<?php
require_once "framework/Controller.php";
require_once "framework/Configuration.php";
require_once "framework/Tools.php";
require_once "utils/AppTime.php";

class ControllerSetup extends Controller {
    public function index(): void {
        $this->install();
    }

    public function restore(): void {
        $this->install(true);
    }

    // restaure l'original ou le backup privé (si $backup est true)
    public function install(bool $backup = false): void {
        echo "<p>Importation des données <b>";
        echo ($backup ? "de votre backup privé" : "originales");
        echo "</b> en cours...</p>";
        echo "<p><b>Suppose que vous avez les droits d'écrire dans /uploads</b></p>";
        try {
            $webroot = Configuration::get("web_root");
            $dbname = Configuration::get("dbname");
            $pdo = new PDO(
                "mysql:host=" . Configuration::get("dbhost") ,
                Configuration::get("dbuser"),
                Configuration::get("dbpassword")
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = file_get_contents("database/{$dbname}.sql");
            $query = $pdo->prepare($sql);

            if ($query->execute()) {
                echo "<p>Le schéma de la base de données a été correctement créé.</p>";
            } else {
                echo "<p>Problème lors de la création du schéma de la base de données.</p>";
            }

            $dump_file = "database/{$dbname}_dump.sql";
            if($backup) {
                $dump_file = "database/{$dbname}_private_dump.sql";
            }

            if (file_exists($dump_file)) {
                $sql = file_get_contents($dump_file);
                $query = $pdo->prepare($sql);

                if ($query->execute()) {
                    $query->closeCursor();
                    echo "<p>Les données ont été correctement importées depuis <code>{$dump_file}</code></p>";
                    if(!$backup) {
                        echo "<p>Ajustement des dates...</p>";
                        $this->adjust_all_date_fields($pdo);
                        echo "<p>Les dates ont été ajustées avec succès!</p>";
                    }
                } else {
                    echo "<p>Problème lors de l'importation des données.</p>";
                }
                
            }

            // Import des fichiers uploads
            $imagesBackupPath = "database/{$dbname}_images_backup/";
            if($backup) {
                $imagesBackupPath = "database/{$dbname}_private_images_backup/";
            }
            $uploadsPath = "uploads/";
            
            if (is_dir($imagesBackupPath)) {
                echo "<p>Importation des fichiers uploads depuis le backup en cours...</p>";
                
                if (is_dir($uploadsPath)) {
                    $this->deleteDirectoryContents($uploadsPath);
                }

                $this->copyDirectory($imagesBackupPath, $uploadsPath);
                echo "<p>Les fichiers uploads ont été correctement restaurés depuis <code>{$imagesBackupPath}</code></p>";
            } else {
                echo "<p>Aucun backup d'uploads à restaurer.</p>";
            }

            echo "<a href='{$webroot}'>Retour à l'index</a>";
        } catch (Exception $exc) {
            throw new Exception("Erreur lors de l'accès à la base de données : " . $exc->getMessage());
        }
    }

    public function export(bool $backup = true): void {
        echo "<p>Exportation des données <b>";
        echo ($backup ? "de votre backup privé" : "originales");
        echo "</b> en cours...</p>";
        echo "<p><b>Suppose que vous avez les droits d'écrire dans /database</b></p>";
        //from https://gist.github.com/micc83/fe6b5609b3a280e5516e2a3e9f633675
        $mysql_path = Configuration::get("mysql_path");
        $webroot = Configuration::get("web_root");
        $dbhost = Configuration::get("dbhost");
        $dbname = Configuration::get("dbname");
        $dbuser = Configuration::get("dbuser");
        $dbpassword = Configuration::get("dbpassword");

        $file = dirname(__FILE__) . "/../database/{$dbname}_private_dump.sql";
        if(!$backup) {
            $file = dirname(__FILE__) . "/../database/{$dbname}_dump.sql";
        }

        $output = [];
        exec("{$mysql_path}mysqldump --no-create-info --user={$dbuser} --password={$dbpassword} --host={$dbhost} {$dbname} --result-file={$file} 2>&1", $output);

        if (count($output) == 0) {
            $path = $backup ? "database/{$dbname}_private_dump.sql" : "database/{$dbname}_dump.sql";
            echo "<p>Les données ont été exportées dans le fichier <code>{$path}</code></p>";
        } else {
            throw new Exception(json_encode($output));
        }

        // Export des fichiers uploads
        echo "<p>Exportation des fichiers uploads en cours...</p>";
        
        $backupPath = dirname(__FILE__) . "/../database/{$dbname}_private_images_backup/";
        if(!$backup) {
            $backupPath = dirname(__FILE__) . "/../database/{$dbname}_images_backup/";
        }
        $uploadsPath = dirname(__FILE__) . "/../uploads/";

        if (is_dir($uploadsPath)) {
            if (is_dir($backupPath)) {
                $this->deleteDirectoryContents($backupPath);
            } else {
                mkdir($backupPath, 0777, true);
            }
            $this->copyDirectory($uploadsPath, $backupPath);
            $path = $backup ? "database/{$dbname}_private_images_backup/" : "database/{$dbname}_images_backup/";
            echo "<p>Les fichiers uploads ont été sauvegardés dans le dossier <code>{$path}</code></p>";
        } else {
            echo "<p>Aucun dossier d'uploads à sauvegarder.</p>";
        }

        echo "<a href='{$webroot}'>Retour à l'index</a>";
    }

    private function deleteDirectoryContents(string $dir): void {
        if (!is_dir($dir)) {
            return;
        }
        
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $fullPath = $dir . $file;
                if (is_dir($fullPath)) {
                    $this->deleteDirectoryContents($fullPath . '/');
                    rmdir($fullPath);
                } else {
                    unlink($fullPath);
                }
            }
        }
    }

    private function copyDirectory(string $source, string $destination): void {
        if (!is_dir($source)) {
            return;
        }

        if (!is_dir($destination)) {
            mkdir($destination, 0777, true);
        }

        $files = scandir($source);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $sourcePath = $source . $file;
                $destPath = $destination . $file;
                
                if (is_dir($sourcePath)) {
                    $this->copyDirectory($sourcePath . '/', $destPath . '/');
                } else {
                    copy($sourcePath, $destPath);
                }
            }
        }
    }
    
    /**
     * Ajuste tous les champs temporels de la base de données.
     */
    private function adjust_all_date_fields($pdo): void {        
        $dbname = Configuration::get("dbname");
        $appTime = AppTime::get_current_datetime();
        $referenceDate = '2025-10-15 14:00:00'; //toutes les dates des données du dump sont avant cette date
        
        $appDateTime = new DateTime($appTime);
        $refDateTime = new DateTime($referenceDate);
        $diff = $appDateTime->diff($refDateTime);
        $daysDiff = $diff->days;
        
        if ($appDateTime < $refDateTime) {
            $daysDiff = -$daysDiff; 
        }
        
        $stmt = $pdo->query("
            SELECT TABLE_NAME, COLUMN_NAME 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = '$dbname' 
            AND DATA_TYPE IN ('datetime', 'timestamp', 'date')
            AND TABLE_NAME NOT LIKE 'v_%'
        ");
        
        $dateFields = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($dateFields as $field) {
            $tableName = $field['TABLE_NAME'];
            $columnName = $field['COLUMN_NAME'];
            
            $pdo->exec("UPDATE $tableName SET $columnName = DATE_ADD($columnName, INTERVAL $daysDiff DAY)");
        }
    }
}

