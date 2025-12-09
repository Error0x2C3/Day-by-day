<?php

require_once "framework/Model.php";

class Signup extends Model {



    public function __construct(public string $pseudo, public string $hashed_password, public ?string $profile = null, public ?string $picture_path = null) {

    }

    static public function signup($Email ,$Full_name , $pseudo ,$password ) : String {

    $query = self::execute(" INSERT users(full_name ,email ,pseudo,password) values (:Full_name ,:Email , :pseudo ,:password)",
     ["Full_name"=>$Full_name ,"Email"=>$Email , "pseudo"=>$pseudo ,"password" => $password]);
     return "b1 passer";
    }

}
