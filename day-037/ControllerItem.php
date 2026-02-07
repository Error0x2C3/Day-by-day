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
require_once 'controller/ControllerHistoryNav.php';
class ControllerItem extends Controller {

    private bool $bool_text_min_3_max_255 = false;//a faire 
    private bool $press_save = false;
    private bool $bool_buy_now_price_UP_starting_bid = false;

    //page d'accueil.
    public function index(): void{
    /*
    Vérifie si l'utilisateur est bien connecté,
    sinon le renvoie au login.
    */
        $this->check_user_session();
        $this->browse_items_view();
    }

    public function add_edit_item_view(): void{
        $this->check_user_session();
        $user = $_SESSION["user"];
        $links = ControllerHistoryNav::get_tab_links();
        $item = isset($_GET['param1']) ? Items::get_Item_instance_by_id($_GET['param1']) : null;
        (new View("Add_edit_item"))->show(
            [
                "user" => $user,
                "item" => $item,
                "links" => $links,
                "press_save"=>$this->press_save,
                "bool_text_min_3_max_255"=>$this->bool_text_min_3_max_255,
                "bool_buy_now_price_UP_starting_bid"=>$this->bool_buy_now_price_UP_starting_bid
            ]
        );
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
            $createdAtString = new DateTime('Y-m-d H:i:s');
            $item = new Items($title, $description,$createdAtString, (int)$duration,1, (float)$starting_bid,$buy_now_price);
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


    /* =========================================================
        Browse items.
    ========================================================= */
    // View qui amène à browse_items.
    public function browse_items_view(): void{
        $this->check_user_session();
        $user = $_SESSION["user"];
        $list_items_user_participing = $user->get_user_items_participing();
        $list_other_available_items = Items::other_available_items($user->get_id());
        (new view("browse_items"))->show(
            [
                "user" => $user,
                "list_items_user_participing" => $list_items_user_participing,
                "list_other_available_items" => $list_other_available_items
            ]
        );
    }
    /* =========================================================
        Browse items.
    ========================================================= */

    /* =========================================================
       Open item.
   ========================================================= */

    public function open_item_view():void {
        $this->check_user_session();
        $user = $_SESSION["user"];
        $item = isset($_GET['param1']) ? Items::get_Item_instance_by_id($_GET['param1']) : null;
        $list_items_user_participing = $user->get_user_items_participing();
        $list_other_available_items = Items::other_available_items($user->get_id());
        (new view("open_item"))->show(
            [
                "user" => $user,
                "list_items_user_participing" => $list_items_user_participing,
                "list_other_available_items" => $list_other_available_items,
                "item" =>  $item
            ]
        );
    }
    /* =========================================================
        Open item.
     ========================================================= */
}
