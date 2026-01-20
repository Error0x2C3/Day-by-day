<?php
require_once "framework/Model.php";
Class Bids extends Model{
    //TODO Save + persist + getter + setter + validations
    public function __construct(
        private int $owner_id,
        private int $item_id,
        private DateTime $create_at,
        private float $amount,
    ){
        if($this->amount <= 0 && !preg_match(Configuration::get("decimal_regles"),(string)$this->amount)){
            throw new InvalidArgumentException(
                "Le montant de l'enchère doit être positif."
            );
        }
    }

    public function get_owner_id(): int{
        return $this->owner_id;
    }
    public function get_item_id(): int{
        return $this->item_id;
    }
    public function get_create_at(): DateTime{
        return  $this->create_at;
    }
    public function get_amount(): int {
        return $this->amount;
    }
}