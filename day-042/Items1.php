<?php

require_once "framework/Model.php";
require_once 'model/Bids.php';
require_once 'model/Item_pictures.php';
class Items extends Model {
 // Les annonces.
    // private ?type $nom_variable => peut-être nullabe.
    private String $title;
    private ?String $description;
    private DateTime $created_at;
    private int $duration_days;
    private int $owner_id;
    private ?float $buy_now_price;
    private ?float $starting_bid;
    /* =========================================================
       v_items_status.
   ========================================================= */
    private int $id;
    // la date/heure de fin des enchères de l'annonce (calculée en fonction de la date/heure de création et de la durée).
    private ?DateTime $end_at;
    // le nombre d'offres d'achat faites sur l'annonce.
    private float $bid_count;
    // Le montant de l'offre d'achat la plus élevée faite
    // sur l'annonce (ou NULL si aucune offre n'a encore été faite).
    private ?float $max_bid;
    // Un booléen indiquant si l'annonce est une vente directe (achat immédiat uniquement, sans enchères).
    private bool $is_direct_sale;
    // Un booléen indiquant si l'annonce est une enchère (avec ou sans achat immédiat).
    private bool $is_auction;
    // Un booléen indiquant si l'annonce possède un prix d'achat immédiat.
    private bool $has_buy_now;
    // Un booléen indiquant si l'annonce a reçu des offres d'achat.
    private  bool $has_bids;
    // Vaut 1 si une offre atteint ou dépasse le buy_now_price, indépendamment de la date de fin des enchères.
    private int $buy_now_reached;
    // Un booléen indiquant si l'annonce n'a pas été achetée via l'achat immédiat.
    private bool $not_purchased_direct_sale;
    /* =========================================================
       v_items_status.
   ========================================================= */
    public function __construct(
        // private ?type $nom_variable => peut-être nullabe.
        String $title,
        ?String $description,
        DateTime $created_at,
        int $duration_days,
        int $owner_id,
        ?float $buy_now_price,
        ?float $starting_bid,
        int $id,
        bool $verif_regle_metier =true //
    ) {
        // Vérifie les règles métiers.
        if($verif_regle_metier){
            $this->validate_contruct($title,$description,$created_at,$duration_days,$owner_id,$buy_now_price,$starting_bid);
            $this->id = $id;
            $this->init_v_items_status();
        }else
        {
            $this->title = $title;
        $this->description = $description;
        $this->created_at = $created_at;
        $this->duration_days = $duration_days;
        $this->owner_id = $owner_id;
        $this->buy_now_price = $buy_now_price;
        $this->starting_bid = $starting_bid;
        $this->id = $id;
        $this->init_v_items_status();
        }
    }
    private $errors = [];
    public function get_title(): string
    {
        return $this->title;
    }
    public function get_description(): string
    {
        return $this->description;
    }
    public function get_owner_id(): int
    {
        return $this->owner_id;
    }
    public function get_created_at(): DateTime
    {
        return $this->created_at;
    }
    public function get_buy_now_price(): ?float
    {
        return $this->buy_now_price;
    }
    public function get_duration_days(): int
    {
        return $this->duration_days;
    }
    public function get_starting_bid(): ?float{
        /*
        NULL => Pas d’enchères possibles.
        0    => Pas d’enchères possibles.
        Starting_bid > 0 Enchères autorisées.
        Starting_bid < 0 Ivalides.
        "starting_bid si elle est remplie" :
            Signifie contient une valeur stric supérieur à 0.
         */
        return $this->starting_bid;
    }
    public function get_id(): int {
        return $this->id;
    }

    public function get_end_at(): ?DateTime
    {
        return $this->end_at;
    }

    public function get_bid_count(): float
    {
        return $this->bid_count;
    }

    public function get_max_bid(): ?float
    {
        return $this->max_bid;
    }

    public function is_direct_sale(): bool
    {
        return $this->is_direct_sale;
    }

    public function is_auction(): bool
    {
        return $this->is_auction;
    }

    public function is_has_buy_now(): bool
    {
        return $this->has_buy_now;
    }

    public function is_has_bids(): bool
    {
        return $this->has_bids;
    }

    public function is_not_purchased_direct_sale(): bool
    {
        return $this->not_purchased_direct_sale;
    }

    public function get_buy_now_reached(): int
    {
        return $this->buy_now_reached;
    }
    public function set_title($title): bool{
        if($this->validate_title($title)){
            $this->title = $title;
            return true;
        }
        return false;
    }
    public function set_description(string $description): bool{
        if($this->validate_description($description)){
            $this->description = $description;
            return true;
        }
        return false;
    }

    public function set_duration_days(int $date):bool{
        if($this->validate_duration_days($date)){
            $this->duration_days= $date;
            return true;
        }
        return false;
    }

    public function set_buy_now_price(int $price):bool{
        if($this->validate_buy_now_price($price)){
            $this->buy_now_price= $price;
            return true;
        }
        return false;
    }
    public function set_starting_bid(int $price):bool{
        if($this->validate_starting_bid($price)){
            $this->starting_bid= $price;
            return true;
        }
        return false;
    }

     public function set_picture_path(string $picture_path):bool{
        if(self::validate_picture_path($picture_path)){
            $this->save_picture_in_item_picture($picture_path);

            return true;
        }
        return false;
    }


    public function validate_contruct(string $title,?String $description,DateTime $created_at,int $duration_days,int $owner_id,?float $buy_now_price,?float $starting_bid):void{
        if($this->validate_title($title,$owner_id)){
            $this->title = $title;
        }else{throw new InvalidArgumentException("Le titre n'est pas bon.");}
        if($this->validate_description($description) || $description =="" || $description == null){
            // Peut être vide ou null.
            $this->description = $description;
        }else{throw new InvalidArgumentException("La description n'est pas bonne.");}
        if($this->validate_created_at($created_at)){
            $this->created_at = $created_at;
        }else{throw new InvalidArgumentException("La date n'est pas bonne.");}
        if($this->validate_duration_days($duration_days)){
            $this->duration_days = $duration_days;
        }else{throw new InvalidArgumentException("La duration_date n'est pas bonne.");}
        if($this->validate_owner_id($owner_id)){
            $this->owner_id = $owner_id;
        }else{throw new InvalidArgumentException("L'utilisateur n'existe pas.");}
        if($this->validate_buy_now_price($buy_now_price,$starting_bid , $buy_now_price) || $buy_now_price=="" || $buy_now_price == null){
            // Peut être vide ou null.
            $this->buy_now_price = $buy_now_price;
        }else{throw new InvalidArgumentException("Ça ne respecte pas les règles.");}
        if($this->validate_starting_bid($starting_bid) || $buy_now_price=="" || $buy_now_price == null){
            // Peut être vide ou null.
            $this->starting_bid = $starting_bid;
        }else{throw new InvalidArgumentException("Ça ne respecte pas les règles.");}
    }


