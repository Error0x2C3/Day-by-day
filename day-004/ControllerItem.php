<?php
/*
Utilisé pour la view_brows_items,
view_add_edit_item.
 */
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'model/Items.php';
require_once "utils/AppTime.php";
require_once "utils/Navigation.php";
require_once 'model/User.php';
class ControllerItem extends Controller {

    const UPLOAD_ERR_OK = 0;
    private bool $bool_text_min_3_max_255 = false;
    private bool $press_save = false;
    private bool $bool_buy_now_price_UP_starting_bid = false;

    //page d'accueil.
    public function index(): void{
        $this->check_user_session();
    }

    // Vérifie si l'utilisateur est bien connecté.
    public function check_user_session(): void{
        // $_SESSION['user'] est crée dans :
        //  ControllerLogin/management_login_connect_as et dans
        //  ControllerLogin/login_check() .
        if( !(isset($_SESSION['user'])) || !($this->get_user_or_redirect() instanceof User)){
            $this->redirect("login");
        }
    }
    public function add_edit_item_view(): void{
        $this->check_user_session();
        (new View("Add_edit_item"))->show(["press_save"=>$this->press_save,"bool_text_min_3_max_255"=>$this->bool_text_min_3_max_255,"bool_buy_now_price_UP_starting_bid"=>$this->bool_buy_now_price_UP_starting_bid ]);
    }

    public function logout_test(): void{
        $this->logout();
    }
    public function save_item_from_form(){
        $this->check_user_session();
        $this->press_save = true;

        // Récupération des valeurs POST
        $title = $_POST["title"] ?? '';
        $buy_now_price = $_POST["buy_now_price"] ?? '';
        $description = $_POST["description"] ?? '';
        $duration = $_POST["duration"] ?? '';
        $starting_bid = $_POST["starting_bid"] ?? '';
        $direct_price = $_POST["direct_price"] ?? '';





        // Vérification si tous les champs obligatoires sont remplis
        if ($this->bool_text_min_3_max_255 && $this->bool_buy_now_price_UP_starting_bid
            && $description !== '' && $duration !== '' && $starting_bid !== '') {

            $item = new Items($title, $description, (int)$duration, (float)$starting_bid,$buy_now_price);
            $createdAtString = date('Y-m-d H:i:s');
            $item->save(1, $createdAtString);

        } else {
            // Réaffichage du formulaire avec valeurs préremplies et erreurs
            (new View("Add_edit_item"))->show([
                "press_save" => $this->press_save,
                "bool_text_min_3_max_255" => $this->bool_text_min_3_max_255,
                "bool_buy_now_price_UP_starting_bid" => $this->bool_buy_now_price_UP_starting_bid,
                "title" => $title,
                "buy_now_price" => $buy_now_price,
                "description" => $description,
                "duration" => $duration,
                "starting_bid" => $starting_bid,
                "direct_price" => $direct_price
            ]);
        }
    }

    public function browse_items_view(): void{
        $this->check_user_session();
        (new view("browse_items"))->show();
    }
}
