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
        // Liste des item vendu qui appartiennent à un utilisateur.
        $list_items = Items::get_sold_items_by_owner($user->get_id());
        $list_items_owners_id = array();
        foreach($list_items as $item){$list_items_owners_id[]= $item->get_owner_id();}
        $word_sale = count($list_items)> 1 ? count($list_items)." sales" : "1 sale" ;
        $sum_revenue = 0;
        $list_recurring_winning = array();
        foreach($list_items as $item){
            // Pour là somme des revenues perçus par les items vendus.
            $sum_revenue += $item->get_max_bid();
//            // Pour avoir le gagnant le plus récurrent.
//            if(in_array($item->get_owner_id(),$list_items_owners_id)){
//                $list_recurring_winning[$item->get_owner_id()] =+1;
//            }else{$list_recurring_winning[$item->get_owner_id()] = 0;}
        }
        $average_ticket = count($list_items)> 0 ? $sum_revenue/count($list_items) : 0;
        $recurring_winning =  User::get_user_by_id( Items::get_recurring_winning_item_owned_by($user->get_id()))->get_pseudo();
            (new View("sales"))->show(
            [
                "user" => $user,
                "list_items" => $list_items,
                "sum_revenue" => $sum_revenue,
                "word_sale" => $word_sale,
                "average_ticket" =>  $average_ticket,
                "recurring_winning" => $recurring_winning,
                "btn_back" => $btn_back
            ]
        );
    }
    /* =========================================================
        Sales
    ========================================================= */

    /* =========================================================
        Purchases
    ========================================================= */
    public function purchases_view():void{
        $this->check_user_session();
        $user = $this->get_user_or_redirect();
        $btn_back = isset($_GET['param1']) ? Navigation::desanitize($_GET['param1']) : "";
        $list_items_win = Items::get_items_win_by($user->get_id());
        $word_sale = count($list_items_win)> 1 ? count($list_items_win)." purchases" : "1 purchase";
        $sum_spent = 0;
        foreach($list_items_win as $item){
            $sum_spent += $item->get_max_bid();
        }
        $average_ticket = $sum_spent/count($list_items_win);
        (new View("purchases"))->show(
            [
                "user" => $user,
                "list_items_win" => $list_items_win,
                "word_sale" => $word_sale,
                "sum_spent" => $sum_spent,
                "average_ticket" => $average_ticket,
                "btn_back" => $btn_back
            ]
        );
    }
    /* =========================================================
        Purchases
    ========================================================= */
}