    public function save_picture_in_item_picture($picture_path):bool{
       /* insert un nouvel element dans la BDD item picture */
        try {
            $stmt = self::execute(
                "
                INSERT INTO item_pictures (item, picture_path, priority)
                SELECT
                    :item_id,
                    :picture_path,
                    COALESCE(MAX(priority), 0) + 1
                FROM item_pictures
                WHERE item = :item_id
                ",
                [
                    "item_id"      => $this->get_id(),
                    "picture_path" => $picture_path
                ]
            );

            return $stmt->rowCount() === 1;

        } catch (PDOException $e) {
            return false;
        }
    }
       /*
     * vérifie si le chemin de la photo respecte bien les règles métiers.
     */
    public static function validate_picture_path($picture_path): bool {
        if(file_exists($picture_path)){
            return true;
        }
        return false;
    }
    
    /* =========================================================
        Respect des règle métiers.
    ========================================================= */

    //Vérifie si le title vérifie bien les règles métiers .
    public function validate_title(string $title,$owner_id): bool{
        if(strlen($title) >= Configuration::get("title_item_min")){
            $pdo = self::execute("Select * from items where owner = :owner",
                array(
                    "owner"=>$owner_id
                ));
            $array = $pdo->fetchAll(PDO::FETCH_ASSOC);
            if($array ===false){return false;}
            foreach($array as $row){
                if($row['title'] == $title){return false;}
            }
            return true;
        }
        return false;
    }

    public function validate_description(string $description): bool{
        /*
        Rappel :
            $string = null;
            echo strlen($string); => 0
         */
        if(strlen($description) > 0){
            if(strlen($description)>= Configuration::get("description_item_min")){
                return true;
            }else{return false;}
        }
        return true;
    }
    public function validate_created_at($created_at):bool{
        if($created_at instanceof DateTime){
            return true;
        }
        return false;
    }

    // Vérifie que duration_days est entre 1 et 365
    public function validate_duration_days($duration_days): bool{
        return $duration_days >= Configuration::get("duration_days_item_min") && $duration_days <= Configuration::get("duration_days_item_max");
    }

    public function validate_owner_id($owner_id):bool{
        if(User::get_user_by_id($owner_id) instanceof User){
            return true;
        }
        return false;
    }
    public function validate_starting_bid(float $value): bool{
        /*
         NULL => Pas d’enchères possibles valide + implique que l'annonce doit avoir un buy_now_price.
         0    => Pas d’enchères possibles valide.
         Starting_bid > 0 Enchères autorisées valide sous condition.
         Starting_bid < 0 Ivalides / Interdit non valide.
        */
        if($value < 0){return false;}
        if($value > 0){
            // Si c'est > 0, alors doit respecter cette régle.
            if(!preg_match(Configuration::get("decimal_regles"), (string)$value)) {
                return false;
            }
            return true;
        }
        return true;
    }

    /*
    Si starting_bid == null => l'annonce doit avoir un buy_now_price.
     */
    public function  validate_bid_or_buy_now_presence(bool $hasAuction, bool $hasBuyNow): bool{
        // S'il y a pas d'enchère => achat immédiat obligatoire.
        if(!$hasAuction && !$hasBuyNow){
            return false;
        }
        return true;
    }
    public function validate_buy_now_price(float $value,?float $starting_bid ,?float $buy_now_price):bool{
        /*
        NULL => Pas d’enchères possibles valide.
        0    => Pas d’enchères possibles valide.
        Starting_bid > 0 Enchères autorisées valide sous condition.
        Starting_bid < 0 Invalides / Interdit non valide.
        Si starting_bid == null => l'annonce doit avoir un buy_now_price.
        */
        // Est mis explicitement ici au lieu d'utilité les méthodes has_auction() et has_buy_now()
        // Car la méthode est aussi appelé dans le constructeur.
        $hasAuction = ($starting_bid !== null && $starting_bid > 0);
        $hasBuyNow  = ($buy_now_price !== null && $buy_now_price > 0);

        if(!$this->validate_bid_or_buy_now_presence($hasAuction,$hasBuyNow)){return false;}
        if($value > 0){
            // Si c'est > 0, alors doit respecter cette régle.
            if(preg_match(Configuration::get("decimal_regles"),(string)$value)) {
                /*
                si elle est remplie, doit être strictement supérieure à 0
                et strictement supérieur à starting_bid (si starting_bid n'est pas nulle)
                 */
                if($hasAuction  && $value <= $starting_bid){
                    return false;
                }
                return true;
            }
            return false;
        }
        if($value < 0){return false;}
        return true;
    }


    /* =========================================================
        Vérification Pour les annonces.
   ========================================================= */

    // Si l'annonce a le système d'enchère ou pas.
    public function has_auction(): bool {
        /*
       NULL → la fonctionnalité n’existe pas.
       0    → la fonctionnalité existe mais est désactivée.
       > 0  → la fonctionnalité est activée.

       Sans test explicite (!== null), PHP traite NULL et 0 de la même manière
       dans les comparaisons :

       null > 0  // false
       0 > 0     // false

       Or NULL et 0 n'ont pas le même sens métier.
       On doit donc tester explicitement NULL afin d'appliquer correctement
       la règle métier dans validate_bid_or_buy_now_presence().
       */
        return $this->get_starting_bid() != NUll && $this->get_starting_bid() > 0;
    }
    // Si l'annonce a le système d'achat immédiat ou pas.
    public function has_buy_now(): bool {
        /*
        NULL → la fonctionnalité n’existe pas.
        0    → la fonctionnalité existe mais est désactivée.
        > 0  → la fonctionnalité est activée.

        Sans test explicite (!== null), PHP traite NULL et 0 de la même manière
        dans les comparaisons :

        null > 0  // false
        0 > 0     // false

        Or NULL et 0 n'ont pas le même sens métier.
        On doit donc tester explicitement NULL afin d'appliquer correctement
        la règle métier dans validate_bid_or_buy_now_presence().
        */
        return $this->get_buy_now_price() != NULL && $this->get_buy_now_price() > 0;
    }
    /*
    Vérifie si une annonce peut recevoir des offres d'achat (enchères)
    de la part des utilisateurs de l'application.
    */
    public function can_receive_bids(): bool {
        return $this->has_auction();
    }

