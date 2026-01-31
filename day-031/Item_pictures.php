<?php


require_once "framework/Model.php";
class  Item_pictures extends  Model {
    // Save , persist  , validation , getter setter , validate_conctruct
    // Contient les photos des annonces.
    private int $item;
    private int $priority;
    private string $picture;
    public function __construct(int $item,int $priority,string $picture){
        // Vérifie les règles métiers.
        $this->validate_conctruct($item,$priority,$picture);
    }


    public function get_item(): int{
        return $this->item;
    }

    public function get_priority(): int{
        return $this->priority;
    }

    public function get_picture(): string{
        return $this->picture;
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
    public function set_picture(string $picture): void{
        if($this->validate_picture($picture)){
            $this->picture = $picture;
        }
    }
    // Vérifie si l'annonce existe.
    public function validate_item_id($item_id):bool{
        if(!Items::get_Item_instance_ById($item_id) instanceof Items){
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
            $this->picture = $picture;
        }else{throw new InvalidArgumentException("Priority doit être supérieur à zéro.");}
    }

    // Mets à jours les informations dans la BDD.
    public function save(): bool {
        try{
            $pdo = self::execute("
                        UPDATE item_pictures
                        SET
                            priority = :priority,
                            picture_path = :picture_path
                        WHERE item = :item",
                array(
                    "priority"=>$this->get_priority(),
                    "picture"=>$this->get_picture(),
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


}