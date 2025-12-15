<?php

require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'model/Items.php';


class ControllerAddEditItem extends Controller
{

    const UPLOAD_ERR_OK = 0;
    private bool $bool_text_min_3_max_255 = false;
    private bool $press_save = false;
    private bool $bool_buy_now_price_UP_starting_bid = false;

    //page d'accueil.
    public function index(): void
    {
        $this->profile();
    }

    public function profile(): void
    {
        (new View("Add_edit_item"))->show(["press_save"=>$this->press_save,"bool_text_min_3_max_255"=>$this->bool_text_min_3_max_255,"bool_buy_now_price_UP_starting_bid"=>$this->bool_buy_now_price_UP_starting_bid ]);
    }


    public function save()
    {
    $this->press_save = true;

    // Récupération des valeurs POST
    $title = $_POST["title"] ?? '';
    $buy_now_price = $_POST["buy_now_price"] ?? '';
    $description = $_POST["description"] ?? '';
    $duration = $_POST["duration"] ?? '';
    $starting_bid = $_POST["starting_bid"] ?? '';
    $direct_price = $_POST["direct_price"] ?? '';

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
        && $description !== '' && $duration !== '' && $starting_bid !== '') {

        $item = new Items($title, $description, (int)$duration, (float)$starting_bid);
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

}
