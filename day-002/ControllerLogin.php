<?php

require_once "framework/Controller.php";
require_once 'model/User.php';
require_once "utils/AppTime.php";
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
                    $this->log_user(User::get_instance_user($login, $password),"Item","browse_items_view");
                }else{
                    $this->management_errors($login, $password);
                }
            }else{
                $this->management_errors($login, $password);
            }
        }else {
            $error =  "Enter your email and password !";
            $this->management_errors($login, $password,$error);
        }
    }

    // Gére la connection guest
    public function login_connect_as_guest() : void {
        $email = "guest@epfc.eu";
        $password = "guest";
        if(User::is_exists($email)){
            $this->log_user(User::get_instance_user($email, $password),"browseItems","view");
        }else{
            if(User::persist("guest",$email,"guest_user",$password)){
                $this->log_user(User::get_instance_user($email, $password),"browseItems","view");
            }
        }
    }
    // Gére la connection debug.
    public function login_connect_as_xavier() : void {
        $this->log_user(User::get_instance_user("xapigeolet@epfc.eu", "Password1,"),"Item","browse_items_view");
    }
    public function login_connect_as_marc() : void {
        $this->log_user(User::get_instance_user("mamichel@epfc.eu", "Password1,"),"Item","browse_items_view");
    }
    public function login_connect_as_quhouben() : void {
        $this->log_user(User::get_instance_user("quhouben@epfc.eu", "Password1,"),"Item","browse_items_view");
    }
    public function login_connect_as_bover() : void {
        $this->log_user(User::get_instance_user("boverhaegen@epfc.eu", "Password1,"),"Item","browse_items_view");
    }

    // Gére le renvoie des informations en cas d'erreurs.
    public function management_errors( String $login, String $password, string $error=""): void {
        $errorArray [] = $login;
        $errorArray [] = $password;
        $error = strlen($error) == 0 ? "Login or password incorrect !" : $error;
        (new view("login"))->show(
            [
                'error' => $error,
                'errorArray' => $errorArray
            ]
        );
    }
}