<?php
require_once "framework/Model.php";
Class Bids extends Model{
    //TODO Save ok + persist ok + getter ok + setter ok + validations
    public function __construct(
        // La clé primaire est composée des colonnes owner, item et created_at.
        private int $owner_id,
        private int $item_id,
        private DateTime $created_at,
        private float $amount,
    ){
        if($this->amount <= 0 && !preg_match(Configuration::get("decimal_regles"),(string)$this->amount)){
            throw new InvalidArgumentException(
                "Le montant de l'enchère doit être positif."
            );
        }
    }

    public function get_owner_id(): int{
        return $this->owner_id;
    }
    public function get_item_id(): int{
        return $this->item_id;
    }
    public function get_created_at(): DateTime{
        return  $this->created_at;
    }
    public function get_amount(): int {
        return $this->amount;
    }

    public function set_owner_id(int $owner_id): bool{
        if($owner_id > 0){
            $this->owner_id = $owner_id;
            return true;
        }
        return false;
    }
    public function set_item_id(int $item_id): bool{
        if($item_id > 0){
            $this->item_id = $item_id;
            return true;
        }
        return false;
    }
    public function set_created_at(DateTime $created_at): bool{
        if($created_at instanceof DateTime){
            $this->created_at = $created_at;
            return true;
        }
        return false;
    }
    public function set_amount(int $amount): bool{
        if($amount >0 && preg_match(Configuration::get("decimal_regles"),(string)$amount)){
            $this->amount = $amount;
            return true;
        }
        return false;
    }


    /*
    Mets à jours les informations dans la BDD.
    Seule la colonne amount a besoin d'être modifiée
    car les autres servent d'identifiants pour trouver la bonne ligne.
     */
    public function save(): bool {
        try{
            $pdo = self::execute("
                        UPDATE bids
                        SET
                            amount = :amount
                        WHERE owner = :owner
                        AND item = :item
                        AND created_at = :created_at
                        ",
                array(
                    "owner" => $this->get_owner_id(),
                    "item" => $this->get_item_id(),
                    "created_at" => $this->get_created_at(),
                    "amount" => $this->get_amount(),
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
        int $owner_id,
        int $item_id,
        DateTime $created_at,
        float $amount,
    ): bool {
        try {
            $pdo = self::execute(
                "INSERT INTO items (owner, item, created_at, amount)
                    VALUES (:owner, :item,:created_at, :amount)",
                array(
                    "owner" => $owner_id,
                    "item" => $item_id,
                    "created_at" => $created_at,
                    "amount" => $amount
                )
            );
            if (!$pdo) {return false;}
            return true;
        }catch (PDOException $e) {
            return false;
        }

    }
}