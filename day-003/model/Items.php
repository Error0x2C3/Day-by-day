<?php

require_once "framework/Model.php";

class Items extends Model {



    public function __construct(String $title ,String $description, int $duration ,int $starting_bid ,int $buy_now_price, int $direct_price ) {

    }

    static public function signup($Email ,$Full_name , $pseudo ,$password ) : String {

    $query = self::execute(" INSERT users(full_name ,email ,pseudo,password) values (:Full_name ,:Email , :pseudo ,:password)",
     ["Full_name"=>$Full_name ,"Email"=>$Email , "pseudo"=>$pseudo ,"password" => $password]);
     return "b1 passer";
    }

}
