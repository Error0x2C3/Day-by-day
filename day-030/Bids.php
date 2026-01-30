<?php
require_once "framework/Model.php";
Class Bids extends Model{
    //TODO Save ok + persist ok + getter ok + setter ok + validations.
    // La clé primaire est composée des colonnes owner, item et created_at.
    private int $owner_id;
        private int $item_id;
        private DateTime $created_at;
        private float $amount;
    public function __construct(int $owner_id,int $item_id, DateTime $created_at,int $amount){
        /*
        Il n'est pas possible de faire une offre d'achat sur
        une annonce clôturée (période d'enchères terminée
        ou prix d'achat immédiat atteint).
        Il n'est pas non plus possible de faire une offre d'achat
        sur une annonce que l'on possède soi-même ou sur une annonce qui n'a pas encore débuté.
         */
        $item = Items::get_Item_instance_ById($item_id);
        if($item->should_close() || $item->get_owner_id() == $owner_id ||  !$item->has_started()){
            throw new InvalidArgumentException(
                "Il n'est pas possible de faire une offre d'achat sur cette annonce."
            );
        }
        $this->owner_id = $owner_id;
        $this->item_id = $item_id;
        if(!$this->validate_created_at($created_at,$item)) {
            throw new InvalidArgumentException(
                "La date de crétion de l'offre n'est pas bonne."
            );
        }else{
            $this->created_at = $created_at;
        }
        /*
          amount, de type DECIMAL(10,2), doit être strictement supérieur
          au montant de l'offre la plus élevée déjà faite sur l'annonce
          (ou supérieure à starting_bid si aucune offre n'a encore été faite,
          ou égale à buy_now_price dans le cadre d'un achat immédiat).
           */
        if($item->has_auction()){
            if($amount <= 0 && !preg_match(Configuration::get("decimal_regles"),(string)$amount) && !$this->validate_amount($item_id,$amount,1)){
                throw new InvalidArgumentException(
                    "Le montant de l'enchère doit être positif."
                );
            }
        }else if($item->has_buy_now()){
            if(!$this->validate_amount($item_id,$amount,2)){
                throw new InvalidArgumentException(
                    "Le montant de l'enchère doit être positif."
                );
            }
        }
        $this->amount = $amount;
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

    // Vérifie si la date de création de l'offre est compris entre celle du début de l'annonce et sa fin.
    public function validate_created_at(DateTime $created_at, Items $item):bool{
        if($created_at >= $item->get_created_at() && $created_at <= $item->time_passed()){
            return true;
        }
        return false;
    }

    /*
    Le montant d'une offre d'achat doit toujours être supérieur
    au montant de l'offre la plus élevée déjà faite sur l'annonce,
    (ou à l'enchère minimale si aucune offre n'a encore été faite).
     */
    public function validate_amount($item_id, $amount,int $option):bool{
        // Option 1 item a un système d'enchère et d'achat immédiat.
        // option 2 item a juste un système d'achat immédiat.
        if($option == 1 ){
            return $this->validate_amount_for_aunction($item_id, $amount);
        }elseif ($option == 2){
            return $this->validate_amount_for_buy_now($item_id, $amount);
        }
        return false;
    }

    // Vérification d'amount pour l'option système d'achat immédiat.
    public function validate_amount_for_buy_now($item_id, $amount){
        try{
            // COALESCE sert à choisir la première valeur qui n’est PAS NULL.
            $pdo = self::execute("SELECT i.buy_now_price
                                    FROM items i
                                    WHERE i.id = :itemId
                                      AND i.buy_now_price IS NOT NULL
                                      AND i.buy_now_price > 0;
                                    ",
                array(
                    "itemId"=> $item_id,
                ));
            // Si la requête ne trouve rien, il retourne false.
            if (!$pdo) {
                return false;
            }
            $min_required = $pdo->fetch(PDO::FETCH_ASSOC);
            if($amount >=$min_required){
                return true;
            }
            return false;
        }catch (PDOException $e) {
            return false;
        }
    }
    // Vérification d'amount pour l'option système d'enchère.
    public function validate_amount_for_aunction($item_id, $amount):bool{
        try{
            // COALESCE sert à choisir la première valeur qui n’est PAS NULL.
            $pdo = self::execute("SELECT 
                                      COALESCE(
                                        (SELECT MAX(b.amount) FROM bids b WHERE b.item = i.id),
                                        i.starting_bid
                                      ) AS min_required
                                    FROM items i
                                    WHERE i.id = :itemId
                                      AND i.starting_bid IS NOT NULL
                                      AND i.starting_bid > 0;
                                    ",
                array(
                    "itemId"=> $item_id,
                ));
            // Si la requête ne trouve rien, il retourne false.
            if (!$pdo) {
                return false;
            }
            $min_required = $pdo->fetch(PDO::FETCH_ASSOC);
            if($amount > $min_required){
                return true;
            }
            return false;
        }catch (PDOException $e) {
            return false;
        }
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