    /*
    Vérifie si un utilisateur a assez pour faire un achat immédiat
    sur une annonce qui a ce système.
    */
    public function is_buy_now_triggered($amount,$owner_id): bool|Bids{
        // Si l'annonce a le système d'achat immédiat.
        if($this->has_buy_now()){
            if($amount < $this->get_buy_now_price()){return false;}
            $bid = new bids($owner_id,$this->get_id(),new DateTime(),$amount,false);
            return $bid;
        }
        return false;
    }

    public function validate() : bool{
        $erro1 = false;
        $erro2 = false;
        // Validation du titre
        if (strlen($this->title) < Configuration::get("title_min_length") || strlen($this->title) > Configuration::get("title_max_length")) {
            $errors["bool_text_min_3_max_255"] = "Title length must be between 3 and 255 characters.";
            $erro1 = true;
        }

        // Validation du buy_now_price seulement si il est rempli
        if ($this->buy_now_price !== '' && $this->starting_bid !== '') {
            if ((float)$this->buy_now_price > (float)$this->starting_bid) {
                $this->errors["bool_buy_now_price_UP_starting_bid"] = "Buy now price must be greater than starting bid." ;
                $erro2 = true;
            }

        }
        return $erro1 && $erro2;
    }

    // Permet de savoir s'il y a un gagné durant les enchères, si oui qui/l'offre.
    public function has_a_winner():bool|Bids{
        if(!$this->should_close()){return false;}
        // 1) Une annonce qui combine les deux systèmes (enchères et achat immédiat)
        // dont la première enchère qui atteint ou dépasse le prix d'achat immédiat remporte l'annonce.
        // Temps écoulé ou pas est déjà géré dans $this->should_close().
        if($this->has_auction() && $this->has_buy_now()){
            if($this->get_first_winning_buy_now_bid() instanceof Bids && $this->has_buy_now()){
                // On renvoie l'offre gagnante.
                return $this->get_first_winning_buy_now_bid();
            }
            // 2) Si à la fin de la période d'enchères aucune
            //    offre n'a atteint le prix d'achat immédiat,
            //    l'annonce est remportée par l'offre la plus élevée.
            //    présuposse l'annonce a un système d'achat immédiat + enchère + temps écoulé.
            else if($this->has_auction() && $this->has_buy_now() && $this->time_has_passed() && !$this->get_first_winning_buy_now_bid()){
                // Récupére l'enchère la plus élevée pour
                // cette annonce même si elle n'atteint pas ni ne dépasse le prix immédiat.
                try{
                    // la plus grosse enchère, et si égalité, la plus ancienne.
                    $pdo = self::execute("SELECT b.* FROM bids b
                                JOIN items i ON b.item = i.id
                                WHERE b.item = :itemId 
                                AND i.buy_now_price IS NOT NULL 
                                AND i.buy_now_price > 0
                                AND i.starting_bid IS NOT NULL  
                                AND i.starting_bid > 0  
                                ORDER BY b.amount DESC, b.created_at ASC
                                LIMIT 1",
                        array(
                            "itemId"=> $this->get_id(),
                        ));
                    // Si la requête ne trouve rien, il retourne false.
                    if (!$pdo) {
                        return false;
                    }
                    $row = $pdo->fetch(PDO::FETCH_ASSOC);
                    if($row === false){return false;}
                    return new Bids(
                        owner_id: (int)$row['owner'],
                        item_id:  (int)$row['item'],
                        created_at: new DateTime($row['created_at']),
                        amount: (float) $row['amount'],
                        verif_regle_metier: false
                    );
                }catch (PDOException $e) {
                    return false;
                }
            }
        }
        // 2) Si à la fin de la période d'enchères aucune
        //    offre n'a atteint le prix d'achat immédiat,
        //    l'annonce est remportée par l'offre la plus élevée.
        // présuposse l'annonce a un système d'achat immédiat + enchère.

        if($this->has_auction() && !$this->has_buy_now()){
            if( $this->first_bid_for_starting_bid_only() instanceof Bids){
                return $this->first_bid_for_starting_bid_only();
            }
            return false;
        }
        if($this->has_buy_now() && !$this->has_auction()){
            if($this->first_bid_for_buy_price_now_only() instanceof Bids){return $this->first_bid_for_buy_price_now_only();}
            return false;
        }
        return false;
    }
    public function should_close(): bool {

        // 1) Clôture à la fin de la période.
        /*
        Si l’annonce a les deux systèmes (enchère + buy now) et que le temps est passé.
         */
        if ($this->time_has_passed()) {
            return true;
        }

        // 2) Si l'annonce a les deux systèmes :
        if($this->has_auction() && $this->has_buy_now()){
            /*
            La première enchère qui atteint ou dépasse
            le prix d'achat immédiat remporte l'annonce et clôture les enchères.
             */
            if($this->get_first_winning_buy_now_bid() instanceof Bids){
                return true;
            }
            return false;
        }

//     // 3) Clôture à la fin de la période si c’est une enchère seulement et que le temps est écoulé.
//        if ($this->has_auction() && !$this->has_buy_now() && $this->time_has_passed()) {
//            return true;
//        } => est déjà géré par la première condition.
        // 4) Clôture à la fin de la période si c'est un achât immédiat seulement et que le temps est écoulé.
        //    Une annonce expirée est clôturée même si invendue.
        if($this->has_buy_now() && !$this->has_auction()){
            // Si l'annonce a seulement un système d'achat immédiat, dès qu'une personne,
            // achéte le produit, il faut clôturer l'annonce même si le temps n'est pas écoulée.
            if($this->first_bid_for_buy_price_now_only() instanceof Bids){
                return true;
            }
            return false;

        }
        return false;
    }


