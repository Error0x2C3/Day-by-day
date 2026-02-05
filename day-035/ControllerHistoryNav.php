<?php
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once "utils/AppTime.php";
require_once 'model/User.php';

// Gestion de l'historique de navigation avec le bouton back <- de la barre de titre de l'application.
class ControllerHistoryNav extends Controller {
    public function index(): void{$this->check_user_session();}
    private static $tab_links = array();
    public function manage_btn_back(): void{
        $this->check_user_session();
        /*
         Arguments :
            1) $option = "forward" ou "back"
            2) $tab_link = [ $link_prev , $link_next(optionnelle) ]
                Exemples de liens :
                   - "item/browse_items_view"
                   - "item/add_edit_item_view"
         */
        // je mets back si $_GET["param1"] est null ou non initialisé
        // pour que par défaut il retourne dans browse item.
        $option = isset($_GET["param1"]) ? $_GET["param1"] : "back";
        if($option == "forward"){
            if(isset($_GET["param2"])){
                /*
                Exemple côté html pour param2 :
                $mon_tableau = ["item/browse_items_view", "item/open_item_view"];
                $json = json_encode($mon_tableau);
                $donnees_codees = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($json));
                Voir explication A) pour le pourquoi.
                */
                // On remet les caractères normaux du Base64.
                $data = str_replace(['-', '_'], ['+', '/'], $_GET["param2"]);
                //$this->redirect("item","add_edit_item_view");
                $tab_link = json_decode(base64_decode($data, true));
                //$tab_link = json_decode($_GET["param2"]);
                $link_prev = $tab_link[0];
                $link_next =  $tab_link[1];
                $this->forward($link_prev,$link_next);
            }
        }
        if($option == "back"){
            if($this->back() != null){
                $link = explode('/', $this->back());
                /*
                Sortie de $link:
                Array (
                    [0] => "item"
                    [1] => "browse_items_view"
                )
                 */
                $this->redirect($link[0],$link[1]);
            }
            /*
            Au cas ou l'utilisateur chipote dans la barre de recherche,
            et tape l'url pour aller directement dans item/add_edit_item par exemple,
            sans passer par les boutons etc et qu'ensuite clique sur back.
            Il est alors dirigé vers browse_item_view par défaut.
            */
            $this->redirect("item","browse_items_view");
        }
    }
    public function forward($link_prev, $link_next): void{
        $this->check_user_session();
        self::$tab_links [] = $link_prev;
        // explode() divise la chaîne en tableau à chaque fois qu'elle trouve "/",
        /*
        Sortie de $link_next :
            Array (
                [0] => "item"
                [1] => "open_item_view"
            )
        */
        $link = explode('/', $link_next);
        $this->redirect($link[0],$link[1]);
    }
    public function back():string|null{
        $this->check_user_session();
        /*
        array_pop(self::$tab_links) :
        Elle supprime physiquement le dernier élément du tableau.
        Elle retourne la valeur de cet élément qui vient d'être supprimé ou null si le tableau est vide.
        Exemple de sortie d'un élément du tableau $tab_links :
            ["item/browse_items_view"]
            Array (
                [0] => "item"
                [1] => "open_item_view"
            )
        */
        return array_pop(self::$tab_links);
    }


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

    SOLUTION :
        Principe pour gérer la barre de retour :
        - Un tableau qui stocke les liens des pages précédentes.
            * Lorqu'on qu'on passe d'une page à une autre (forward) =>
              le tableau stocke le lien de la page d'ou l'on vient.
            * lorsqu'on revient à la page précédente (back) =>
              On supprime le lien de cette page du tableau.

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

    /*
    EXPLICATIONS :
    A)
      --- Explication ---
      Partie 1 - Pourquoi utiliser json_encode($array) puis base64_encode($json)) :

          Une URL ne peut pas transporter nativement des structures de données complexes comme un tableau PHP.

          1)
          JSON transforme le tableau (une entité informatique) en une simple chaîne de caractères (du texte).
          Ex : ["a", "b"] devient la chaîne "["a","b"]".
          2)
          Même une fois transformé en texte,
          le JSON contient des caractères comme les guillemets ", les crochets [ ou les virgules ,.
          Problème :
            => Ces caractères ont une signification spéciale pour les navigateurs
               et les serveurs web (ils servent à définir les paramètres ou la structure de l'URL).
               ce qui peut provoquer des erreurs d'interprétation ou des coupures (comme mon erreur 404).
          Solution :
            => Il prend ta chaîne JSON et la transforme en une suite de caractères "neutres"
               (lettres et chiffres uniquement) que n'importe quel serveur acceptera de transmettre sans broncher.

      Partie 2 - Pourquoi utiliser str_replace(['+', '/', '='], ['-', '_', ''] :

          Problème :
              Passer du JSON et du Base64 directement dans une URL,
              sans gérer les caractères spéciaux (comme /, + ou =)
              qui font partie de la syntaxe d'une URL.
              => Obtiens une erreur 404 Not Found :
                    le serveur voit les / à l'intérieur de la chaîne encodée et croit qu'on cherche
                    un dossier qui n'existe pas au lieu d'un paramètre.
          Solution :
              En PHP, le Base64 standard contient des caractères interdits dans les URLs.
              Tu dois créer une petite fonction (ou faire un str_replace) pour les nettoyer.
              1) Remplacer ces caractères qui posent problème dans l'URL au moment de la déclaration du tableau.
                  EX :
                    $mon_tableau = ["item/browse_items_view", "item/open_item_view"];
                    $json = json_encode($mon_tableau);
                    // On remplace les caractères qui posent problème dans l'URL.
                    $donnees_codees = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($json));
              2) Remettre les caractères originaux avant de décoder.
                  EX :
                    $data = $_GET["param2"];
                    // On remet les caractères normaux du Base64.
                    $data = str_replace(['-', '_'], ['+', '/'], $data);
                    // On décode le Base64 puis le JSON (le "true" est important pour avoir un tableau).
                    $tab_link = json_decode(base64_decode($data), true);
      --- Explication ----
     */

}