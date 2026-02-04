<?php
require_once "framework/Model.php";
Class Bids extends Model{
    // Les offres d'achat faites par les utilisateurs sur les annonces.
    private int $owner_id;
    private int $item_id;
    private DateTime $created_at;
    private float $amount;
    public function __construct(int $owner_id,int $item_id, DateTime $created_at,float $amount,bool $verif_regle_metier =true){
        // Vérifie les règles métiers.
        if($verif_regle_metier){
            $this->validate_construct($owner_id,$item_id,$created_at,$amount);
        }else{
            $this->owner_id = $owner_id;
            $this->item_id = $item_id;
            $this->created_at = $created_at;
            $this->amount = $amount;
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
    public function get_amount(): float {
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
    public function set_amount(float $amount): bool{
        if($amount >0 && preg_match(Configuration::get("decimal_regles"),(string)$amount)){
            $this->amount = $amount;
            return true;
        }
        return false;
    }

    // Vérifie si l'utilisateur existe.
    public function validate_owner_id(int $owner_id):bool{
        if(User::get_user_by_id($owner_id)){
            return true;
        }
        return false;
    }

    // Vérifie si l'annonce existe.
    public function validate_item_id($item_id):bool{
        if(!Items::get_Item_instance_by_id($item_id) instanceof Items){
            // Alors $item_id est faux.
            return false;
        }
        return true;
    }
    // Vérifie si la date de création de l'offre est compris entre celle du début de l'annonce et sa fin.
    public function validate_created_at(DateTime $created_at, int$item_id):bool{
        $item = Items::get_Item_instance_by_id($item_id);
        if($created_at >= $item->get_created_at() && $created_at <= $item->get_end_at()){
            return true;
        }
        return false;
    }

    /*
    amount, de type DECIMAL(10,2), doit être strictement supérieur
    au montant de l'offre la plus élevée déjà faite sur l'annonce
    (ou supérieure à starting_bid si aucune offre n'a encore été faite,
    ou égale à buy_now_price dans le cadre d'un achat immédiat).
    */
    public function validate_amount($item_id, $amount):bool{
        $item = Items::get_Item_instance_by_id($item_id);
        if(!preg_match(Configuration::get("decimal_regles"),(string)$amount)){
            return false;
        }
        if($item->has_auction() && !$item->has_buy_now()){
            return $this->validate_amount_for_aunction($item_id, $amount);
        }
        if ($item->has_buy_now() && !$item->has_auction()){
            return $this->validate_amount_for_buy_now($item_id, $amount);
        }
        if($item->has_buy_now() && $item->has_auction()){
            return $this->validate_amount_for_aunction($item_id, $amount) && $this->validate_amount_for_buy_now($item_id, $amount);
        }
        return false;
    }

    // Vérification d'amount pour l'option système d'achat immédiat.
    public function validate_amount_for_buy_now(int $item_id,float $amount){
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
    public function validate_amount_for_aunction(int $item_id,float $amount):bool{
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
    Il n'est pas possible de faire une offre d'achat sur
    une annonce clôturée (période d'enchère terminée
    ou prix d'achat immédiat atteint).
    Il n'est pas non plus possible de faire une offre d'achat
    sur une annonce que l'on possède soi-même ou sur une annonce qui n'a pas encore débutée.
    */
    public function cannot_bid($item_id,$owner_id):bool{
        $item = Items::get_Item_instance_by_id($item_id);
        if($item->should_close() || $item->get_owner_id() == $owner_id ||  !$item->has_started()){
            return true;
        }
        return false;
    }

    public function validate_construct(int $owner_id,int $item_id, DateTime $created_at,float $amount):void {
        if($this->validate_owner_id($owner_id)){
            $this->owner_id = $owner_id;
        }else{ throw new InvalidArgumentException("L'utilisateur n'existe pas.");}

        if($this->validate_item_id($item_id)){
            $this->item_id = $item_id;
        }else{throw new InvalidArgumentException("L'annonce n'existe pas.");}

        if($this->validate_created_at($created_at,$item_id)) {
            $this->created_at = $created_at;
        }else{ throw new InvalidArgumentException("La date de crétion de l'offre n'est pas bonne.");}

        if($this->validate_amount($item_id, $amount)){
            $this->amount = $amount;
        }else{throw new InvalidArgumentException("Le montant de l'enchère doit être positif.");}

        if($this->cannot_bid($item_id,$owner_id)){
            throw new InvalidArgumentException(
                "Il n'est pas possible de faire une offre d'achat sur
         cette annonce");
        }
    }
    /*
    Mets à jours les informations dans la BDD.
    Seule la colonne amount a besoin d'être modifiée
    car les autres servent d'identifiants pour trouver la bonne ligne.
     */
    public function save(): bool {
        // La clé primaire est composée des colonnes owner, item et created_at.
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
                    "created_at" => $this->get_created_at()->format("Y-m-d H:i:s"),
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
        // La clé primaire est composée des colonnes owner, item et created_at.
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

    /* =========================================================
       Browse items.
   ========================================================= */
    // Donne l'enchère dont le mountant est le plus élevé sur une annonce.
    public static function get_highest_bidder($item_id):bool|Bids{
        try {
            $pdo = self::execute("SELECT b.* FROM bids b
                                JOIN items i ON b.item = i.id
                                WHERE i.starting_bid IS NOT NULL 
                                AND i.starting_bid > 0   
                                AND b.amount >= i.starting_bid
                               	AND b.item = :item_id
                                ORDER BY b.amount DESC, b.created_at ASC
                                LIMIT 1
                                ",
                array(
                    "item_id" => $item_id
                ));
            // Si la requête ne trouve rien, il retourne false.
            if (!$pdo) {
                return false;
            }
            $row = $pdo->fetch(PDO::FETCH_ASSOC);
            if ($row === false) {
                return false;
            }
            return new Bids(
                owner_id: (int)$row['owner'],
                item_id:  (int)$row['item'],
                created_at: new DateTime($row['created_at']),
                amount: (float) $row['amount'],
                verif_regle_metier: false
            );
        } catch (PDOException $e) {
            return false;
        }
    }

    // Obtenir le nom du propriètaire d'une annonce.
    public function get_owner_name():string{
        $name = User::get_user_by_id($this->owner_id)->get_pseudo();
        return $name;
    }

}