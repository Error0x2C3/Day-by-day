<?php
require_once "framework/Controller.php";
require_once "utils/AppTime.php";
require_once "utils/Navigation.php";
require_once 'model/User.php';
require_once 'model/Items.php';
class ControllerProfile extends Controller {
    public function index(): void {
        $this->check_user_session();
        $this->profile_view();
    }
    public function profile_view():void{
        $this->check_user_session();
        $user = $_SESSION["user"];
        $active_bar = "profile";
        (new view("profile"))->show(
            [
                "user" => $user,
                "active_bar" => $active_bar
            ]

        );
    }

    /* =========================================================
        Edit profil
    ========================================================= */
    public function profil_edit_view():void{
        $this->check_user_session();
        $user = $_SESSION["user"];
        $btn_back = isset($_GET['param1']) ? Navigation::desanitize($_GET['param1']) :"";
        (new view("Edit_profile"))->show(
            [
                "user"=> $user,
                "btn_back" => $btn_back
            ]

        );
    }
    public function logout_user(): void{
        $this->check_user_session();
        $user = $_SESSION["user"];
        $this->logout();
    }

    public function save_edit_profil(): void{
        $this->check_user_session();
        $user = $_SESSION["user"];

        // Vérifier POST
        if (
            !isset($_POST['full_name']) ||
            !isset($_POST['username']) ||
            !isset($_POST['email']) ||
            !isset($_POST['iban'])
        ) {
            $this->profil_edit_view();
        }

        $full_name = trim($_POST['full_name']);
        $username  = trim($_POST['username']);
        $email     = trim($_POST['email']);
        $iban      = isset($_POST['iban']) ? trim($_POST['iban']) : null;

        // Mise à jour via le modèle
        $user->update_profile(
            full_name: $full_name,
            username: $username,
            email: $email,
            iban: $iban
        );

        $this->profile_view();
    }
    /* =========================================================
        Edit profil
    ========================================================= */

    /* =========================================================
        Change password
    ========================================================= */
    public function profile_change_password_view() : void {
        $this->check_user_session();
        $user = $_SESSION["user"];
        $btn_back = isset($_GET['param1']) ? Navigation::desanitize($_GET['param1']):"" ;
        (new View("change_password"))->show(
            [
                'user' => $user,
                'btn_back' => $btn_back
            ]
        );

    }
    public function upload_password () : void {
        $this->check_user_session();
        $user = $_SESSION["user"];
 
        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['new_password']     ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
 
        $errors = [];
 
        if (!password_verify($current, $user->get_password())) {
            $errors['current_password'] = "Mot de passe actuel incorrect.";
        }
 
        if ($new !== $confirm) {
            $errors['confirm_password'] = "Les nouveaux mots de passe ne correspondent pas.";
        }
 
        if (!User::validate_password($new)) {
            $errors['new_password'] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.";
        }
 
        if (!empty($errors)) {
            (new View("change_password"))->show([
                "errors" => $errors
            ]);
            return;
        }

        $user->save();
        $this->redirect("profile", "profile_view");
    }
    /* =========================================================
        Change password
    ========================================================= */

    /* =========================================================
        Profile picture
    ========================================================= */
    public function  manage_profile_picture_view():void{
        $this->check_user_session();
        $user = $this->get_user_or_redirect();
        $btn_back = isset($_GET['param1']) ? Navigation::desanitize($_GET['param1']):"" ;
        (new View("Manage_profile_Picture"))->show(
            [
                "user" => $user,
                "btn_back" => $btn_back
//                "name"=>$user->get_full_Name(),
//                "pseudo"=>$user->get_pseudo(),
//                "path_photo"=>$user->get_picture_path()
            ]
        );
    }
    public function delete_profile_picture(){
        $this->check_user_session();
        $user = $_SESSION["user"];
        $user->delete_user_profile_picture();
        $this->redirect("profile", "profile_view");
    }
    public function upload_profile_picture(){
        $this->check_user_session();
        $user = $_SESSION["user"];
        if (isset($_FILES["profile_picture"])){

            $profile_picture = $_FILES["profile_picture"];
            $user->validate_picture($profile_picture);
        }
        $this->redirect("profile", "profile_view");
    }
    /* =========================================================
        Profile picture
    ========================================================= */

    /* =========================================================
        Sales
    ========================================================= */
    public function sales_view():void{
        $this->check_user_session();
        $user = $this->get_user_or_redirect();
        $btn_back = isset($_GET['param1']) ? Navigation::desanitize($_GET['param1']) : "";
        $list_items = Items::get_sold_items_by_owner($user->get_id());
        (new View("sales"))->show(
            [
                "user" => $user,
                "list_items" => $list_items,
                "btn_back" => $btn_back
            ]
        );
    }
    /* =========================================================
        Sales
    ========================================================= */
}