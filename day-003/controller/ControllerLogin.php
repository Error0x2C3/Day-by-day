<?php

require_once "framework/Controller.php";
require_once 'model/User.php';
class ControllerLogin extends Controller {
    public function index(): void {
        $this->view();
    }

    public function view(): void {
        (new view("login"))->show();
    }

    /*
     * Vérifie les donnés envoyées de la page login,
     * Et renvoie vers la page selon les cas (fail/success).
     */
    public function login_check(): void {
        $login = isset($_POST['loginId']) ? $_POST['loginId'] : "";
        $password = isset($_POST['loginMdp']) ? $_POST['loginMdp'] : "";
        if($login != "" && $password != ""){
            if( User::is_exists($login) && filter_var($login, FILTER_VALIDATE_EMAIL)){
                if(User::get_instance_user($login, $password) !== false ){
                    $this->log_user(User::get_instance_user($login, $password),"browseItems","view");
                }else{
                    $this->management_errors($login, $password);
                }
            }else{
                $this->management_errors($login, $password);
            }
        }
    }

    // Gére la connection guest
    public function login_connect_as_guest() : void {
        $password = "Password1,";
        $this->log_user(User::get_instance_user("@epfc.eu", "Password1,"),"browseItems","view");
    }
    // Gére la connection debug.
    public function login_connect_as_xavier() : void {
        $this->log_user(User::get_instance_user("xapigeolet@epfc.eu", "Password1,"),"browseItems","view");
    }
    public function login_connect_as_marc() : void {
        $this->log_user(User::get_instance_user("mamichel@epfc.eu", "Password1,"),"browseItems","view");
    }
    public function login_connect_as_quhouben() : void {
        $this->log_user(User::get_instance_user("quhouben@epfc.eu", "Password1,"),"browseItems","view");
    }
    public function login_connect_as_bover() : void {
        $this->log_user(User::get_instance_user("boverhaegen@epfc.eu", "Password1,"),"browseItems","view");
    }

    // Gére le revoie des informations en cas d'erreurs.
    public function management_errors( String $login, String $password): void {
        $errorArray [] = $login;
        $errorArray [] = $password;
        $error ="Login or password incorrect !";
        (new view("login"))->show(
            [
                'error' => $error,
                'errorArray' => $errorArray
            ]
        );
    }
}