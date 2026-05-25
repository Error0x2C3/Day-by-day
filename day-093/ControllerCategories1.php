<?php
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once "utils/AppTime.php";
require_once "utils/Navigation.php";
require_once 'model/User.php';
require_once 'model/Categories.php';
require_once 'model/Item_categories.php';

class ControllerCategories extends Controller {
    public function index(): void{
        /*
        Vérifie si l'utilisateur est bien connecté,
        sinon le renvoie au login.
        */
        $this->check_user_session();
        $this->browse_items_view();
    }

    public function categories_view(){
        $this->check_user_session();
        $user = $_SESSION["user"];
        $active_bar = "categories";
        $list_categories = Categories::get_all();
        (new View("Categories"))->show(
            [
                "user" => $user,
                "active_bar" => $active_bar,
                "list_categories" => $list_categories
            ]
        );
    }


    public function manage_categorie(){
        $this->check_user_session();
        $user = $_SESSION["user"];
        $category = isset($_POST['category_id']) ? categories::get_categorie_by_id((int)$_POST['category_id']) : "";
        if($category instanceof categories && $user->get_role() === Role::Admin){
            if($_POST['action'] === "move_up"){
                $this->move_up($category);
            }else if($_POST['action'] === "move_down"){
                $this->move_down($category);
            }else if($_POST['action'] === "save"){
                $this->save($category);
            }else if($_POST['action'] === "delete"){
                $this->redirect("categories","delete_category_confirm_view",$category->get_id());
            }
        }
    }

    public function move_up(categories $category){
        $this->check_user_session();
        $prec_categorie =  categories::get_categorie_by_priority($category->get_priority()-1);
        if($category->get_priority() > 1){
            if( $prec_categorie instanceof categories){
                $this->toggle_priority($category,$prec_categorie);
                $this->redirect("categories","categories_view");
            }else{
                $category->set_priority($category->get_priority()-1);
                $category->save();
                $this->redirect("categories","categories_view");
            }
        }
    }


    public function move_down(categories $category){
        $this->check_user_session();
        $list_categorie = categories::get_all();
        $next_categorie =  categories::get_categorie_by_priority($category->get_priority()+1);
        if($category->get_priority() < count($list_categorie)){
            if( $next_categorie instanceof categories){
                $this->toggle_priority($category,$next_categorie);
                $this->redirect("categories","categories_view");
            }else{
                $category->set_priority($category->get_priority()+1);
                $category->save();
                $this->redirect("categories","categories_view");
            }
        }
    }

    // S'occupe d'interchanger les priorités de deux catégories.
    public function toggle_priority($category,$other_categorie){
        $this->check_user_session();
        $tmp = $category->get_priority();
        $tmp_other = $other_categorie->get_priority();

        $other_categorie->set_priority(0);
        $other_categorie->save();
        $category->set_priority($tmp_other);
        $category->save();
        $other_categorie->set_priority($tmp);
        $other_categorie->save();
    }

    // Enregistre les modification faîtes sur une catégorie.
    public function save(categories $_category){
        $this->check_user_session();

    }

    // Redirige vers la page de suppression d'une catégorie.
    public function delete_category_confirm_view(){
        $this->check_user_session();
        $user = $_SESSION["user"];
        $category = isset($_GET['param1']) ? categories::get_categorie_by_id((int)$_GET['param1']): "";
        if($category instanceof categories){
            if(count(item_categories::get_items_category_by_category($category->get_id())) == 0 ){
                (new View("delete_category_confirm"))->show(
                    [
                        "user" => $user,
                        "category" => $category
                    ]
                );
            }
            $this->redirect("categories","categories_view");
        }
    }
    public function delete(categories $category){
        $this->check_user_session();
        $category = isset($_GET['param1'])? categories::get_categorie_by_id($_GET['param1']) : $category;
        if(count(item_categories::get_items_category_by_category($category->get_id())) == 0 ){
            if(categories::delete($category)){
                $this->redirect("categories","categories_view");
            }

        }
        $this->redirect("categories","categories_view");
    }
}