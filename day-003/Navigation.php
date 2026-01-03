<?php

class Navigation {
    public static function top_bar(string $word): string{
        // += -> .=
        $html  = '<nav class="top-nav">';
        $html .=    '<div class="top-wrap" >';
        $html .=        '<a href="browse_items/view" class="brand">';
        $html .=            '<span class="brand-name">'.$word.'</span>';
        $html .=            '<i class="bi bi-cart"></i>';
        $html .=        '</a>';
        $html .=    '</div>';
        $html .= '</nav>';
        return $html;
    }

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