    /*
     Si l'annonce a un système d'enchère + un système d'achât immédiat alors =>
     Récupère la première enchère ayant atteint ou dépassé le prix d'achat immédiat
     pour l'annonce et si égalité,on prend la plus ancienne.
     */
    function get_first_winning_buy_now_bid():bool|Bids{
        // Sans try/catch, une requête SQL peut échouer et le code continuer comme si de rien n’était.
        try {
            $pdo = self::execute("SELECT b.* FROM bids b
                                JOIN items i ON b.item = i.id
                                WHERE b.item = :itemId 
                                AND i.buy_now_price IS NOT NULL 
                                AND i.buy_now_price > 0
                                AND i.starting_bid > 0   
                                AND i.starting_bid IS NOT NULL  
                                AND b.amount >= i.buy_now_price
                                ORDER BY b.created_at ASC
                                LIMIT 1",
                array(
                    "itemId"=> $this->get_id(),
                ));
            // Si la requête ne trouve rien, il retourne false.
            if (!$pdo) {
                return false;
            }
            $row = $pdo->fetch(PDO::FETCH_ASSOC);
            // Création de l'objet avec les données récupérées.
            if($row === false){return false;}
            return new Bids(
                owner_id: (int)$row['owner'],
                item_id:  (int)$row['item'],
                created_at: new DateTime($row['created_at']),
                amount: (float) $row['amount'],
                verif_regle_metier: false
            );
        } catch (PDOException $e) {
            return false;
        }
    }


    // Pour savoir si la durée de la mise enchère est écoulée ou pas.
    public function time_has_passed():bool{
//        Date de fin = created_at + duration_days.
//        $end_date = clone $this->get_created_at();
//        $end_date->modify("+{$this->get_duration_days()} days");
        $now = new DateTime(AppTime::get_current_datetime());
        if($now > $this->get_end_at()){return true;}
        return false;
    }

    // Selectionne l'offre sur une annonce (qui a juste un système d'achât immédiat),
    // et dont le moment est égale ou supérieur à $this->by_price_now.
    public function first_bid_for_buy_price_now_only():bool|Bids{
        try{
        $pdo = self::execute("SELECT b.* FROM bids b
                                JOIN items i ON b.item = i.id
                                WHERE b.item = :itemId 
                                AND i.buy_now_price IS NOT NULL 
                                AND i.buy_now_price > 0
                                AND (i.starting_bid = 0 OR i.starting_bid IS NULL)   
                                AND b.amount >= i.buy_now_price
                                ORDER BY b.created_at ASC
                                LIMIT 1",
            array(
                "itemId"=> $this->get_id(),
            ));
        // Si la requête ne trouve rien, il retourne false.
        if (!$pdo) {
            return false;
        }
        $row = $pdo->fetch(PDO::FETCH_ASSOC);
        // Création de l'objet avec les données récupérées.
        if($row === false){return false;}
        return new Bids(
            owner_id: (int)$row['owner'],
            item_id:  (int)$row['item'],
            created_at: new DateTime($row['created_at']),
            amount: (float) $row['amount'],
            verif_regle_metier: false
        );
        } catch (PDOException $e) {
            return false;
        }
    }

    // Selectionne l'offre sur une annonce (qui a juste un système d'enchère),
    // et dont le moment est égale ou supérieur à $this->by_price_now.
    public function first_bid_for_starting_bid_only():bool|Bids
    {
        try {
            $pdo = self::execute("SELECT b.* FROM bids b
                                JOIN items i ON b.item = i.id
                                WHERE b.item = :itemId 
                                AND i.starting_bid IS NOT NULL 
                                AND i.starting_bid > 0
                                AND (i.buy_now_price= 0 OR i.buy_now_price IS NULL)   
                                AND b.amount >= i.starting_bid
                                ORDER BY b.created_at ASC
                                LIMIT 1",
                array(
                    "itemId" => $this->get_id(),
                ));
            // Si la requête ne trouve rien, il retourne false.
            if (!$pdo) {
                return false;
            }
            $row= $pdo->fetch(PDO::FETCH_ASSOC);
            // Création de l'objet avec les données récupérées.
            if($row ===false){return false;}
            return new Bids(
                owner_id: (int)$row['owner'],
                item_id:  (int)$row['item'],
                created_at: new DateTime($row['created_at']),
                amount: (float) $row['amount'],
                verif_regle_metier: false
            );
        } catch (PDOException $e) {
            return false;
        }
    }
    /* =========================================================
      Gestion des images.
    ========================================================= */
    /*
    Vérifie si l'image respecte les formats données par les règles métiers
    en toute sécurité.
    Reçois en paramètre $_FILES['picture'].
     */
    public function check_format_picture($file):bool{
        // 1. On récupère le chemin temporaire du fichier
        $tmp_name = $file['tmp_name'];
        // 2. On crée une ressource FileInfo
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        // 3. On extrait le vrai type MIME
        $mime_type = $finfo->file($tmp_name);
        // 4. On définit les formats autorisés
        $allowed_types = ["image/jpeg", "image/png", "image/gif", "image/webp"];
        if(in_array($mime_type, $allowed_types)){
            return true;
        }
        return false;
    }
    /*
    Vérifie si l'image respecte les règles métiers.
    Reçois en paramètre $_FILES['picture'].
     */
    public function validate_picture(array $file):bool|array{
        $error = array();
        if(isset($file['name']) && $file['name'] != ''){
            if($file['error']== 0){
                if($this->check_format_picture($file)){
                    if($file['size'] <=Configuration::get("picture_size_max")){
                        return $this->create_picture( $file); // On retourne true si tout c'est bien passé.
                    }else{
                        $error['image_size'] = "La taille de l'image est trop grande.";
                    }
                }else{
                    $error['format'] = "Format non autorisé.";
                }
            }else{
                $error['upload'] = "Problème lors du chargement de l'image.";
            }
        }else{
            $error["fichier_transmis"] = "Pas d'image transmise.";
        }
        if(empty($error)){
            return true;
        }
        return $error;
    }

