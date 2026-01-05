<?php

require_once "framework/Model.php";

class Items extends Model {

    public function __construct(
        public String $title,
        public String $description,
        public DateTime $created_at,
        public int $duration_days,
        public int $owner_id,
        public float $buy_now_price,
        public float $starting_bid,
        public int $id=0,
    ) {
    }
    private $errors = [];
    public function get_title(): string
    {
        return $this->title;
    }
    public function get_description(): string
    {
        return $this->description;
    }
    public function get_owner_id(): int
    {
        return $this->owner_id;
    }
    public function get_created_at(): DateTime
    {
        return $this->created_at;
    }
    public function get_buy_now_price(): float
    {
        return $this->buy_now_price;
    }
    public function get_duration_days(): int
    {
        return $this->duration_days;
    }
    public function get_starting_bid(): int
    {
        /*
        NULL => Pas d’enchères possibles.
        0    => Pas d’enchères possibles.
        Starting_bid > 0 Enchères autorisées.
        Starting_bid < 0 Ivalides.
        "starting_bid si elle est remplie" :
            Signifie contient une valeur stric supérieur à 0.
         */
        return $this->starting_bid;
    }
    public function get_id(): int
    {
        return $this->id;
    }

    public function set_title($title): bool{
        if($this->validate_title($title)){
            return true;
        }
        return false;
    }

    /* =========================================================
        Respect des règle métiers.
    ========================================================= */
    public function validate_title($title): bool{
        if(strlen($title) >= Configuration::get("title_item_min")){
            $pdo = self::execute("Select * from items where owner = :owner",
                array(
                    "owner"=>$this->get_owner_id()
                ));
            $array = $pdo->fetchAll(PDO::FETCH_ASSOC);
            foreach($array as $row){
                if($row['title'] == $title){return false;}
            }
            return true;
        }
        return false;
    }

    public function validate_description($description): bool{
        /*
        Rappel :
            $string = null;
            echo strlen($string); => 0
         */
        if(strlen($description) > 0){
            if(!strlen($description)>= Configuration::get("description_item_min")){
                return false;
            }else{return true;}
        }
        return true;
    }
    public function validate_starting_bid(float $value): bool{
        /*
         NULL => Pas d’enchères possibles valide + implique que l'annonce doit avoir un buy_now_price.
         0    => Pas d’enchères possibles valide.
         Starting_bid > 0 Enchères autorisées valide sous condition.
         Starting_bid < 0 Ivalides / Interdit non valide.
        */
        if($value == null && !$this->has_buy_now()){return false;}
        if($value < 0){return false;}
        if($value > 0){
            // Si c'est > 0, alors doit respecter cette régle.
            if(!preg_match(Configuration::get("decimal_regles"), (string)$value)) {
                return false;
            }
            return false;
        }
        return true;
    }

    public function validate_buy_now_price(float $value):bool{
        /*
        NULL => Pas d’enchères possibles valide.
        0    => Pas d’enchères possibles valide.
        Starting_bid > 0 Enchères autorisées valide sous condition.
        Starting_bid < 0 Ivalides / Interdit non valide.
        */
        if($value > 0){
            // Si c'est > 0, alors doit respecter cette régle.
            if(preg_match(Configuration::get("decimal_regles"),(string)$value)) {
                /*
                si elle est remplie, doit être strictement supérieure à 0
                et strictement supérieur à starting_bid (si starting_bid n'est pas nulle)
                 */
                if($this->has_auction() && $value <= $this->get_starting_bid()){
                    return false;
                }
                return true;
            }
            return false;
        }
        if($value < 0){return false;}
        return true;
    }

    // Vérifie que duration_days est entre 1 et 365
    public function validate_duration_days(int $value): bool{
        return $this->get_duration_days() >= Configuration::get("duration_days_min") && $this->get_duration_days() <= Configuration::get("duration_days_max");
    }
    /* =========================================================
        Vérification Pour les annonces.
   ========================================================= */
    // Annonce a le système d'enchère ou pas.
    public function has_auction(): bool {
        return $this->get_starting_bid() > 0;
    }
    // Annonce a le système d'achat immédiat ou pas.
    public function has_buy_now(): bool {
        return $this->get_buy_now_price() > 0;
    }
    /*
    Vérifie si une annonce peut recevoir des offres d'achat (enchères)
    de la part des utilisateurs de l'application.
    */
    public function can_receive_bids(): bool {
        // Enchères possibles sur l'annonce ou pas.
        return $this->has_auction();
    }

    /*
    Vérifie si une annonce peut recevoir des achats immédiats
    de la part des utilisateurs de l'application.
    */
    public function can_receive_buy_now_price(): bool{
        return $this->has_buy_now();
    }
    /*
    Vérifie si :
        Lorsqu'on a un prix d'achat immédiat, un utilisateur peut décider
        d'acheter immédiatement l'annonce au prix indiqué.
        Un achat immédiat se concrétise par la création d’une offre d'achat (enchère)
        dont le montant est supérieur ou égal à buy_now_price.
        Il faut dot qu'il y ait seulement le système d'achat immédiat,
        et non système d'achat immédiat + système d'enchère.
     */
    public function announcement_can_buy_immediately_by_buy_now_price($price_by_user): bool{
        // TODO  create new offre d'achat (enchère).
        // Vérifie que l'annonce n'a qu'un système celui de l'achat immédiat.
        if(isset($this->buy_now_price) && (!isset($this->starting_bid) || $this->starting_bid == 0) ){
            // Un achat immédiat se concrétise par la création d’une offre d'achat (enchère) dont le montant est supérieur ou égal à buy_now_price.
            if( $price_by_user >= $this->buy_now_price){
                return true;
            }else{
                return false;
            }
        }
        return false;
    }


    public function save ($owner,$created_at){

        if($this->validate()){

            $this->persist( $this->title , $this->description,$this->owner_id,$this->created_at,$this->duration_days ,$this->buy_now_price, $this->starting_bid );
        }
        else{
            return $this->errors;
        }
    }
    public function validate() : bool{
        $erro1 = false;
        $erro2 = false;
        // Validation du titre
        if (strlen($this->title) >= Configuration::get("title_min_length") && strlen($this->title) <= Configuration::get("title_max_length")) {
            $errors["bool_text_min_3_max_255"] = "Title length must be between 3 and 255 characters.";
            $erro1 = true;
        } 

            // Validation du buy_now_price seulement si il est rempli
        if ($this->buy_now_price !== '' && $this->starting_bid !== '') {
            if ((float)$this->buy_now_price > (float)$this->starting_bid) {
                $this->errors["bool_buy_now_price_UP_starting_bid"] = "Buy now price must be greater than starting bid." ;
                $erro2 = true;
            } 

        }
        return $erro1 && $erro2;
    }

    public static  function persist(String $title ,String $description,$owner_id,DateTime $created_at,float $buy_now_price,int $duration_days ,int $starting_bid ): bool{
        self::execute("INSERT INTO items(title,description,owner_id,created_at,buy_now_price,duration_days,starting_bid )
                                  VALUES (:title,:description,:owner_id,:created_at,:buy_now_price,:duration_days,:starting_bid )",
            array(
                "title"=> $title,
                "description"=>$description,
                "owner" => $owner_id,
                "created_at" => $created_at,
                "buy_now_price"=> $buy_now_price,
                "duration"=>$duration_days,
                "starting_bid"=>$starting_bid
            
            ));
        // $user = $pdo->fetch(PDO::FETCH_ASSOC); Pas bon, insert renvoie aucune ligne.
        return true;
    }

}
