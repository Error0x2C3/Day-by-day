<?php
// Save , persist  , validation , getter setter , validate_conctruct
class V_items_status extends Model{
    // Permet d'obtenir des informations sur le statut des annonces.
    // l'id de l'item.
    private int $id;
    private string $title;
    private string $description;
    private int $owner;
    private DateTime $create_at;
    private float $buy_now_price;
    private int $duration_days;
    private float $starting_bid;
    // La date/heure de fin des enchères de l'annonce
    // (calculée en fonction de la date/heure de création
    // et de la durée).
    public DateTime $end_at;
    // Le nombre d'offres d'achat faites sur l'annonce.
    public int $bid_count;
    // Le montant de l'offre d'achat la plus élevée faite sur l'annonce
    // (ou NULL si aucune offre n'a encore été faite).
    public float $max_bid;
    // Un booléen indiquant si l'annonce est une vente directe
    // (achat immédiat uniquement, sans enchères).
    public int $is_direct_sale;
    // Un booléen indiquant si l'annonce est une enchère
    // (avec ou sans achat immédiat).
    public int $is_auction;
    // Un booléen indiquant si l'annonce
    // possède un prix d'achat immédiat.
    public int $has_buy_now;
    // Un booléen indiquant si l'annonce a reçu des offres d'achat ;
    public int $has_bids;
    // Vaut 1 si une offre atteint ou dépasse le buy_now_price,
    // indépendamment de la date de fin des enchères.
    public int $buy_now_reached;
    // Un booléen indiquant si l'annonce n'a pas été
    // achetée via l'achat immédiat ;
    public int $not_purchased_direct_sale;
    public Items $item;
    public function __construct(int $item_id){
        $this->validate_contruct($item_id);
    }

    public function get_id():int {
        return $this->id;
    }
    public function get_title():string {
        return $this->title;
    }
    public function get_description():string {
        return $this->description;
    }
    public function get_owner():int {
        return $this->owner;
    }
    public function get_created_at():DateTime{
        return $this->create_at;
    }
    public function get_buy_now_price():float{
        return $this->buy_now_price;
    }
    public function get_duration_days():int{
        return $this->duration_days;
    }
    public function get_starting_bid():float{
        return $this->starting_bid;
    }
    public function get_end_at():DateTime{
        return $this->end_at;
    }
    public function get_bid_count():int{
        return $this->bid_count;
    }
    public function get_max_bid(): float{
        return $this->max_bid;
    }
    public function get_is_direct_sale(): int{
        return $this->is_direct_sale;
    }
    public function get_is_auction(): int{
        return $this->is_auction;
    }
    public function get_has_buy_now(): int{
        return $this->has_buy_now;
    }
    public function get_has_bids(): int{
        return $this->has_bids;
    }
    public function get_buy_now_reached(): int{
        return $this->not_purchased_direct_sale;
    }

    public function validate_contruct(int $item_id):void {
        if( Items::get_Item_instance_ById($item_id)){
            $item = Items::get_Item_instance_ById($item_id);
            $this->id = $item->get_id();
            $this->title = $item->get_title();
            $this->description = $item->get_description();
            $this->owner = $item->get_owner_id();
            $this->create_at = $item->get_created_at();
            $this->buy_now_price = $item->get_buy_now_price();
            $this->duration_days = $item->get_duration_days();
            $this->starting_bid = $item->get_starting_bid();
            // la date/heure de fin des enchères de l'annonce (calculée en fonction de la date/heure de création et de la durée).
            $this->end_at = $item->end_at();
            // le nombre d'offres d'achat faites sur l'annonce.
            $this->bid_count = is_int($item->bid_count()) ? $item->bid_count() : throw new InvalidArgumentException("La date n'est pas bonne."); ;
            // le montant de l'offre d'achat la plus élevée faite sur l'annonce (ou NULL si aucune offre n'a encore été faite).
            $this->max_bid = $item->max_bid();
            // Un booléen indiquant si l'annonce est une vente directe (achat immédiat uniquement, sans enchères).
            $this->is_direct_sale = $item->is_direct_sale();
            // Un booléen indiquant si l'annonce est une enchère (avec ou sans achat immédiat).
            $this->is_auction = $item->is_auction();
            // Un booléen indiquant si l'annonce possède un prix d'achat immédiat.
            $this->has_buy_now = $item->has_buy_now();
            // Un booléen indiquant si l'annonce a reçu des offres d'achat.
            $this->has_bids = $item->has_bids();
            // Vaut 1 si une offre atteint ou dépasse le buy_now_price, indépendamment de la date de fin des enchères.
            $this->buy_now_reached = $item->buy_now_reached();
            // Un booléen indiquant si l'annonce n'a pas été achetée via l'achat immédiat.
            $this->not_purchased_direct_sale = $item->not_purchased_direct_sale();
        }else{throw new InvalidArgumentException("L'annonce n'existe pas.");}
    }
}