    /*
    Crée les images si la vérification  l'image s'est bien passée.
    Reçois en paramètre $_FILES['picture'].
    */
    private function create_picture(Array $file): bool {
        // 1. Définir le répertoire de base.
        $upload_dir = "uploads/items/".$this->get_id()."/";

        // 2. Créer le dossier s'il n'existe pas encore.
        if (!file_exists($upload_dir)) {
            // mkdir crée le dossier, 0755 sont les permissions standards, true permet de créer les dossiers parents.
            mkdir($upload_dir, 0755, true);
        }

        // 3. Nettoyer le nom de fichier.
        // On récupère le nom sans l'extension originale et on enlève les caractères spéciaux.
        $original_name = pathinfo($file['name'], PATHINFO_FILENAME);
        $clean_name = preg_replace('/[^A-Za-z0-9\-]/', '_', $original_name); // Remplace tout sauf lettres/chiffres par _
        $filename_dest_path = $clean_name . ".jpg";
        $filename_thumb_path =  $clean_name ."_thumbnail.jpg";

        // 4. Chemin complet de destination.
        $dest_path = $upload_dir . $filename_dest_path;
        $thumb_path = $upload_dir . $filename_thumb_path;

        // 5. Création des deux versions :
        // Version Principale (1080px/1080px).
        $success1 = $this->process_and_save_image(
            $file['tmp_name'],
            $dest_path,
            Configuration::get("picture_dim_max_vr_princiaple")
        );
        // Version Vignette (360px/360px).
        $success2 = $this->process_and_save_image(
            $file['tmp_name'],
            $thumb_path,
            Configuration::get("picture_dim_max_vr_vignette")
        );
        if($success1 && $success2){
            if($this->update_picture($dest_path)){
                return true;
            }
        }
        return false;
    }

    // Modifie le chemin et l'enregistre dans la BDD.
    public function update_picture(string $dest_path):bool{
        if($this->set_picture_path($dest_path)){
            return true;
        }
        return false;
    }
    /*
     Redimensionne, compresse et convertit une image en JPEG.
     */
    private function process_and_save_image($tmp_name, $dest_path, $max_size, $quality = 80):bool {
        // 1. Récupérer les dimensions et le type.
        list($width, $height, $type) = getimagesize($tmp_name);


        // 2. Calculer les nouvelles dimensions en gardant le ratio.
        //      Déterminer si on veut un carré parfait (pour les vignettes uniquement)
        $is_thumbnail = (strpos($dest_path, '_thumbnail') !== false);
        if ($is_thumbnail) {
            // Logique de recadrage au centre pour un carré parfait
            $new_width = $max_size;
            $new_height = $max_size;
            $src_size = min($width, $height);
            $src_x = ($width - $src_size) / 2;
            $src_y = ($height - $src_size) / 2;
            $src_w = $src_size;
            $src_h = $src_size;
        }else{
            if ($width > $height) {
                $ratio = $height / $width;
                $new_height = (int)($ratio * $max_size);
                $new_width  = $max_size;
            } else {
                $ratio = $width / $height;
                $new_width = (int)($ratio * $max_size);
                $new_height  = $max_size;
            }
            $src_x = 0; $src_y = 0;
            $src_w = $width; $src_h = $height;
        }

        if (!extension_loaded('gd')) {
            throw new Exception("Extension GD manquante : active php-gd (imagecreatefromjpeg indisponible).");
        }
        // 3. Créer la ressource source selon le format d'origine.
        //    Création de l'image
        switch ($type) {
            case IMAGETYPE_JPEG: $source = imagecreatefromjpeg($tmp_name); break;
            case IMAGETYPE_PNG:  $source = imagecreatefrompng($tmp_name); break;
            case IMAGETYPE_GIF:  $source = imagecreatefromgif($tmp_name); break;
            case IMAGETYPE_WEBP: $source = imagecreatefromwebp($tmp_name); break;
            default: return false;
        }

        // 4. Créer l'image de destination.
        $destination = imagecreatetruecolor($new_width, $new_height);

        // Gestion de la transparence pour PNG/WEBP (évite le fond noir avant conversion JPEG).
        imagefill($destination, 0, 0, imagecolorallocate($destination, 255, 255, 255));

        // 5. Redimensionnement fluide.
        imagecopyresampled($destination, $source, 0, 0, $src_x, $src_y, $new_width, $new_height, $src_w, $src_h);

        // 6. Sauvegarde en JPEG (compression incluse).
        //    // Utilisation des coordonnées x, y et tailles source pour le crop.
        $result = imagejpeg($destination, $dest_path, $quality);

        // Libération mémoire
        imagedestroy($source);
        imagedestroy($destination);

        return $result;
    }

//    public function save ($owner,$created_at){
//
//        if($this->validate()){
//
//            $this->persist( $this->title , $this->description,$this->owner_id,$this->created_at,$this->duration_days ,$this->buy_now_price, $this->starting_bid );
//        }
//        else{
//            return $this->errors;
//        }
//    }

    public static function get_user_by_id($item_id):bool|Items{
        try {
            $pdo = self::execute("Select * from items where id = :id",
                array(
                    "id" => $item_id
                ));
            if (!$pdo) {
                return false;
            }
            $row = $pdo->fetch(PDO::FETCH_ASSOC);
            if($row === false){return false;}
            return new Items(
                id: (int)$row['id'],
                title: (string)$row['title'],
                description: (string)$row['description'] ?? "",
                owner_id: (int)$row['owner'],
                created_at: new DateTime($row['created_at']),
                buy_now_price: (float)$row['buy_now_price'] ?? "",
                duration_days: (int)$row['duration_days'] ,
                starting_bid: (float)$row['starting_bid'] ?? "",
            );
        }catch (PDOException $e) {
            return false;
        }
    }
    public function delete()
    {
        return self::execute(
            "DELETE FROM items WHERE id = :id",
            [
                "id" => $this->get_id()
            ]
        );
    }
    
