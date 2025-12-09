<?php

require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'model/Signup.php';

class ControllerAddEditItem extends Controller {

    const UPLOAD_ERR_OK = 0;



    //page d'accueil.
    public function index() : void {
        $this->profile();
    }

    public function profile() : void {
        (new View("Add_edit_item"))->show([]);
    }
    

    public function save (){

        if (isset($_POST["title"] ) && isset($_POST["description"] )&& isset($_POST["duration"] )&& isset($_POST["starting_bid"] )&& isset($_POST["buy_now_price"]) && isset($_POST["direct_price"])){
            {
                
            }

    }
}
