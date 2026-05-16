<?php
require_once 'framework/Controller.php';
require_once 'framework/Model.php';
/*
Sert à factoriser les barres de navigations & Co.
 */
class Navigation {

    // Permet de générer la barre de nav en haut + les boutons aux besoins.
    /*
    Ex:
    $path_this_page -> "item/add_edit_item_view",
    $name_page -> Add edit item,
    $btn_return_link -> "historynav/manage_btn_back/back",
    $$btn_save_id_form -> "item-form". // id du formulaire qu'on veut soumettre.
    */
    public static function top_bar(string $path_this_page,string $name_page,string $btn_return_link="",string $btn_save_id_form=""): string{
        // += -> .=
        // HTML5 permet à un bouton de soumettre n’importe quel formulaire via son id.
        $html  = '<nav class="top-nav">';
        $html .=    '<div class="top-wrap" >';
        if($btn_return_link !=""){
            // S'il y a un lien pour soumettre un formulaire.
            $html .= '<a href="'.$btn_return_link.'" class="btn-return"><i class="bi bi-arrow-left"></i></a>';
        }
        // Si l'utilisateur clique sur le nom de la page en haut il revient sur cette même page.
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
    public static function bottom_nav(string $active,?User $user = null): string {
        // += -> .=+
        $html  = '<nav class="bottom-nav">';
        $html .=     '<div class="nav-wrap">';

        $html .=         '<a class="nav-item-btn ' . ($active === 'browse_items' ? 'active' : '') . '" href="item/browse_items_view">';
        $html .=             '<i class="bi bi-compass"></i><span>Browse</span>';
        $html .=         '</a>';
        // echo $user->get_role()->value;
            if (!isset($user) || $user->get_role() !== Role::Guest) {
            $html .=         '<a class="nav-item-btn ' . ($active === 'my_items' ? 'active' : '') . '" href="item/my_items_view">';
            $html .=             '<i class="bi bi-house-door"></i><span>My Items</span>';
            $html .=         '</a>';

            $html .=         '<a class="nav-item-btn ' . ($active === 'Add_item' ? 'active' : '') . '" href="item/add_offer_view">';
            $html .=             '<i class="bi bi-plus-circle"></i><span>Add Offer</span>';
            $html .=         '</a>';

            $html .=         '<a class="nav-item-btn ' . ($active === 'profile' ? 'active' : '') . '" href="profile/profile_view">';
            $html .=             '<i class="bi bi-gear"></i><span>Profile</span>';
            $html .=         '</a>';
            }
            else {
            // Pour le guest : bouton login
            $html .= '<a class="nav-item-btn" href="login">';
            $html .=     '<i class="bi bi-magic"></i><span>Join Us</span></a>';
        }


        $html .=     '</div>';
        $html .= '</nav>';

        return $html;
    }

    // Permet de générer la barre tout en bas.
    public static function bottom_bar(): string {
        $html="";
        if (Configuration::is_dev()) {
            // += -> .=
            $html = '<div class="bottom-bar fixed-bottom">';
            $html .= '<div class="container-fluid h-100 d-flex justify-content-between align-items-center">';

            $html .= '<div class="d-flex align-items-center gap-2 time-display">';
            $html .= '<i class="bi bi-clock"></i>';
            $html .= '<span>' . AppTime::get_current_datetime_Other_format() . '</span>';
            $html .= '</div>';

            $html .= '<div class="right-actions d-flex gap-2">';
            $html .= '<a href="time/advance_next/1" class="btn-time">';
            $html .= '<i class="bi bi-clock"></i> +1h';
            $html .= '</a>';
            $html .= '<a href="time/advance_next/24" class="btn-time">';
            $html .= '<i class="bi bi-calendar2"></i> +1d';
            $html .= '</a>';
            $html .= '<a href="time/advance_next/168" class="btn-time">';
            $html .= '<i class="bi bi-calendar2"></i> +1w';
            $html .= '</a>';
            $html .= '<a href="time/reset"  class="btn-reset">';
            $html .= '<i class="bi bi-arrow-counterclockwise"></i> Reset';
            $html .= '</a>';
            $html .= '</div>';

            $html .= '</div>';
            $html .= '</div>';
        }else{
            $html = '<div class="bottom-bar fixed-bottom">';
            $html .= '</div>';
        }
        return $html;
    }


    // Encode un lien pour la gestion du buton back.
    public static function encode($link){
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($link) ));
    }
    // Décode un lien pour la gestion du bouton back.
    public static function decode($link_crypte){
        return json_decode(base64_decode( str_replace(['-', '_'], ['+', '/'], $link_crypte), true));
    }

    // Génére les cartes d'annonce en Boodstrap.
    public static function generate_cards(User $user, Items $item, array $btn_back, array $list_pictures_item): string {
        $highest_bidder_id = $item->get_highest_bidder() instanceof Bids ? $item->get_highest_bidder()->get_owner_id() : null;
        $phrase = count($list_pictures_item) > 1 ? "images" : "image";

        return '<div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card">
                    <a href="item/open_item_view/'.$item->get_id().'/'.self::encode($btn_back).'" class="stretched-link"></a>
                    <div class="position-relative">
                        '.(!empty($list_pictures_item) ?
                        '<img src="'.$list_pictures_item[0].'" class="card-img-top" alt="Card image">' :
                        '<i class="bi bi-image card-img-top d-flex align-items-center justify-content-center" style="font-size: 8rem; height: 100%; width: 100%; color: grey;"></i>'
                        ).'             
                        <div class="card-img-overlay">
                               '.($highest_bidder_id == $user->get_id() && $item->has_auction() ?
                                    ($item->get_owner_id() == $user->get_id() ?
                                        ""
                                        :
                                        '<div class="badge-highest-bidder">
                                            <i class="bi bi-trophy"></i><span>Highest Bidder</span>
                                        </div>'
                                    )
                                    :
                                    ($item->get_owner_id() == $user->get_id() ?
                                        ""
                                        :
                                        '<div class="badge-bidder">
                                            <i class="bi bi-bag"></i> <span>Bidder</span>
                                        </div>'
                                    )
                                ).'
                                <div class="badges-top-right">
                                    '.($item->has_auction() && !$item->has_buy_now() ?
                                            '<div class="badge-auction">
                                                <i class="bi bi-shop-window"></i>
                                                <span>Auction</span>
                                            </div>' : ""
                                    ).
                                    ($item->has_buy_now() && !$item->has_auction() ?
                                            '<div class="badge-buy-now">
                                                <i class="bi bi-bag"></i>
                                                <span>Buy Now</span>
                                            </div>' : ""
                                        ).
                                    ($item->has_buy_now() && $item->has_auction() ?
                                            '<div class="badge-auction">
                                                <i class="bi bi-shop-window"></i>
                                                <span>Auction</span>
                                            </div>
                                            <div class="badge-buy-now">
                                                <i class="bi bi-bag"></i>
                                                <span>Buy Now</span>
                                            </div>' : ""
                                        ).'
                                </div>
                                '.(count($list_pictures_item) > 0 ?
                                    '<div class="badge-images">
                                        <i class="bi bi-images"></i>
                                        <span>'.count($list_pictures_item).' '.$phrase.'</span>
                                    </div>' : ""
                                ).'
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-title-custom">'.$item->get_title().'</p>
                        <p class="card-owner-custom"><span class="by">by</span> '.$item->get_owner_pseudo().'</p>
                        <div class="d-flex justify-content-between align-items-end mt-3">
                            <div class="main-price-container">
                                '.($item->has_auction() && !$item->has_buy_now() ?
                                    '<p class="card-starting-bid-custom mb-0">
                                        € '.number_format($item->get_starting_bid(), 2, ',', '.').'
                                    </p>' : ""
                                ).
                                ($item->has_buy_now() ?
                                    '<p class="card-buy-now-price-custom mb-0">
                                        € '.number_format($item->get_buy_now_price(), 2, ',', '.').'
                                    </p>' : ""
                                ).'
                            </div>
                            '.($item->get_max_bid() > 0 ?
                                '<div class="text-end">
                                    <p class="card-current-bid-label mb-0">Current bid</p>
                                    <p class="card-current-bid-value mb-0">
                                        € '.number_format($item->get_max_bid(), 2, ',', '.').'
                                    </p>
                                </div>' : ""
                            ).'
                        </div>
                        '.(!$item->time_has_passed() ?
                            '<p class="card-timer-custom mt-3 mb-0">
                                <i class="bi bi-clock"></i>
                                '.$item->time_elapsed_string().'
                            </p>'
                        :
                            '<p class="card-timer-expired-custom mt-3 mb-0">
                                <i class="bi bi-clock"></i>
                                Closed
                            </p>'
                        ).'
                    </div>
                </div>
            </div>';
    }

    // Génére les cartes d'annonce en Boodstrap.
    public static function generate_cards_for_sales_purchase(User $user, Items $item, array $btn_back, array $list_pictures_item): string {
        $highest_bidder_id = $item->get_highest_bidder() instanceof Bids ? $item->get_highest_bidder()->get_owner_id() : null;
        $phrase = count($list_pictures_item) > 1 ? "images" : "image";

        return '<div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card">
                    <a href="item/open_item_view/'.$item->get_id().'/'.self::encode($btn_back).'" class="stretched-link"></a>
                    <div class="position-relative">
                        '.(!empty($list_pictures_item) ?
                        '<img src="'.$list_pictures_item[0].'" class="card-img-top" alt="Card image">' :
                        '<i class="bi bi-image card-img-top d-flex align-items-center justify-content-center" style="font-size: 8rem; height: 100%; width: 100%; color: grey;"></i>'
                        ).'             
                        <div class="card-img-overlay">
                               '.($highest_bidder_id == $user->get_id() && $item->has_auction() ?
                                    ($item->get_owner_id() == $user->get_id() ?
                                        ""
                                        :
                                        '<div class="badge-highest-bidder">
                                                                <i class="bi bi-trophy"></i><span>Highest Bidder</span>
                                                            </div>'
                                    )
                                    :
                                    ($item->get_owner_id() == $user->get_id() ?
                                        ""
                                        :
                                        '<div class="badge-bidder">
                                                                <i class="bi bi-bag"></i> <span>Bidder</span>
                                                            </div>'
                                    )
                                ).'
                                <div class="badges-top-right">
                                    '.($item->has_auction() && !$item->has_buy_now() ?
                                            '<div class="badge-auction">
                                                <i class="bi bi-shop-window"></i>
                                                <span>Auction</span>
                                            </div>' : ""
                                    ).
                                    ($item->has_buy_now() && !$item->has_auction() ?
                                        '<div class="badge-buy-now">
                                            <i class="bi bi-bag"></i>
                                            <span>Buy Now</span>
                                        </div>' : ""
                                    ).
                                    ($item->has_buy_now() && $item->has_auction() ?
                                        '<div class="badge-auction">
                                            <i class="bi bi-shop-window"></i>
                                            <span>Auction</span>
                                        </div>
                                        <div class="badge-buy-now">
                                            <i class="bi bi-bag"></i>
                                            <span>Buy Now</span>
                                        </div>' : ""
                                    ).'
                                </div>
                                '.(count($list_pictures_item) > 0 ?
                                    '<div class="badge-images">
                                        <i class="bi bi-images"></i>
                                        <span>'.count($list_pictures_item).' '.$phrase.'</span>
                                    </div>' : ""
                                ).'
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-title-custom">'.$item->get_title().'</p>
                        <p class="card-owner-custom"><span class="by">by</span> '.$item->get_owner_pseudo().'</p>
                        <div class="d-flex justify-content-between align-items-end mt-3">
                            <div class="main-price-container">
                                '.($item->has_auction() && !$item->has_buy_now() ?
                                    '<p class="card-starting-bid-custom mb-0">
                                        € '.number_format($item->get_starting_bid(), 2, ',', '.').'
                                    </p>' : ""
                                ).
                                ($item->has_buy_now() ?
                                    '<p class="card-buy-now-price-custom mb-0">
                                        € '.number_format($item->get_buy_now_price(), 2, ',', '.').'
                                    </p>' : ""
                                ).'
                            </div>
                            '.($item->get_max_bid() > 0 ?
                                '<div class="text-end">
                                    <p class="card-current-bid-label mb-0">Current bid</p>
                                    <p class="card-current-bid-value mb-0">
                                        € '.number_format($item->get_max_bid(), 2, ',', '.').'
                                    </p>
                                </div>' : ""
                            ).'
                        </div>
                        '.(!$item->time_has_passed() ?
                            '<p class="card-timer-custom mt-3 mb-0">
                                            <i class="bi bi-clock"></i>
                                            '.$item->time_elapsed_string().'
                                        </p>'
                            :
                            '<p class="card-timer-expired-custom mt-3 mb-0">
                                <i class="bi bi-clock"></i>
                                Closed
                            </p>'
                        ).'
                    </div>
               </div>
               <div class="mt-3 text-secondary small">
                        <div class="mb-1">
                            <i class="bi bi-currency-dollar"></i> Final price € '.number_format($item->get_max_bid(), 2, ',', '.').'
                        </div>
                        <div class="mb-1">
                            <i class="bi bi-trophy"></i>'.$item->has_a_winner()->get_owner_pseudo().'
                        </div>
                        <div>
                            <i class="bi bi-clock"></i> Closed on '.$item->get_end_at()->format('d-m-Y H:i:s').'
                        </div>
                </div>
            </div>';
    }

}


