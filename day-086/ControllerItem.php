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
    public function index(): void  {
    /*
    Vérifie si l'utilisateur est bien connecté,
    sinon le renvoie au login.
    */
        $this->check_user_session();
        $this->browse_items_view();
    }


    /* =========================================================
       Edit item
    ========================================================= */
    public function edit_item_view(): void{
        $this->check_user_session();
        $user = $_SESSION["user"];
        if(isset($_GET['param1'])){
            $item = Items::get_Item_instance_by_id($_GET['param1']);
            $page_title = "Edit item";
            $item_title = $item->get_title();
            $item_description = $item->get_description();
            $item_starting_bid = $item->get_starting_bid();
            $duration = $item->get_duration_days();
            $btn_back= isset($_GET['param2'])? Navigation::decode($_GET['param2']): "";
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
                    "starting_bid" => $item_starting_bid,
                    "btn_back" => $btn_back
                ]
            );
        }
    }
    /* =========================================================
       Edit item.
    ========================================================= */

    /* =========================================================
       Add item.
    ========================================================= */
    public function add_offer_view(): void{
        $this->check_user_session();
        $user = $_SESSION["user"];
        $page_title = "Add item";
        $active_bar = "Add_item";
        // On n'insere rien en BDD ici : le formulaire est vide,
        // la sauvegarde se fait uniquement dans add_item_from_form.
        (new View("Add_edit_item"))->show(
            [
                "page_title" => $page_title,
                "user"       => $user,
                "item"       => null,
                "press_save" => false,
                "bool_text_min_3_max_255" => true,
                "bool_buy_now_price_UP_starting_bid" => true,
                "title"       => "",
                "description" => "",
                "duration"    => "7",
                "starting_bid" => "",
                "active_bar" => $active_bar
            ]
        );
    }
    /* =========================================================
       Add item.
    ========================================================= */
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
        $btn_back = Navigation::decode($_POST['btn_back'])?? "";
        // Validation du titre
        $item = Items::get_Item_instance_by_id($item_id);
        //on cree un object items
        try {
            //pour pouvoir uttiliser validate contruct qui est methode 
            $item_sert_verification = new Items(
                $title,
                $description,
                new DateTime(AppTime::get_current_datetime()),
                (int)$duration,
                $item->get_owner_id(),
                $buy_now_price !== "" ? (float)$buy_now_price : null,
                $starting_bid !== "" ? (float)$starting_bid : null,
                $item_id,
                false//pour eviter de verifier la premier fois
            );
            //la onverifie les regle metier
            // evite 
            if ($buy_now_price === ''){
                $buy_now_price = 0;
            }
            if ($starting_bid === ''){
                $starting_bid = 0;
            }
            $item_sert_verification->validate_contruct($title,
            $description,
            new DateTime(AppTime::get_current_datetime()),
            (int)$duration,
            $item->get_owner_id(),
            $buy_now_price !== "" ? (float)$buy_now_price : null,
            $starting_bid !== "" ? (float)$starting_bid : null,
            false);
        
            // Si tout est OK → sauvegarde
           
            $item->item_setParams($title, $description, (int)$duration, (float)$starting_bid,$buy_now_price);
            $item->save();
            $this->redirect("item","open_item_view",$item_id,Navigation::encode($btn_back) );
        
        } catch (InvalidArgumentException $e) {
        
       // On récupère le message EXACT envoyé par validate_contruct
           $page_title = "Edit_item";
           $errors["global"] = $e->getMessage();

            (new View("Add_edit_item"))->show([
                "item" => $item,
                "page_title" => $page_title,
                "errors" => $errors,
                "press_save" => $this->press_save,
                "title" => $title,
                "description" => $description,
                "duration" => $duration,
                "starting_bid" => $starting_bid,
                "buy_now_price" => $buy_now_price
            ]);

        }

        
    
    }

    // Gére les données envoyées depuis la page add_offer.
    public function add_item_from_form(): void {
        $this->check_user_session();
        $user = $_SESSION["user"];
        $this->press_save = true;

        $title         = trim($_POST["title"]         ?? '');
        $description   = trim($_POST["description"]   ?? '');
        $duration      = $_POST["duration"]            ?? '';
        $starting_bid  = trim($_POST["starting_bid"]  ?? '');
        $buy_now_price = trim($_POST["buy_now_price"]  ?? '');
        $direct_price  = trim($_POST["direct_price"]   ?? '');
        $page_title    = "add_item";
        $user          = $_SESSION["user"];
        $errors        = [];
        // --- Validations métier ---
        if (strlen($title) < (int)Configuration::get("title_min_length") || strlen($title) > (int)Configuration::get("title_max_length")) {
            $errors["title"] = "Le titre doit avoir entre "
                . Configuration::get("title_min_length") . " et "
                . Configuration::get("title_max_length") . " caractères.";
        }
        if ($description !== '' && strlen($description) < (int)Configuration::get("description_item_min")) {
            $errors["description"] = "La description doit avoir au minimum "
                . Configuration::get("description_item_min") . " caractères.";
        }
        $duration_int = (int)$duration;
        if ($duration_int < (int)Configuration::get("duration_days_item_min") || $duration_int > (int)Configuration::get("duration_days_item_max")) {
            $errors["duration"] = "La durée doit être comprise entre "
                . Configuration::get("duration_days_item_min") . " et "
                . Configuration::get("duration_days_item_max") . " jours.";
        }

        // Déterminer les prix selon le type de vente
        $starting_bid_val  = $starting_bid  !== '' ? (float)$starting_bid  : null;
        $buy_now_price_val = $buy_now_price !== '' ? (float)$buy_now_price : null;
        // Vente directe : direct_price rempli et pas de starting_bid
        if ($direct_price !== '' && $starting_bid === '') {
            $buy_now_price_val = (float)$direct_price;
            $starting_bid_val  = null;
        }
        // Règle : buy_now_price > starting_bid si les deux présents
        if ($starting_bid_val !== null && $buy_now_price_val !== null
            && $buy_now_price_val <= $starting_bid_val) {
            $errors["buy_now_price"] = "Le prix d'achat immédiat doit être strictement supérieur à l'enchère de départ.";
        }
        // Au moins un prix requis
        if ($starting_bid_val === null && $buy_now_price_val === null) {
            $errors["sale_type"] = "Veuillez renseigner au moins un prix (enchère de départ ou vente directe).";
        }
        if (!empty($errors)) {
            (new View("Add_edit_item"))->show([
                "page_title"    => $page_title,
                "user"          => $user,
                "item"          => null,
                "press_save"    => $this->press_save,
                "errors"        => $errors,
                "title"         => $title,
                "description"   => $description,
                "duration"      => $duration,
                "starting_bid"  => $starting_bid,
                "buy_now_price" => $buy_now_price,
                "direct_price"  => $direct_price,
            ]);
            return;
        }

        // --- Sauvegarde via Items::persist() (INSERT propre, sans create_empty_item) ---
        $new_item_id = Items::create_new_item(
            $title,
            $description,
            $user->get_id(),
            new DateTime(AppTime::get_current_datetime()),
            $buy_now_price_val,
            $duration_int,
            $starting_bid_val
        );
        // Si l'item qu'on vient de créer existe alors je dois avoir son id.
        if (is_int( $new_item_id)) {
            // Car depuis il n'y a pas de page précédent add_item/add_offer.
            $btn_back[] = "item/my_items_view";
            $this->redirect("item", "open_item_view",$new_item_id,Navigation::encode($btn_back));
        }else if (!$new_item_id || gettype($new_item_id) !== "integer") {
            $errors["global"] = "Erreur lors de la sauvegarde. Le titre est peut-être déjà utilisé pour une de vos annonces.";
            (new View("Add_edit_item"))->show([
                "page_title"    => $page_title,
                "user"          => $user,
                "item"          => null,
                "press_save"    => $this->press_save,
                "errors"        => $errors,
                "title"         => $title,
                "description"   => $description,
                "duration"      => $duration,
                "starting_bid"  => $starting_bid,
                "buy_now_price" => $buy_now_price,
            ]);
            return;
        }
    }
    public function update_order() {
        $item_id = $_POST['item_id'];
        $order = $_POST['order'];
    
        Item_pictures::update_order($item_id, $order);
    
        echo json_encode(["status" => "ok"]);
    }
    public function get_images_service() {
        $item_id = $_GET['param1'] ?? null;
        
        if (!$item_id) {
            echo json_encode(['error' => 'Missing id']);
            return;
        }
        
        $item = Items::get_Item_instance_by_id($item_id);
        
        if (!$item) {
            echo json_encode(['error' => 'Item not found']);
            return;
        }
        
        $pictures = $item->get_picture_path_item();
        echo json_encode(['images' => $pictures]);
    }
    // À ajouter dans ControllerItem
    public function get_config_service(): void {
        header('Content-Type: application/json');
        echo json_encode([
            'title_min'       => (int)Configuration::get('title_min_length'),
            'title_max'       => (int)Configuration::get('title_max_length'),
            'description_min' => (int)Configuration::get('description_item_min'),
            'duration_min'    => (int)Configuration::get('duration_days_item_min'),
            'duration_max'    => (int)Configuration::get('duration_days_item_max'),
            'decimal_regex'   => Configuration::get('decimal_regles'),
        ]);
    }

    public function check_title_unique_service(): void {
        header('Content-Type: application/json');
        $this->check_user_session();
        $user    = $_SESSION['user'];
        $title   = trim($_POST['title']   ?? '');
        $item_id = (int)($_POST['item_id'] ?? 0); // 0 = mode ajout

        $stmt = Items::execute(
            "SELECT COUNT(*) FROM items WHERE owner = :owner AND title = :title AND id != :id",
            ['owner' => $user->get_id(), 'title' => $title, 'id' => $item_id]
        );
        $count = $stmt->fetchColumn();
        echo json_encode(['unique' => $count == 0]);
    }

    /* =========================================================
        Browse items.
    ========================================================= */
    // View qui amène à browse_items.
    public function browse_items_view(): void{
        $this->check_user_session();
        $user = $_SESSION["user"];
        $list_items_user_participing = array();
        $active_bar = "browse_items";
        // $word_search est déjà encodé dans la méthode item/get_card_search_service.
        $word_search = isset($_GET['param1']) ? $_GET['param1']: null ;
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
                "list_other_available_items" => $list_other_available_items,
                "active_bar" => $active_bar,
                "word_search" => $word_search
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
        // Ex : $btn_back = ["item/browse_items_view/"];
        $btn_back= isset($_GET['param2']) ? Navigation::decode($_GET['param2']) : "";
        (new view("open_item"))->show(
            [
                "user" => $user,
                "owner_user" => $owner_user,
                "item" =>  $item,
                "btn_back" => $btn_back
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
            $btn_back = isset($_POST["btn_back"]) ? Navigation::decode($_POST["btn_back"]): null;
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
                            "success" => "Your bid has been placed successfully!",
                            "btn_back" => $btn_back
                        ]
                    );
                }catch(InvalidArgumentException $e) {
                    // On attrape le message d'erreur défini dans le modèle Bids.
                    $error = $e->getMessage();
                    (new view("open_item"))->show([
                        "user" => $user,
                        "owner_user" => $owner_user,
                        "item" => $item,
                        "error" => $error,
                        "btn_back" => $btn_back
                    ]);
                }
            }else{
                (new view("open_item"))->show(
                    [
                        "user" => $user,
                        "owner_user" => $owner_user,
                        "item" => $item,
                        "btn_back" => $btn_back
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
        $active_bar = "my_items";
        // $word_search est déjà encodé dans la méthode item/get_card_search_service.
        $word_search = isset($_GET['param1']) ? $_GET['param1']: null ;
//        echo empty($activeItems);
//        echo $activeItems[0]["title"];
        (new View("my_items"))->show([
            "activeItems" => $activeItems,
            "closedUnsoldItems" => $closedUnsoldItems,
            "soldItems" => $soldItems,
            "user" => $user,
            "active_bar" => $active_bar,
            "word_search" => $word_search
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
        $btn_back = isset($_GET['param2']) ? Navigation::decode($_GET['param2']): "";
        (new View("Manage_images"))->show(
            [
                "tab_photo" => $array,
                "titre" => $instance_item->get_title(),
                "item_id" => $instance_item->get_id(),
                "item" => $instance_item,
                "btn_back" => $btn_back
            ]
        );

    }

    public function update_order_ajax() {
    if (isset($_POST['order'])) {

        $order = $_POST['order'];

        foreach ($order as $elem) {
            $id = $elem['id'];
            $position = $elem['position'];

            // Update en DB
            Item_pictures::update_order($id, $position);
        }

        echo json_encode(["status" => "ok"]);
    }
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
        $btn_back =  $_POST['btn_back']?? "";
        if (!$item || $item->get_owner_id() !== $user->get_id()) {
            //$this->redirect("ManageImages");
            // echo "pas meme id";
        }

        $image = $_FILES["images"];
        $item->validate_picture($image);
        // Retour vers la page manage images
        $this->manage_images_view($item_id,$btn_back);
    }

    public function move_up(): void{
        $this->check_user_session();

        $item_id = (int)$_POST['item_id'];
        $priority = (int)$_POST['priority'];
        $btn_back =$_POST['btn_back']?? "";
        Item_pictures::move_up($item_id, $priority);

        $this->manage_images_view($item_id,$btn_back);
    }

    public function move_down(): void
    {
        $this->check_user_session();
        $item_id = (int)$_POST['item_id'];
        $priority = (int)$_POST['priority'];
        Item_pictures::move_down($item_id, $priority);
        $btn_back = $_POST['btn_back']?? "";
        $this->manage_images_view($item_id,$btn_back);

    }
    /* =========================================================
     Manage images
  ========================================================= */

    /* =========================================================
      Delete confirmation
   ========================================================= */
    public function delete_confirm_view(): void{
        $this->check_user_session();
        $user = $_SESSION["user"];
        // $item->delete();
        // intval convertit un string en int.
        $item = isset($_GET['param1']) ? Items::get_Item_instance_by_id(intval($_GET['param1'])): null;
        $btn_back = isset($_GET['param2']) ? Navigation::decode($_GET['param2']): "";
        $owner = isset($item) ? User::get_user_by_id((intval($item->get_owner_id()))) :null;
        (new View("Delete_confirm"))->show(
            [
                "item" => $item,
                "owner" => $owner,
                "btn_back" => $btn_back
            ]
        );
    }

    public function delete_item():void{
        $this->check_user_session();
        $user = $_SESSION["user"];
        $item = isset($_GET['param1']) ? Items::get_Item_instance_by_id(intval($_GET['param1'])): null;
        $item->delete();
        // Ex de $link_array : item/my_items_view/.
        $link_array = isset($_GET['param2']) ? Navigation::decode($_GET['param2']) :null;
        $link = explode('/', $link_array);
        // Ex : $link[0] => "item" , $link[1] => "my_items_view".
        $this->redirect($link[0],$link[1]);


    }
    /* =========================================================
      Delete confirmation
   ========================================================= */

    /* =========================================================
       Service JS
    ========================================================= */
    // Donne tous les items de la BDD auxquels l'utilisateur courrant participent au format json + données supplémetaires.
    public function get_card_search_service(): void{
        $this->check_user_session();
        $user = $_SESSION["user"];
        // Le mots tapé par l'utilisateur sur la barre de recherche.
        $word = isset($_POST["word_search"]) ? $_POST["word_search"]: null;
        $mode = isset($_POST["mode"]) ? $_POST["mode"] : null;
        if(isset($word)){
            $list=null;
            if($mode === "participating"){
                $list = $user->get_user_items_participing_search_service($word);
            }elseif($mode === "available-items"){
                $list = Items::other_available_items_service($user->get_id(),$word);
            }elseif($mode === "active-items"){
                $list = Items::get_active_items_by_owner_service($user->get_id(),$word);
            }elseif($mode === "closed-unsold-items"){
                $list = Items::get_closed_unsold_items_by_owner_service($user->get_id(),$word);
            }elseif($mode === "sold-items"){
                $list =Items::get_sold_items_by_owner_service($user->get_id(),$word);
            }
            foreach ($list as &$item) {
                $instance = Items::get_Item_instance_by_id($item["id"]);
                $item["data"] = $instance->to_json();
                $item["user_current_id"] = $user->get_id();
                // Pour avoir le btn_back dans open item.
                if($mode === "participating" || $mode === "available-items"){
                    $btn_back[] = "item/browse_items_view/".Navigation::encode($word);
                    $item["btn_back"] = Navigation::encode($btn_back);
                }elseif($mode === "active-items" || $mode === "closed-unsold-items" || $mode === "closed-unsold-items" || $mode === "sold-items"){
                    $btn_back[] = "item/my_items_view/".Navigation::encode($word);;
                    $item["btn_back"] = Navigation::encode($btn_back);
                }
            }
            echo json_encode($list);
            exit; // Arrête l'exécution après le JSON.
        }
    }
    /* =========================================================
       Service JS
    ========================================================= */
}
