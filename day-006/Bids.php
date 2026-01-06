<?php
require_once "framework/Model.php";
Class bids extends Model{

    public function __construct(
        public int $owner_id,
        public int $idem,
        public DateTime $create_at,
        public float $amount,
    ){
        if($this->amount <= 0 && !preg_match(Configuration::get("decimal_regles"),(string)$this->amount)){
            throw new InvalidArgumentException(
                "Le montant de l'enchère doit être positif."
            );
        }
    }
}