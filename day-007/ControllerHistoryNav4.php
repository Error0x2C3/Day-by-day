<?php
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once "utils/AppTime.php";
require_once 'model/User.php';

// Gestion de l'historique de navigation avec le bouton back <- de la barre de titre de l'application.
class ControllerHistoryNav extends Controller {
    public function index(): void{$this->check_user_session();}
    /*
    ENNONCEE :
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
        * Lorqu'on qu'on passe d'une page à une autre (forward) =>
          le tableau stocke le lien de la page d'ou l'on vient.
        * lorsqu'on revient à la page précédente (back) =>
          on supprime le lien de cette page du tableau.

    EXEMPLE :
    $tab_links = [];
    Cas 1 :
    On va de Brows_item à Open_item.
    A)                      B)
    $tab_links = []         "item/browse_items_view"

    Cas 2 :
    De Open_item retour à Browse_item.
    array_pop(self::$tab_links);
    A)                                          B)
    $tab_links = ["item/browse_items_view"]     $tab_links=[]
     */
    private static $tab_links = array();
    public function manage_btn_back(): void{
        /*
         Arguments :
            1) $option = "forward" ou "back"
            2) $tab_link = [ $link_prev , $link_next(optionnelle) ]
                Exemples de liens :
                   - "item/browse_items_view"
                   - "item/add_edit_item_view"
         */
        $option = isset($_GET["param1"]) ?? "";
        if(isset($_GET["param2"])){
            // exemple côté html : $tab = base64_encode(json_encode($monTableau));
            $tab_link = json_decode(base64_decode($_GET["param2"], true));
            $link_prev = $tab_link[0];
            $link_next =  $tab_link[1];
            if($option == "forward" ){$this->forward($link_prev,$link_next);}
        }
        if($option == "back"){
            if($this->back() != null){
                $link = explode('/', $this->back());
                $this->redirect($link[0],$link[1]);
            }
            /*
            Au cas ou l'utilisateur chipote dans la barre de recherche,
            et tape l'url pour aller dans item/add_edit_item,
            sans passer par les boutons etc et qu'ensuite clique sur back.
            Il est alors dirigé vers browse_item_view par défaut.
            */
            $this->redirect("item","browse_items_view");
        }
    }
    public function forward($link_prev, $link_next): void{
        self::$tab_links [] = $link_prev;
        // explode() divise la chaîne en tableau à chaque fois qu'elle trouve "/",
        /*
        Sortie :
            Array (
                [0] => "item"
                [1] => "browse_items_view"
            )
        */
        $link = explode('/', $link_next);
        $this->redirect($link[0],$link[1]);
    }
    public function back():string|null{
        /*
        array_pop(self::$tab_links) :
        Elle supprime physiquement le dernier élément du tableau.
        Elle retourne la valeur de cet élément qui vient d'être supprimé ou null si le tableau est vide.
         */
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

}