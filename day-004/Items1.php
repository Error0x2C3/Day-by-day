<?php

require_once "framework/Model.php";

class Items extends Model {

    public function __construct(
        public String $title,
        public String $description,
        public int $owner_id,
        public DateTime $created_at,
        public float $buy_now_price,
        public int $duration_days,
        public float $starting_bid,
        public int $id=0,
    ) {
    }

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
    public function validate_title($title): bool{
        if($title >= Configuration::get("title_item_min")){
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

    private $errors = [];
    public function save ($owner,$created_at){

        if($this->validate()){
            $this->persist( $this->title , $this->description,$owner,$created_at,  $this->duration_days , $this->starting_bid );
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
