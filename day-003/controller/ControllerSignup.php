<?php

require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'model/Signup.php';

class ControllerSignup extends Controller {

    const UPLOAD_ERR_OK = 0;



    //page d'accueil.
    public function index() : void {
        $this->subcribe();
    }

    public function subscribe() : void {
        (new View("signup"))->show([]);
    }
    public function signup()  : void {
        if (isset($_POST["Email"] ) && isset($_POST["Full_name"] )&& isset($_POST["pseudo"] )&& isset($_POST["password"] )&& isset($_POST["confirm_password"])){
            if ($_POST["password"] === $_POST["confirm_password"])
                {
                    $st = Signup::signup($_POST["Full_name"] ,$_POST["Email"] , $_POST["pseudo"] ,$_POST["password"]);
                }

        }
        else{
            echo "in else";
        }

    }


}
