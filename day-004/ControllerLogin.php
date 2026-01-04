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
                    $this->log_user(User::get_instance_user($login, $password),"item","browse_items_view");
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
        $full_name = "Guest";
        $pseudo = "guest_user";
        $this->management_login_connect_as($email,$password,$full_name,$pseudo);
    }
    // Gére la connection debug pour Mr Xavier.
    public function login_connect_as_xavier() : void {
        $email = "xapigeolet@epfc.eu";
        $password = "Password1,";
        $full_name = "Xavier Pigeolet";
        $pseudo ="Xavier";
        $this->management_login_connect_as($email,$password,$full_name,$pseudo);
    }
    // Gére la connection debug pour Mr Marc.
    public function login_connect_as_marc() : void {
        $email = "mamichel@epfc.eu";
        $password = "Password1,";
        $full_name = "Marc Michel";
        $pseudo = "Marc";
        $this->management_login_connect_as($email,$password,$full_name,$pseudo);
    }
    // Gére la connection debug pour Mr Quentin.
    public function login_connect_as_quhouben() : void {
        $email = "quhouben@epfc.eu";
        $password = "Password1,";
        $full_name = "Quentin Houben";
        $pseudo = "Quentin";
        $this->management_login_connect_as($email,$password,$full_name,$pseudo);
    }
    // Gére la connection debug pour Mr Boris.
    public function login_connect_as_bover() : void {
        $email = "boverhaegen@epfc.eu";
        $password = "Password1,";
        $full_name = "Boris Verhaegen";
        $pseudo = "Boris";
        $this->management_login_connect_as($email,$password,$full_name,$pseudo);
    }

    // Gére la vérification durant les login_connect_as en une seule fois.
    public function management_login_connect_as($email,$password,$full_name,$pseudo){
        if(User::is_exists($email)){
            $this->log_user(User::get_instance_user($email, $password),"item","browse_items_view");
        }else{
            if(User::persist($full_name,$email,$pseudo,$password)){
                $this->log_user(User::get_instance_user($email, $password),"item","browse_items_view");
            }
        }
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