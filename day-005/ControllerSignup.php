<?php

require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'model/User.php';
class ControllerSignup extends Controller {

    //page d'accueil.
    public function index() : void {
        $this-> subscribe();
    }

    public function subscribe() : void {
        (new View("signup"))->show([]);
    }

    // Ecrire les données de l'utilisateur dans la BDD à l'inscription.
    public function signup()  : void {
        $errorArray = array();
        $error = "";
        if (isset($_POST["Email"] ) && isset($_POST["Full_name"] )&& isset($_POST["pseudo"] )&& isset($_POST["password"] )&& isset($_POST["confirm_password"])){
            $is_corret = User::validate_all($_POST["Full_name"],$_POST["Email"] ,$_POST["pseudo"],$_POST["password"]);
            $password_match = $_POST["password"] === $_POST["confirm_password"];
            if( empty($is_corret)){
                if(User::persist($_POST["Full_name"],$_POST["Email"],$_POST["pseudo"], $_POST["password"] )){
                    (new view("login"))->show(
                        [
                            "correct" => $is_corret[1],
                        ]
                    );
                }else{
                    (new view("signup"))->show(
                        [
                            "error" => $is_corret[1],
                        ]
                    );
                }
            }else{
                $errorArray = $is_corret;
                if( !($password_match) && isset($errorArray["password"]) && $errorArray["password"] != ""){
                    $errorArray["password"] = "Password is invalid ! or Password and Password Confirmation do not match !";
                    $errorArray["password_confirmation"] = "Password is invalid ! or Password and Password Confirmation do not match !";
                }
                $this->management_errors( $errorArray,$error);
            }
        }else{
            $error = "Complete the form !";
            $this->management_errors($errorArray,$error);
        }

    }

    // Gére le renvoie des informations en cas d'erreurs.
    public function management_errors( array $errorArray, string $error): void {
        $error = strlen($error) == 0 ? "" : $error;
        (new view("signup"))->show(
            [
                'error' => $error,
                'errorArray' => $errorArray
            ]
        );
    }
}