    public function save2 ($owner,$created_at){

        $this->persist( $this->title , $this->description,$owner,$created_at, $this->buy_now_price, $this->duration_days , $this->starting_bid );
    }
        // Mets à jours les informations dans la BDD.
        public function save():bool {
            try{
                $pdo = self::execute("
                            UPDATE items
                            SET
                                title = :title,
                                description = :description,
                                owner = :owner,
                                created_at = :created_at,
                                buy_now_price = :buy_now_price,
                                duration_days = :duration_days,
                                starting_bid = :starting_bid
                            WHERE id = :id",
                    array(
                        "title" => $this->get_title(),
                        "description" => $this->get_description(),
                        "owner" => $this->get_owner_id(),
                        "created_at" => $this->get_created_at()->format("Y-m-d H:i:s"),
                        "buy_now_price" => $this->get_buy_now_price(),
                        "duration_days" => $this->get_duration_days(),
                        "starting_bid" => $this->get_starting_bid(),
                        "id" => $this->get_id(),
                    )
                );
                if (!$pdo) {return false;}
                return true;
            }catch (PDOException $e) {
                return false;
            }
        }
    // Mets à jours les informations dans la BDD.
    
    public function add_item(): bool {
        try {
            $pdo = self::execute("
                INSERT INTO items (
                    title,
                    description,
                    owner,
                    created_at,
                    buy_now_price,
                    duration_days,
                    starting_bid
                ) VALUES (
                    :title,
                    :description,
                    :owner,
                    :created_at,
                    :buy_now_price,
                    :duration_days,
                    :starting_bid
                )",
                array(
                    "title" => $this->get_title(),
                    "description" => $this->get_description(),
                    "owner" => $this->get_owner_id(),
                    "created_at" => $this->get_created_at()->format("Y-m-d H:i:s"),
                    "buy_now_price" => $this->get_buy_now_price(),
                    "duration_days" => $this->get_duration_days(),
                    "starting_bid" => $this->get_starting_bid(),
                )
            );
    
            if (!$pdo) {
                return false;
            }
    
            return true;
    
        } catch (PDOException $e) {
            return false;
        }
    }
    public static function create_empty_item(int $owner_id): ?Items {
        try {
            $pdo = self::execute(
                "INSERT INTO items (owner,title)
                 VALUES (:owner,:titre)",
                [
                    "owner" => $owner_id,
                    "titre" => self::lastInsertId()+1
                ]
            );
    
            if (!$pdo) {
                return null;
            }
    
            // Récupération de l'id auto-incrémenté
            $id = self::lastInsertId();
            

            self::execute(
                "UPDATE items
                SET title = :title
                WHERE id = :id",
                [
                    "title" => "item_" . $id,
                    "id"    => $id
                ]
            );
            // Création de l'instance Item avec champs vides
            return new Items(
                id: $id,
                title: "",
                description: null,
                owner_id: $owner_id,
                created_at: new DateTime(AppTime::get_current_datetime()),
                buy_now_price: null,
                duration_days: 0,
                starting_bid: null,
                verif_regle_metier:false
            );
    
        } catch (PDOException $e) {
            return null;
        }
    }
    public function item_setParams(
        string $title,
        ?string $description,
        int $duration,
        int $item_id,
        ?float $starting_bid,
        ?float $buy_now_price
    ): void {
        $this->title = $title;
        $this->description = $description;
        $this->duration_days = $duration;
        $this->id = $item_id;
        $this->starting_bid = $starting_bid;
        $this->buy_now_price = $buy_now_price;
    }
    
    
    
    // Enregistre les données d'une offre dans la BDD.
    // Création d'un nouvel objet dans la BDD.
    public static function persist(
        string $title,
        string $description,
        int $owner_id,
        DateTime $created_at,
        ?float $buy_now_price,
        int $duration_days,
        ?float $starting_bid
    ): bool {
        try {
            $pdo = self::execute(
                "INSERT INTO items (title, description, owner, created_at, buy_now_price, duration_days, starting_bid)
                    VALUES (:title, :description, :owner, :created_at, :buy_now_price, :duration_days, :starting_bid)",
                array(
                    "title" => $title,
                    "description" => $description,
                    "owner" => $owner_id,
                    "created_at" => $created_at->format("Y-m-d H:i:s"),
                    "buy_now_price" => $buy_now_price,   // null autorisé
                    "duration_days" => $duration_days,
                    "starting_bid" => $starting_bid
                )
            );
            if (!$pdo) {return false;}
            return true;
        }catch (PDOException $e) {
            return false;
        }
        
    }

    public static function get_user_item_picture_path_and_priority(int $item_id): array
{
    $pdo = self::execute(
        "SELECT item,picture_path, priority
         FROM item_pictures
         WHERE item = :item_id
         ORDER BY priority ASC",
        [
            "item_id" => $item_id
        ]
    );

    if (!$pdo) {
        return [];
    }

    $rows = $pdo->fetchAll(PDO::FETCH_ASSOC);
    if (!$rows) {
        return [];
    }

    $pictures = [];

    foreach ($rows as $row) {
        $pictures[] = new Item_pictures(item: (int)$row["item"],
         priority: (int)$row["priority"],
            picture_path: (string)$row["picture_path"],
            verif_regle_metier: false
        );
    }

    return $pictures;
}

    /* =========================================================
        My items.
     ========================================================= */
    /*
    * Récupère un item par son ID et retourne un objet Items.
    */
    public static function get_Item_instance_by_id(int $id): Items|bool
    {
        $pdo = self::execute(
            "SELECT *
             FROM items
             WHERE id = :id",
            [
                "id" => $id
            ]
        );
        if(!$pdo){return false;}
        $item = $pdo->fetch(PDO::FETCH_ASSOC);
        if ($item === false) {
            return false;
        }  
        return new Items(
                title:          (string)$item["title"],
                description:    (string)$item["description"],
                created_at:     new DateTime($item["created_at"]),
                duration_days:  (int)$item["duration_days"],
                owner_id:       (int)$item["owner"],
                buy_now_price:  (float)$item["buy_now_price"],
                starting_bid:   (float)$item["starting_bid"],
                id:             (int)$item["id"],
                verif_regle_metier: false
            );
        
    }

