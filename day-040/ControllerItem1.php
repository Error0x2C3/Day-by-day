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


    /* =========================================================
       Add edit item / edit item.
    ========================================================= */
    public function manage_add_edit_item_view(): void{
        $this->check_user_session();
        $user = $_SESSION["user"];
        if(isset($_GET['param1'])){
    
            $item = Items::get_Item_instance_by_id($_GET['param1']) ;
            $page_title = "Edit_item";
            $item_title = $item->get_title();
            $item_description = $item->get_description();
            $item_starting_bid = $item->get_starting_bid();
            $duration = $item->get_duration_days();
        }
        (new View("Add_edit_item"))->show(
            [
                "page_title"=>$page_title,
                "user" => $user,
                "item" => $item,
                "press_save"=>$this->press_save,
                "bool_text_min_3_max_255"=>$this->bool_text_min_3_max_255,
                "bool_buy_now_price_UP_starting_bid"=>$this->bool_buy_now_price_UP_starting_bid,
                "title" =>$item_title,
                "description" => $item_description,
                "duration" => $duration,
                "starting_bid" => $item_starting_bid
            ]
        );
    }
    /* =========================================================
       Add edit item.
    ========================================================= */

    public function add_offer(): void{
        $this->check_user_session();
        $user = $_SESSION["user"];
        $page_title = "add_item";
        $item_title = "";
        $item_description = "";
        $item_starting_bid = "";
        $duration = "";
        $item = Items::create_empty_item($_SESSION["user"]->get_id());
        (new View("Add_edit_item"))->show(
            [
                "page_title"=>$page_title,
                "user" => $user,
                "item" => $item,
                "press_save"=>$this->press_save,
                "bool_text_min_3_max_255"=>$this->bool_text_min_3_max_255,
                "bool_buy_now_price_UP_starting_bid"=>$this->bool_buy_now_price_UP_starting_bid,
                "title" =>$item_title,
                "description" => $item_description,
                "duration" => $duration,
                "starting_bid" => $item_starting_bid
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
        $item_id =  $_POST["item_id"] ?? '';


        // Validation du titre
        if (strlen($title) >= Configuration::get("title_min_length") && strlen($title) <= Configuration::get("title_max_length")) {
            $this->bool_text_min_3_max_255 = true;
        } else {
            $this->bool_text_min_3_max_255 = false;
        }

        // Validation du buy_now_price seulement si il est rempli
        if ($buy_now_price !== '' && $starting_bid !== '') {
            if ((float)$buy_now_price > (float)$starting_bid) {
                $this->bool_buy_now_price_UP_starting_bid = true;
            } else {
                $this->bool_buy_now_price_UP_starting_bid = false;
            }
        } else {
            // Si aucun prix instantané, pas d'erreur
            $this->bool_buy_now_price_UP_starting_bid = true;
        }

        // Vérification si tous les champs obligatoires sont remplis
        if ($this->bool_text_min_3_max_255 && $this->bool_buy_now_price_UP_starting_bid && ! new Items($title, $description, new DateTime(AppTime::get_current_datetime()),(int)$duration,$item_id, (float)$starting_bid,$buy_now_price,$item_id)
            && $description !== '' && $duration !== '' ) {
            $createdAtString = new DateTime(AppTime::get_current_datetime());
            $item = Items::get_Item_instance_by_id($item_id);
            if ($buy_now_price === ''){
                $buy_now_price = 0;
            }
            if ($starting_bid === ''){
                $starting_bid = 0;
            }
            $item->item_setParams($title, $description, (int)$duration,$item_id, (float)$starting_bid,$buy_now_price);
            $item->save2($item_id, $createdAtString);
            $this->redirect("item","open_item_view",$item_id);

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
    public function add_item_from_form(){
        $this->check_user_session();
        $this->press_save = true;

        // Récupération des valeurs POST
        $title = $_POST["title"] ?? '';
        $buy_now_price = $_POST["buy_now_price"] ?? '';
        $description = $_POST["description"] ?? '';
        $duration = $_POST["duration"] ?? '';
        $starting_bid = $_POST["starting_bid"] ?? '';
        $direct_price = $_POST["direct_price"] ?? '';
        $item_id =  $_POST["item_id"] ?? '';
        $page_title = "add_item";

        $user = $_SESSION["user"];

        // Validation du titre
        if (strlen($title) >= Configuration::get("title_min_length") && strlen($title) <= Configuration::get("title_max_length")) {
            $this->bool_text_min_3_max_255 = true;
        } else {
            $this->bool_text_min_3_max_255 = false;
        }

        // Validation du buy_now_price seulement si il est rempli
        if ($buy_now_price !== '' && $starting_bid !== '') {
            if ((float)$buy_now_price > (float)$starting_bid) {
                $this->bool_buy_now_price_UP_starting_bid = true;
            } else {
                $this->bool_buy_now_price_UP_starting_bid = false;
            }
        } else {
            // Si aucun prix instantané, pas d'erreur
            $this->bool_buy_now_price_UP_starting_bid = true;
        }
        // Vérification si tous les champs obligatoires sont remplis
        if ($this->bool_text_min_3_max_255 && $this->bool_buy_now_price_UP_starting_bid
            && $description !== '' && $duration !== '') {
            
            $createdAtString = new DateTime(AppTime::get_current_datetime());
            if ($buy_now_price === ''){
                $buy_now_price = 0;
            }
            if ($starting_bid === ''){
                $starting_bid = 0;
            }
            $item = new Items($title, $description,$createdAtString, (int)$duration,$_SESSION["user"]->get_id(), (float)$starting_bid,$buy_now_price,$item_id);
            $item->save();
            $this->redirect("item","open_item_view",$item_id);
        } else {
            // Réaffichage du formulaire avec valeurs préremplies et erreurs
            (new View("Add_edit_item"))->show(
                [
                    "page_title"=>$page_title,
                    "user" => $user,
                    "item" => Items::get_Item_instance_by_id($item_id),
                    "press_save"=>$this->press_save,
                    "bool_text_min_3_max_255"=>$this->bool_text_min_3_max_255,
                    "bool_buy_now_price_UP_starting_bid"=>$this->bool_buy_now_price_UP_starting_bid,
                    "title" =>$title,
                    "description" => $description,
                    "duration" => $duration,
                    "starting_bid" => $starting_bid
                ]
            );
        }
    }


    /* =========================================================
        Browse items.
    ========================================================= */
    // View qui amène à browse_items.
    public function browse_items_view(): void{
        $this->check_user_session();
        $user = $_SESSION["user"];
        $list_items_user_participing = array();
        foreach($user->get_user_items_participing() as $item){
            // On refuse ceux d'ont le temps est écoulé
            // et ceux qui devraient fermés pour x raison voir règles métier.
            if(!$item->should_close()){$list_items_user_participing[]=$item;}
        }
        $list_other_available_items = array();
        foreach(Items::other_available_items($user->get_id()) as $item){
            if(!$item->should_close()){$list_other_available_items[]=$item;}
        }
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
        $owner_user = User::get_user_by_id($item->get_owner_id()) instanceof User ?  User::get_user_by_id($item->get_owner_id()) : null ;
        (new view("open_item"))->show(
            [
                "user" => $user,
                "owner_user" => $owner_user,
                "item" =>  $item
            ]
        );
    }

    public function additional_images_manage_for_open_item_view():void{
        $this->check_user_session();
        $user = $_SESSION["user"];
        $item = isset($_GET['param1']) ? Items::get_Item_instance_by_id($_GET['param1']) : null;
        $owner_user = User::get_user_by_id($item->get_owner_id());
        // for galery images.
        $image_index = isset($_GET['param2']) ? $_GET['param2'] : null ;
        $user = $_SESSION["user"];
        (new view("open_item"))->show(
            [
                "user" => $user,
                "owner_user" => $owner_user,
                "item" =>  $item,
                "image_index" =>$image_index
            ]
        );
    }
    public function buy_bid():void{
        $this->check_user_session();
        $user = $_SESSION["user"];
        $item = null;
        if(isset($_GET['param1'])){
            $item = Items::get_Item_instance_by_id((int)$_GET['param1']);
            $amount = isset($_POST['bid_amount']) ? $_POST['bid_amount']:null;
            $action =  isset($_POST['action']) ? $_POST['action']:null; // 'bid' ou 'buy_now'
            $owner_user = User::get_user_by_id($item->get_owner_id());
            $buy_now = null ;
            if($action =='buy_now'){
                $amount = $item->get_buy_now_price();
            }
            if($item->get_owner_id() != $user->get_id()){
                try {
                    $bid = new Bids($user->get_id(), $item->get_id(),new DateTime(AppTime::get_current_datetime()),$amount,true);
                    Bids::persist($user->get_id(), $item->get_id(), new DateTime(AppTime::get_current_datetime()), $amount);
                    // Pour récupérer les infos mises à jour dans la BDD.
                    $item = Items::get_Item_instance_by_id((int)$_GET['param1']);
                    (new view("open_item"))->show(
                        [
                            "user" => $user,
                            "owner_user" => $owner_user,
                            "item" => $item,
                            "achat_" => "test",
                            "success" => "Your bid has been placed successfully!"
                        ]
                    );
                }catch(InvalidArgumentException $e) {
                    // On attrape le message d'erreur défini dans le modèle Bids.
                    $error = $e->getMessage();
                    (new view("open_item"))->show([
                        "user" => $user,
                        "owner_user" => $owner_user,
                        "item" => $item,
                        "error" => $error
                    ]);
                }

            }else{
                (new view("open_item"))->show(
                    [
                        "user" => $user,
                        "owner_user" => $owner_user,
                        "item" => $item,
                    ]
                );
            }
        }
    }
    /* =========================================================
        Open item.
     ========================================================= */

    /* =========================================================
        My items.
     ========================================================= */
    public function my_items_view() : void {
        $this->check_user_session();
        $user = $_SESSION["user"];
        $activeItems = Items::get_active_items_by_owner($user->get_id());
        $closedUnsoldItems = Items::get_closed_unsold_items_by_owner($user->get_id());
        $soldItems = Items::get_sold_items_by_owner($user->get_id());
//        echo empty($activeItems);
//        echo $activeItems[0]["title"];
        (new View("my_items"))->show([
            "activeItems" => $activeItems,
            "closedUnsoldItems" => $closedUnsoldItems,
            "soldItems" => $soldItems
        ]);
    }
    /* =========================================================
       My items.
    ========================================================= */

    /* =========================================================
      Manage images
   ========================================================= */

    public function manage_images_view($item_id_param = false): void{
        $this->check_user_session();
        $user = $_SESSION["user"];
        $item_id = null;
        if(isset($_GET['param1'])){
            $item_id = $_GET['param1'];
        }elseif($item_id_param !=false){
            $item_id = $item_id_param;
        }
        //$item_id = isset($_GET['param1']) ? $_GET['param1'] : null;
        $this->check_user_session();
        $instance_item = Items::get_Item_instance_by_id($item_id);
        $array = Items::get_user_item_picture_path_and_priority($item_id);
        (new View("Manage_images"))->show(
            [
                "tab_photo" => $array,
                "titre" => $instance_item->get_title(),
                "item_id" => $instance_item->get_id(),
                "item" => $instance_item
            ]
        );

    }

    public function upload_images(): void{
        $this->check_user_session();
        $user = $_SESSION["user"];

        if (!isset($_POST['item_id']) || !isset($_FILES['images'])) {
            $this->redirect("error");
        }

        $item_id = (int)$_POST['item_id'];
        // echo $item_id;
        // Récupération de l’item
        $item = Items::get_Item_instance_by_id($item_id);
        if (!$item || $item->get_owner_id() !== $user->get_id()) {
            //$this->redirect("ManageImages");
            echo "pas meme id";
        }

        $image = $_FILES["images"];
        $item->validate_picture($image);
        // Retour vers la page manage images
        $this->manage_images_view($item_id);
    }

    public function move_up(): void{
        $this->check_user_session();

        $item_id = (int)$_POST['item_id'];
        $priority = (int)$_POST['priority'];

        Item_pictures::move_up($item_id, $priority);

        $this->manage_images_view($item_id);
    }

    public function move_down(): void
    {
        $this->check_user_session();
        $item_id = (int)$_POST['item_id'];
        $priority = (int)$_POST['priority'];
        Item_pictures::move_down($item_id, $priority);
        $this->manage_images_view($item_id);

    }
    /* =========================================================
     Manage images
  ========================================================= */
  public function delete(){
    $this->check_user_session();
    $item_id = (int)$_POST['item_id'];
    $item = Items::get_Item_instance_by_id($item_id);
    $item->delete();
    $this->redirect("item","browse_items_view");
  }
  
}
