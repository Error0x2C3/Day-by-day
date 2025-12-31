<?php

require_once "framework/Model.php";

class Items extends Model {

    private String $title ;
    private String $description;
    private int $owner_id;
    private DateTime $created_at;
    private float $buy_now_price;
    private int $duration_days ;
    private int $starting_bid ;
    private $errors = [];

    public function __construct(String $title ,String $description,int $owner_id,DateTime $created_at,float $buy_now_price, int $duration_days ,int $starting_bid ) {

        $this->title= $title ;
        $this->description = $description;
        $this->owner_id = $owner_id;
        $this->created_at = $created_at;
        $this->buy_now_price = $buy_now_price;
        $this->duration_days = $duration_days ;
        $this->starting_bid =  $starting_bid ;
    }
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
