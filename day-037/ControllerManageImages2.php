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

class ControllerManageImages extends Controller {

const UPLOAD_ERR_OK = 0;

private Items $model_item ;
//page d'accueil.
public function index(): void{
    /*
    Vérifie si l'utilisateur est bien connecté,
    sinon le renvoie au login.
    */
    $this->check_user_session();
    $this->manage_images(16);//exemple

}

public function manage_images($item_id=false): void{
//    if(!$item_id){
//        $item_id = (int)$_GET[0];
//    }
    $item_id = isset($_GET['param1']) ? $_GET['param1'] : null;
    $this->check_user_session();
    $instance_item = Items::get_Item_instance_by_id($item_id);
    $array = Items::get_user_item_picture_path_and_priority($item_id);
    (new View("Manage_images"))->show(
        ["tab_photo"=>$array,
            "titre"=>$instance_item->get_title(),
            "item_id"=>$instance_item->get_id()]
    );

}

public function upload_images(): void
{
    $this->check_user_session();
    $user = $this->get_user_or_redirect();

    if (!isset($_POST['item_id']) || !isset($_FILES['images'])) {
        $this->redirect("error");
    }

    $item_id = (int)$_POST['item_id'];
    echo $item_id;
    // Récupération de l’item
    $item = Items::get_Item_instance_by_id($item_id);
    if (!$item || $item->get_owner_id() !== $user->get_id()) {
        //$this->redirect("ManageImages");
        echo "pas meme id";
    }

    $image = $_FILES["images"];
    $item->validate_picture($image);
    // Retour vers la page manage images
   $this->manage_images($item_id);
}

public function move_up(): void
{
    $this->check_user_session();

    $item_id = (int)$_POST['item_id'];
    $priority = (int)$_POST['priority'];

    Item_pictures::move_up($item_id, $priority);

    $this->manage_images($item_id);
}

public function move_down(): void
{
    $this->check_user_session();

   $item_id = (int)$_POST['item_id'];
    $priority = (int)$_POST['priority'];

    Item_pictures::move_down($item_id, $priority);

$this->manage_images($item_id);}

}