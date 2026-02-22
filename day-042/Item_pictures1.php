<?php


require_once "framework/Model.php";
class  Item_pictures extends  Model {
    // Contient les photos des annonces.
    private int $item;
    private int $priority;
    private string $picture_path;
    public function __construct(int $item,int $priority,string $picture_path,$verif_regle_metier=True){
        // Vérifie les règles métiers.
        if($verif_regle_metier){

        $this->validate_conctruct($item,$priority,$picture_path);
        }
        else{
            $this->item = $item;
            $this->priority = $priority;
            $this->picture_path = $picture_path;
        }
    }


    public function get_item(): int{
        return $this->item;
    }

    public function get_priority(): int{
        return $this->priority;
    }

    public function get_picture_path(): string{
        return $this->picture_path;
    }
    public function get_picture_path_vignette():string{
        $original_file = $this->get_picture_path();
        // 1. On récupère les informations du fichier.
        /*
              Ex :
                  $original_file = "952d806ffdcee7da_1762786660.jpg";
                  $info['filename']  -> renvoie "952d806ffdcee7da_1762786660";
                  $info['extension'] -> renvoie "jpg";
         */
        $info_file = pathinfo($original_file);
        // 2. On reconstruit le nom avec le suffixe.
        $picture_vingette = $info_file['filename']."_thumbnail." . $info_file['extension'];
        // 3. Résultat final.
        return $info_file['filename']."_thumbnail." . $info_file['extension'];
    }
    public function set_item(int $item): void{
        if($this->validate_item_id($item)){
            $this->item = $item;
        }
    }
    public function set_priority(int $priority): void{
        if($this->validate_priority($priority)){
            $this->priority = $priority;
        }
    }
    public function set_picture_path(string $picture_path): void{
        if($this->validate_picture($picture_path)){
            $this->picture_path = $picture_path;
        }
    }
    // Vérifie si l'annonce existe.
    public function validate_item_id($item_id):bool{
        if(!Items::get_Item_instance_by_id($item_id) instanceof Items){
            // Alors $item_id est faux.
            return false;
        }
        return true;
    }

    // Vérifie $priority
    public function validate_priority(int $priority):bool{
        if($priority > 0){return true;}
        return false;
    }
    // Vérifie $picture, elle est caractérisée par un chemin vers le fichier image.
    public function validate_picture(string $picture):bool{
        if(file_exists($picture)){
            return true;
        }
        return false;
    }

    public function  validate_conctruct(int $item,int $priority,string $picture):void{
        if($this->validate_item_id($item)){
            $this->item = $item;
        }else{throw new InvalidArgumentException("L'annonce n'existe pas.");}
        if($this->validate_priority($priority)){
            $this->priority = $priority;
        }else{throw new InvalidArgumentException("Priority doit être supérieur à zéro.");}
        if($this->validate_picture($picture)){
            $this->picture_path = $picture;
        }else{throw new InvalidArgumentException("Priority doit être supérieur à zéro.");}
    }

    // Mets à jours les informations dans la BDD.
    public function save(): bool {
        // La clé primaire est composée des colonnes item et priority.
        try{
            $pdo = self::execute("
                        UPDATE item_pictures
                        SET
                            picture_path = :picture_path
                        WHERE item = :item
                        AND priority= :priority",
                array(
                    "priority"=>$this->get_priority(),
                    "picture"=>$this->get_picture_path(),
                    "item"=> $this->get_item()
                )
            );
            if (!$pdo) {return false;}
            return true;
        }catch (PDOException $e) {
            return false;
        }
    }

    // Enregistre les données d'une offre dans la BDD.
    // Création d'un nouvel objet dans la BDD.
    public static function persist(
        // La clé primaire est composée des colonnes item et priority.
        int $item,
        int $priority,
        string $picture
    ): bool {
        try {
            $pdo = self::execute(
                "INSERT INTO items (item, priority, picture)
                    VALUES (:item, :priority, :picture)",
                array(
                    "item" => $item,
                    "priority" => $priority,
                    "picture" => $picture
                )
            );
            if (!$pdo) {return false;}
            return true;
        }catch (PDOException $e) {
            return false;
        }

    }

    public function priorityUp(): bool
{
    if ($this->priority <= 1) {
        return false; // déjà en haut
    }

    try {

        // libère temporairement la priorité cible
        self::execute(
            "UPDATE item_pictures
             SET priority = 0
             WHERE item = :item AND priority = :target",
            [
                "item"   => $this->item,
                "target" => $this->priority - 1
            ]
        );

        // monte l'image courante
        self::execute(
            "UPDATE item_pictures
             SET priority = priority - 1
             WHERE item = :item AND priority = :current",
            [
                "item"    => $this->item,
                "current" => $this->priority
            ]
        );

        // remet l'autre image à l'ancienne position
        self::execute(
            "UPDATE item_pictures
             SET priority = :old
             WHERE item = :item AND priority = 0",
            [
                "item" => $this->item,
                "old"  => $this->priority
            ]
        );

        $this->priority--;

        return true;

    } catch (PDOException $e) {
        return false;
    }

    


}
public static function move_up(int $item_id, int $priority): void
{
    if ($priority <= 1) {
        return;
    }

    // Image au-dessus → priorité temporaire
    self::execute(
        "UPDATE item_pictures
         SET priority = 0
         WHERE item = :item AND priority = :p",
        [
            "item" => $item_id,
            "p" => $priority - 1
        ]
    );

    // Image courante → priorité - 1
    self::execute(
        "UPDATE item_pictures
         SET priority = :p
         WHERE item = :item AND priority = :cur",
        [
            "item" => $item_id,
            "p" => $priority - 1,
            "cur" => $priority
        ]
    );

    // Temporaire → priorité courante
    self::execute(
        "UPDATE item_pictures
         SET priority = :p
         WHERE item = :item AND priority = 0",
        [
            "item" => $item_id,
            "p" => $priority
        ]
    );
}
public static function move_down(int $item_id, int $priority): void
{
    // Image en-dessous → priorité temporaire
    self::execute(
        "UPDATE item_pictures
         SET priority = 0
         WHERE item = :item AND priority = :p",
        [
            "item" => $item_id,
            "p" => $priority + 1
        ]
    );

    // Image courante → priorité + 1
    self::execute(
        "UPDATE item_pictures
         SET priority = :p
         WHERE item = :item AND priority = :cur",
        [
            "item" => $item_id,
            "p" => $priority + 1,
            "cur" => $priority
        ]
    );

    // Temporaire → priorité courante
    self::execute(
        "UPDATE item_pictures
         SET priority = :p
         WHERE item = :item AND priority = 0",
        [
            "item" => $item_id,
            "p" => $priority
        ]
    );
}




}