<?php
require_once "framework/Model.php";
Class categories extends Model{
    // Les offres d'achat faites par les utilisateurs sur les annonces.
    private String $name;
    private int $priority;
    private int $id;
    public function __construct($id,string $name ,int $priority){
        // Vérifie les règles métiers.
       $this->id = $id;
       $this->name = $name;
       $this->priority = $priority;

    }
    public function get_id(){
        return $this->id;
    }

    public function get_name(){
        return $this->name;
    }
    public function set_name(String $category_name){
        if(categories::is_not_already_in_use($category_name)){
            $this->name = $category_name;
        }
    }
    public function get_priority(){
        return $this->priority;
    }
    public function set_priority(int $priority){
        if( $priority >= 0 ){
            $this->priority = $priority;
        }
    }

    /**
     * Retourne toutes les catégories triées par priorité (ASC).
     * @return categories[]|false
     */
    public static function get_all() {
        try {
            $pdo = self::execute("SELECT * FROM categories ORDER BY priority ASC", []);
            if (!$pdo) return false;
            $rows = $pdo->fetchAll(PDO::FETCH_ASSOC);
            $tab = [];
            foreach ($rows as $row) {
                $tab[] = new categories(
                    id:      (int) $row['id'],
                    name:     (string)$row['name'],
                    priority: (int)$row['priority']
                );
            }
            return $tab;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function get_priority_max() {
        try {
            $pdo = self::execute(
                "SELECT max(priority) FROM categories ",[]);
            if (!$pdo){ return false;};
            $rep = $pdo->fetchColumn();

            return $rep !== false ? (int)$rep : 0;
        } catch (PDOException $e) {
            return false;
        }
    }
    public static function get_categorie_by_id(int $id) {
        try {
            $pdo = self::execute(
                "SELECT * FROM categories WHERE id=:categorie_id",
                array(
                    "categorie_id"=> $id,
                ));
            if (!$pdo){ return false;};
            $row = $pdo->fetch(PDO::FETCH_ASSOC);
            return  new categories(
                id:       (int)$row['id'],
                name:     (string)$row['name'],
                priority: (int)$row['priority']
            );
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function get_categorie_by_priority(int $priority){
        try {
            $pdo = self::execute(
                "SELECT * FROM categories WHERE priority=:priority",
                array(
                    "priority"=> $priority,
                ));
            if (!$pdo){ return false;};
            $row = $pdo->fetch(PDO::FETCH_ASSOC);
            return  new categories(
                id:       (int)$row['id'],
                name:     (string)$row['name'],
                priority: (int)$row['priority']
            );
        } catch (PDOException $e) {
            return false;
        }
    }

    // Vérifie si un nom donnée n'est pas déjà dans la BDD categories.
    public static function is_not_already_in_use(String $name):bool{
        try{
            $pdo = self::execute("SELECT * FROM categories WHERE name = :name",  array(
                "name" => $name
            ));
            return $pdo->rowCount() === 0;
        }catch (PDOException $e) {
            return false;
        }
    }
    // Mets à jours les informations dans la BDD.
    public function save():bool {
        try{
            $pdo = self::execute("
                        UPDATE categories
                        SET
                            name = :name,
                            priority = :priority
                        WHERE id = :id",
                array(
                    "name" => $this->get_name(),
                    "priority" => $this->get_priority(),
                    "id" => $this->get_id(),
                )
            );
            if (!$pdo) {return false;}
            return true;
        }catch (PDOException $e) {
            return false;
        }
    }

    // Supprime une categorie dans la BDD.
    public static function delete(categories $categorie):bool {
        if(count(item_categories::get_items_category_by_category($categorie->get_id())) == 0 ){
            try{
                $pdo = self::execute("DELETE FROM categories WHERE id=:id",
                    [
                        "id" => $categorie->get_id()
                    ]
                );
                if(!$pdo){return false;}
                return true;
            }catch (PDOException $e) {
                return false;
            }
        }
        return false;
    }

}