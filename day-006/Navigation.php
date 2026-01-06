<?php
/*
Sert à factoriser les barres de navigations
 */
class Navigation {

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
    array_pop($tab_links);
    A)                                          B)
    $tab_links = ["item/browse_items_view"]     $tab_links=[]
    $pointeur = 0           $pointeur = -1
     */

    
    private static $tab_links = array();

    // Permet de générer la barre de nav en haut + les boutons aux besoins.
    public static function top_bar(string $path_this_page,string $name_page,string $btn_return_link="",string $btn_save_id_form=""): string{
        // += -> .=
        // HTML5 permet à un bouton de soumettre n’importe quel formulaire via son id.
        $html  = '<nav class="top-nav">';
        $html .=    '<div class="top-wrap" >';
        if($btn_return_link !=""){
            $html .= '<a href="'.$btn_return_link.'" class="btn-return"><i class="bi bi-arrow-left"></i></a>';

        }
        $html .=        '<a href="'.$path_this_page.'" class="brand">';
        $html .=            '<span class="brand-name">'.$name_page.'</span>';
        $html .=            '<i class="bi bi-cart"></i>';
        $html .=        '</a>';
        if($btn_save_id_form != ""){
            $html .= '<button type="submit" class="btn-save-icon" form="'.$btn_save_id_form.'" aria-label="Save">
            <i class="bi bi-floppy"></i>
          </button>';
           //$html .= '<button type="submit" class="btn btn-sm btn-success" form="'.$btn_save_id_form.'">Save</button>';

        }
        $html .=    '</div>';
        $html .= '</nav>';
        return $html;
    }

    // Permet de générer la barre de nav en bas.
    public static function bottom_nav(string $active): string {
        // += -> .=
        $html  = '<nav class="bottom-nav">';
        $html .=     '<div class="nav-wrap">';

        $html .=         '<a class="nav-item-btn ' . ($active === 'browseItems' ? 'active' : '') . '" href="browseItems/view">';
        $html .=             '<i class="bi bi-compass"></i><span>Browse</span>';
        $html .=         '</a>';

        $html .=         '<a class="nav-item-btn ' . ($active === 'myItems' ? 'active' : '') . '" href="myItems/view">';
        $html .=             '<i class="bi bi-house-door"></i><span>My Items</span>';
        $html .=         '</a>';

        $html .=         '<a class="nav-item-btn ' . ($active === 'addOffer' ? 'active' : '') . '" href="addOffer/view">';
        $html .=             '<i class="bi bi-plus-circle"></i><span>Add Offer</span>';
        $html .=         '</a>';

        $html .=         '<a class="nav-item-btn ' . ($active === 'profile' ? 'active' : '') . '" href="profile/view">';
        $html .=             '<i class="bi bi-gear"></i><span>Profile</span>';
        $html .=         '</a>';

        $html .=     '</div>';
        $html .= '</nav>';

        return $html;
    }

    // Permet de générer la barre tout en bas.
    public static function bottom_bar(): string {
        // += -> .=
        $html  = '<div class="bottom-bar fixed-bottom">';
        $html .=     '<div class="container-fluid h-100 d-flex justify-content-between align-items-center">';

        $html .=         '<div class="d-flex align-items-center gap-2 time-display">';
        $html .=             '<i class="bi bi-clock"></i>';
        $html .=             '<span>' . AppTime::get_current_datetime_Other_format() . '</span>';
        $html .=         '</div>';

        $html .=         '<div class="right-actions d-flex gap-2">';
        $html .=             '<button type="button" class="btn-time">';
        $html .=                 '<i class="bi bi-clock"></i> +1h';
        $html .=             '</button>';
        $html .=             '<button type="button" class="btn-time">';
        $html .=                 '<i class="bi bi-calendar2"></i> +1d';
        $html .=             '</button>';
        $html .=             '<button type="button" class="btn-time">';
        $html .=                 '<i class="bi bi-calendar2"></i> +1w';
        $html .=             '</button>';
        $html .=             '<button type="button" class="btn-reset">';
        $html .=                 '<i class="bi bi-arrow-counterclockwise"></i> Reset';
        $html .=             '</button>';
        $html .=         '</div>';

        $html .=     '</div>';
        $html .= '</div>';

        return $html;

    }


}