<?php
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once "utils/AppTime.php";
require_once 'model/User.php';
class ControllerHistoryNav extends Controller {

    public function index(): void
    {
        
    }

    /*
    Le bouton de la barre de titre de l'application, lorsqu'il est affiché,
    doit permettre de revenir à la page précédente, c'est-à-dire la page à partir
    de laquelle on a navigué vers la page actuelle.
    Les éventuels paramètres d'état ou de navigation doivent être conservés.
    Par exemple, si on a ouvert une annonce (voir open_item) à partir
    de la page browse_items, le bouton doit permettre de revenir à cette page browse_items.
    En revanche, si on a ouvert une annonce à partir de la liste de ses achats (purchases),
    le même bouton doit permettre de revenir à la liste de ses achats.
    Ce retour en arrière doit être géré explicitement par l'application,
    et non par la fonctionnalité d'historique du navigateur.

    Principe pour gérer la barre de retour :
    - Un tableau qui stocke les liens des pages précédentes.
    - Un pointeur initialisé a -1 et
        * qui à chaque fois qu'on passe
          d'une page à une autre =>
          pointeur +=1 && le tableau stocke le lien de la page d'ou l'on vient.
        * lorsqu'on revient à la page précédente =>
          pointeur-=1 && on supprime le lien de cette page du tableau.

    EXEMPLE :
    $tab_links = [];
    $pointeur = -1;

    Cas 1 :
    On va de Brows_item à Open_item.
    $tab_links []= "item/browse_items_view";
    A)                      B)
    $tab_links = []         "item/browse_items_view"
    $pointeur = -1          0

    Cas 2 :
    De Open_item à Browse_item.
    array_pop(self::$tab_links);
    Elle supprime physiquement le dernier élément du tableau.
    Elle retourne la valeur de cet élément qui vient d'être supprimé ou null si le tableau est vide.
    A)                                          B)
    $tab_links = ["item/browse_items_view"]     $tab_links=[]
    $pointeur = 0           $pointeur = -1
     */
    private static $tab_links = array();
    private static int $pointeur = -1;
    public function forward($link): void{
        self::$tab_links [] = $link;
        self::$pointeur ++;
        $this->redirect($link);
    }
    public function back():string{
        self::$pointeur --;
        return array_pop(self::$tab_links);
    }
    /*
    Retourne un lien ou rien.
    Liens ex :
       "item/add_edit_item_view ou item" ou "item/browse_items_view".
    Option ex :
        foward => voir cas 1 de l'exemple ci-dessus.
        back   => voir cas 2 de l'exemple ci-dessus.
     */
    public function manage_btn_back($link,$option): string|null{
        if($option == "foward"){$this->forward($link);}
        if($option == "back"){
            /*
            Au cas ou l'utilisateur chipote dans la barre de rechercher,
            et tape l'url pour aller dans item/add_edit_item par exemple
            sans passer par les boutons etc et qu'ensuite clique sur back.
            Il est dirigigé vers browse_item_view par défaut
             */
            if($this->back() == null){return "item/browse_items_view";}
            return $this->back();
        }
        return null;
    }
}