    public static function get_active_items_by_owner(int $owner):bool|array{
        try {
            $pdo = self::execute(
                "SELECT v.*, ip.picture_path
             FROM v_items_status v
             LEFT JOIN item_pictures ip ON ip.item = v.id AND ip.priority = 1
             WHERE v.owner = :owner
               AND v.end_at > :now         -- Temps non écoulé
               AND v.buy_now_reached = 0   -- Prix d'achat immédiat non atteint
             ORDER BY v.end_at ASC",
                [
                    "owner" => $owner,
                    "now"   => AppTime::get_current_datetime()
                ]
            );

            if (!$pdo) return false;
            $rows = $pdo->fetchAll(PDO::FETCH_ASSOC);
            if($rows === false){return false;}
            $list_items = [];
            foreach ($rows as $item) {
                $list_items[] = new Items(
                    title:          (string)$item["title"],
                    description:    (string)$item["description"],
                    created_at:     new DateTime($item["created_at"]),
                    duration_days:  (int)$item["duration_days"],
                    owner_id:       (int)$item["owner"],
                    buy_now_price:  $item["buy_now_price"] !== null ? (float)$item["buy_now_price"] : null,
                    starting_bid:   $item["starting_bid"] !== null ? (float)$item["starting_bid"] : null,
                    id:             (int)$item["id"],
                    verif_regle_metier: false
                );
            }
            return $list_items;
        } catch (PDOException $e) {
            return false;
        }

    }

    public static function get_closed_unsold_items_by_owner(int $owner): array
    {
        try {
            $pdo = self::execute(
                "SELECT v.*, ip.picture_path
             FROM v_items_status v
             LEFT JOIN item_pictures ip
                ON ip.item = v.id
               AND ip.priority = 1 -- Simplification si priority 1 est toujours la principale
             WHERE v.owner = :owner
               AND v.end_at <= :now
               AND v.has_bids = 0
               AND v.buy_now_reached = 0
             ORDER BY v.end_at ASC",
                [
                    "owner" => $owner,
                    "now"   => AppTime::get_current_datetime()
                ]
            );

            if (!$pdo) return []; // Retourne un tableau vide pour respecter le type de retour

            $rows = $pdo->fetchAll(PDO::FETCH_ASSOC);
            $list_items = [];

            foreach ($rows as $row) {
                $list_items[] = new Items(
                    title:          (string)$row["title"],
                    description:    (string)$row["description"],
                    created_at:     new DateTime($row["created_at"]),
                    duration_days:  (int)$row["duration_days"],
                    owner_id:       (int)$row["owner"],
                    buy_now_price:  $row["buy_now_price"] !== null ? (float)$row["buy_now_price"] : null,
                    starting_bid:   $row["starting_bid"] !== null ? (float)$row["starting_bid"] : null,
                    id:             (int)$row["id"],
                    verif_regle_metier: false
                );
            }
            return $list_items;

        } catch (PDOException $e) {
            return [];
        }
    }

    public static function get_sold_items_by_owner(int $owner): array
    {
        try {
            $pdo = self::execute(
                "SELECT v.*, ip.picture_path
             FROM v_items_status v
             LEFT JOIN item_pictures ip
                ON ip.item = v.id
               AND ip.priority = 1 -- Les consignes utilisent la priorité 1 pour la photo principale
             WHERE v.owner = :owner
               AND (
                    v.buy_now_reached = 1
                    OR (v.end_at <= :now AND v.has_bids = 1)
               )
             ORDER BY v.end_at ASC",
                [
                    "owner" => $owner,
                    "now"   => AppTime::get_current_datetime()
                ]
            );

            if (!$pdo) return []; // Retourne un tableau vide en cas d'échec

            $rows = $pdo->fetchAll(PDO::FETCH_ASSOC);
            $list_items = [];

            foreach ($rows as $row) {
                $list_items[] = new Items(
                    title:          (string)$row["title"],
                    description:    (string)$row["description"],
                    created_at:     new DateTime($row["created_at"]),
                    duration_days:  (int)$row["duration_days"],
                    owner_id:       (int)$row["owner"],
                    buy_now_price:  $row["buy_now_price"] !== null ? (float)$row["buy_now_price"] : null,
                    starting_bid:   $row["starting_bid"] !== null ? (float)$row["starting_bid"] : null,
                    id:             (int)$row["id"],
                    verif_regle_metier: false
                );
            }
            return $list_items;

        } catch (PDOException $e) {
            return [];
        }
    }
    /* =========================================================
        My items.
     ========================================================= */

    /* =========================================================
        v_items_status.
    ========================================================= */

    // Initialise les attributs liés à v_items_status.
    public function init_v_items_status():void{
        try {
            $pdo = self::execute("SELECT * FROM  v_items_status
                                      WHERE id = :id
                                ",
                array(
                    "id" => $this->get_id(),
                ));
            // Si la requête ne trouve rien, il retourne false.
            if (!$pdo) {
                throw new InvalidArgumentException("Problème lié à v_items_status");
            }
            $row = $pdo->fetch(PDO::FETCH_ASSOC);
            if($row ===false){throw new InvalidArgumentException("Problème lié à v_items_status");}
            $this->end_at = !empty($row['end_at']) ? new DateTime($row['end_at']): null;
            $this->bid_count = (float)$row['bid_count'];
            $this->max_bid =  $row['max_bid'] == null ? null : (float) $row['max_bid'];
            $this->is_direct_sale = (bool) $row['is_direct_sale'];
            $this->is_auction = (bool) $row['is_auction'];
            $this->has_buy_now = (bool) $row['has_buy_now'];
            $this->has_bids = (bool) $row['has_bids'];
            $this->buy_now_reached = (int) $row['buy_now_reached'];
            $this->not_purchased_direct_sale = (bool) $row['not_purchased_direct_sale'];
        } catch (PDOException $e) {
            throw new InvalidArgumentException("Problème lié à v_items_status");
        }
    }

    /* =========================================================
       v_items_status.
   ========================================================= */

    /* =========================================================
        Browse items.
    ========================================================= */

