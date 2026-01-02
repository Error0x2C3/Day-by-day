<?php

require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'model/User.php';
require_once "utils/AppTime.php";
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
        $answerArray = Array();
        $error = "";
        if (isset($_POST["email"] ) && isset($_POST["full_name"] )&& isset($_POST["pseudo"] )&& isset($_POST["password"] )&& isset($_POST["confirm_password"])){
            $answerArray = array(
                "email" => $_POST["email"],
                "full_name" => $_POST["full_name"],
                "pseudo" => $_POST["pseudo"],
                "password" => $_POST["password"],
                "confirm_password" => $_POST["confirm_password"]
            );
            $is_corret = User::validate($_POST["full_name"],$_POST["email"] ,$_POST["pseudo"],$_POST["password"]);
            $password_match = $_POST["password"] === $_POST["confirm_password"];
            if( empty($is_corret)){
                if(User::persist($_POST["full_name"],$_POST["email"],$_POST["pseudo"], $_POST["password"] )){
                    (new view("login"))->show(
                        [
                            "correct" => $is_corret,
                        ]
                    );
                }else{
                    $this->management_errors( $errorArray,$error, $answerArray);
                }
            }else{
                $errorArray = $is_corret;
                if( !($password_match) && isset($errorArray["password"]) && $errorArray["password"] != ""){
                    $errorArray["password"] = "Password is invalid ! or Password and Password Confirmation do not match !";
                    $errorArray["password_confirmation"] = "Password is invalid ! or Password and Password Confirmation do not match !";
                }
                $this->management_errors( $errorArray,$error,$answerArray);
            }
        }else{
            $error = "Complete the form !";
            $this->management_errors($errorArray,$error,$answerArray);
        }

    }

    // Gére le renvoie des informations en cas d'erreurs.
    public function management_errors( array $errorArray, string $error, array  $answerArray): void {
        $error = strlen($error) == 0 ? "" : $error;
        $answerArray = empty($answerArray) ? "" : $answerArray;
        (new view("signup"))->show(
            [
                'error' => $error,
                'errorArray' => $errorArray,
                'answerArray' => $answerArray
            ]
        );
    }
}