    // Obtenir les items auquelles l'utilisateur ne participent pas.
    public static function other_available_items($user_id){
        try {
            $pdo = self::execute("
               select DISTINCT * 
               from items i1
               JOIN v_items_status s1 ON i1.id = s1.id
               WHERE i1.id NOT IN (
                    SELECT i2.id FROM bids b2
                    JOIN items i2 ON b2.item = i2.id
                    WHERE b2.owner = :user_id
                    )
               ORDER BY s1.end_at ASC",
                array(
                    "user_id" => $user_id,
                ));
            // Si la requête ne trouve rien, il retourne false.
            if (!$pdo) {
                return false;
            }
            $row = $pdo->fetchall(PDO::FETCH_ASSOC);
            if ($row === false) {
                return false;
            }
            $list_items = array();
            foreach($row as $item){
                $list_items [] = new Items(
                    title:          (string)$item["title"],
                    description:    (string)$item["description"],
                    created_at:     new DateTime($item["created_at"]),
                    duration_days:  (int)$item["duration_days"],
                    owner_id:       (int)$item["owner"],
                    buy_now_price:  (float)$item["buy_now_price"],
                    starting_bid:   (int)$item["starting_bid"],
                    id:             (int)$item["id"],
                    verif_regle_metier: false
                );
            }
            return $list_items;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Récupére les images (leurs chemins) d'une item.
    public function get_picture_path_item():bool|array{
        try {
            $item_pictures = array();
            $web_root = "http://localhost/prwb_2526_f09/";
            $pdo = self::execute("SELECT * FROM  item_pictures
                                      WHERE item= :item
                                ",
                array(
                    "item" => $this->get_id(),
                ));
            // Si la requête ne trouve rien, il retourne false.
            if (!$pdo) {
                return false;
            }
            $array= $pdo->fetchall(PDO::FETCH_ASSOC);
            if($array === false){return false;}
            foreach($array as $row){
//                $texte = "Bonjour tout le monde";
//                $mot_a_supprimer = "tout ";
//                $resultat = str_replace($mot_a_supprimer, "", $texte);
//                echo $resultat; // Affiche "Bonjour le monde"
                // _thumbnail.jpg
                $resultat = str_replace( ".jpg", "", $web_root.$row['picture_path']);
                // $item_pictures [] =  $web_root.$resultat."_thumbnail.jpg";
                $item_pictures [] =  $web_root.$row['picture_path'];
            }
            return $item_pictures;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Obtenir le nom du propriètaire d'une annonce.
    public function get_owner_pseudo():string{
        $name = User::get_user_by_id($this->owner_id)->get_pseudo();
        return $name;
    }

    // Donne l'enchère dont le mountant est le plus élevé sur une annonce.
    public function get_highest_bidder():bool|Bids{
        try {
            $pdo = self::execute("SELECT b.* FROM bids b
                                JOIN items i ON b.item = i.id
                                WHERE i.starting_bid IS NOT NULL 
                                AND i.starting_bid > 0   
                                AND b.amount >= i.starting_bid
                               	AND b.item = :item_id
                                ORDER BY b.amount DESC, b.created_at ASC
                                LIMIT 1
                                ",
                array(
                    "item_id" => $this->get_id()
                ));
            // Si la requête ne trouve rien, il retourne false.
            if (!$pdo) {
                return false;
            }
            $row = $pdo->fetch(PDO::FETCH_ASSOC);
            if ($row === false) {
                return false;
            }
            return new Bids(
                owner_id: (int)$row['owner'],
                item_id:  (int)$row['item'],
                created_at: new DateTime($row['created_at']),
                amount: (float) $row['amount'],
                verif_regle_metier: false
            );
        } catch (PDOException $e) {
            return false;
        }
    }
    // Pour obtenir le nombre de temps restant avant la cloture d'une annonce au format string.
    // Source - https://stackoverflow.com/a/18602474
    // Posted by Glavić, modified by community. See post 'Timeline' for change history
    // Retrieved 2026-02-03, License - CC BY-SA 4.0
    function time_elapsed_string($short_format = true): string {
        $now = new DateTime(AppTime::get_current_datetime());
        $future = $this->get_end_at();

        // Si la date est passée, on peut retourner 0 ou un message
        if ($now > $this->get_end_at()) {
            return "Closed";
        }

        $diff = $now->diff($future);

        // Calcul des semaines et jours restants pour PHP 8.2+
        $parts = [
            'y' => $diff->y,
            'm' => $diff->m,
            'w' => floor($diff->d / 7),
            'd' => $diff->d % 7,
            'h' => $diff->h,
            'i' => $diff->i,
            's' => $diff->s,
        ];

        // Format court (ex: 2d 4h left)
        if ($short_format) {
            $output = [];
            if ($parts['y']) $output[] = $parts['y'] . 'y';
            if ($parts['m']) $output[] = $parts['m'] . 'mo';
            if ($parts['w']) $output[] = $parts['w'] . 'w';
            if ($parts['d']) $output[] = $parts['d'] . 'd';
            if ($parts['h']) $output[] = $parts['h'] . 'h';
            if ($parts['i']) $output[] = $parts['i'] . 'm';

            // On ne garde que les 2 premières unités pour rester compact comme sur l'image
            $result = array_slice($output, 0, 2);
            return implode(' ', $result) . ' left';
        }

        // Format long (ex: 2 days, 4 hours left)
        $labels = [
            'y' => 'year', 'm' => 'month', 'w' => 'week', 'd' => 'day',
            'h' => 'hour', 'i' => 'minute', 's' => 'second'
        ];

        foreach ($labels as $k => &$v) {
            if ($parts[$k]) {
                $v = $parts[$k] . ' ' . $v . ($parts[$k] > 1 ? 's' : '');
            } else {
                unset($labels[$k]);
            }
        }

        return implode(', ', $labels) . ' left';
    }

    /* =========================================================
        Browse items.
    ========================================================= */

    /* =========================================================
        Open item.
    ========================================================= */

    public function get_bid_history():bool|array{
        try {
            $pdo = self::execute("SELECT *
                                      FROM bids
                                      WHERE item = :item_id
                                      ORDER BY created_at DESC;
                                ",
                array(
                    "item_id" => $this->get_id()
                ));
            // Si la requête ne trouve rien, il retourne false.
            if (!$pdo) {
                return false;
            }
            $list = $pdo->fetchall(PDO::FETCH_ASSOC);
            if ($list=== false) {
                return false;
            }
            $list_bids = array();
            foreach($list as $row){
                $list_bids[]=
                    new Bids(
                        owner_id: (int)$row['owner'],
                        item_id:  (int)$row['item'],
                        created_at: new DateTime($row['created_at']),
                        amount: (float) $row['amount'],
                        verif_regle_metier: false
                    );
            }
            return $list_bids;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function get_owner_user():User{
        return User::get_user_by_id($this->get_owner_id());
    }
    /* =========================================================
        Open item.
    ========================================================= */
}


