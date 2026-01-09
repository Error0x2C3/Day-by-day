<?php

require_once "framework/Model.php";

class Items extends Model {

    public function __construct(
        // public ?type $nom_variable => peut-être nullabe.
        public String $title,
        public ?String $description,
        public DateTime $created_at,
        public int $duration_days,
        public int $owner_id,
        public ?float $buy_now_price,
        public ?float $starting_bid,
        public int $id=0,
    ) {
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
    public function get_starting_bid(): ?int{
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
            $this->buy_now_price= $price;
            return true;
        }
        return false;
    }

    /* =========================================================
        Respect des règle métiers.
    ========================================================= */
    public function validate_title(string $title): bool{
        if(strlen($title) >= Configuration::get("title_item_min")){
            $pdo = self::execute("Select * from items where owner = :owner",
                array(
                    "owner"=>$this->get_owner_id()
                ));
            $array = $pdo->fetchAll(PDO::FETCH_ASSOC);
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
            if(!strlen($description)>= Configuration::get("description_item_min")){
                return false;
            }else{return true;}
        }
        return true;
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
    public function  validate_bid_or_buy_now_presence(): bool{
        // S'il y a pas d'enchère => achat immédiat obligatoire.
        if(!$this->has_auction() && !$this->has_buy_now()){
            return false;
        }
        return true;
    }
    public function validate_buy_now_price(float $value):bool{
        /*
        NULL => Pas d’enchères possibles valide.
        0    => Pas d’enchères possibles valide.
        Starting_bid > 0 Enchères autorisées valide sous condition.
        Starting_bid < 0 Invalides / Interdit non valide.
        Si starting_bid == null => l'annonce doit avoir un buy_now_price.
        */
        if(!$this->validate_bid_or_buy_now_presence()){return false;}
        if($value > 0){
            // Si c'est > 0, alors doit respecter cette régle.
            if(preg_match(Configuration::get("decimal_regles"),(string)$value)) {
                /*
                si elle est remplie, doit être strictement supérieure à 0
                et strictement supérieur à starting_bid (si starting_bid n'est pas nulle)
                 */
                if($this->has_auction() && $value <= $this->get_starting_bid()){
                    return false;
                }
                return true;
            }
            return false;
        }
        if($value < 0){return false;}
        return true;
    }

    // Vérifie que duration_days est entre 1 et 365
    public function validate_duration_days(int $value): bool{
        return $this->get_duration_days() >= Configuration::get("duration_days_min") && $this->get_duration_days() <= Configuration::get("duration_days_max");
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
            $bid = new bids($owner_id,$this->get_id(),new DateTime(),$amount);
            return $bid;
        }
        return false;
    }

    public function validate() : bool{
        $erro1 = false;
        $erro2 = false;
        // Validation du titre
        if (strlen($this->title) >= Configuration::get("title_min_length") && strlen($this->title) <= Configuration::get("title_max_length")) {
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
        if($this->has_auction() && $this->has_buy_now() && !$this->time_has_passed()){
            if($this->getFirstWinningBuyNowBid() instanceof Bids && $this->has_buy_now()){
                // On renvoie l'offre gagnante.
                return $this->getFirstWinningBuyNowBid();
            }
            return false;
        }
        // 2) Si à la fin de la période d'enchères aucune
        //    offre n'a atteint le prix d'achat immédiat,
        //    l'annonce est remportée par l'offre la plus élevée.
        // présuposse l'annonce a un système d'achat immédiat + enchère.
        if($this->has_auction() && $this->has_buy_now() && $this->time_has_passed() && !$this->getFirstWinningBuyNowBid()){
            // Récupére l'enchère la plus élevée pour cette annonce.
            // la plus grosse enchère, et si égalité, la plus ancienne.
            try{
                $pdo = self::execute("SELECT b.* FROM bids b
                                JOIN items i ON b.item = i.id
                                WHERE b.item = :itemId 
                                AND i.buy_now_price IS NOT NULL 
                                AND i.buy_now_price > 0
                                AND i.starting_bid > 0   
                                ORDER BY b.amount ASC,b.created_at ASC
                                LIMIT 1",
                    array(
                        "itemId"=> $this->get_id(),
                    ));
                // Si la requête ne trouve rien, il retourne false.
                if (!$pdo) {
                    return false;
                }
                $array = $pdo->fetch(PDO::FETCH_ASSOC);
                return $array['owner'];
            }catch (PDOException $e) {
                return false;
            }
        }
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
        if ($this->time_has_passed()) {
            return true;
        }

        // 2) Si l'annonce a les deux systèmes :
        if($this->has_auction() && $this->has_buy_now()){
            /*
            La première enchère qui atteint ou dépasse
            le prix d'achat immédiat remporte l'annonce et clôture les enchères.
             */
            if($this->getFirstWinningBuyNowBid() instanceof Bids){
                return true;
            }
            return false;

        }

        // 3) Clôture à la fin de la période si c’est une enchère seulement et que le temps est écoulé.
        if ($this->has_auction() && !$this->has_buy_now() && $this->time_has_passed()) {
            return true;
        }
        // 4) Clôture à la fin de la période si c'est un achât immédiat seulement et que le temps est écoulé
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
    function getFirstWinningBuyNowBid():bool|Bids{
        // Sans try/catch, une requête SQL peut échouer et le code continuer comme si de rien n’était.
        try {
            $pdo = self::execute("SELECT b.* FROM bids b
                                JOIN items i ON b.item = i.id
                                WHERE b.item = :itemId 
                                AND i.buy_now_price IS NOT NULL 
                                AND i.buy_now_price > 0
                                AND i.starting_bid > 0   
                                AND b.amount >= i.buy_now_price
                                ORDER BY b.amount ASC,b.created_at ASC
                                LIMIT 1",
                array(
                    "itemId"=> $this->get_id(),
                ));
            // Si la requête ne trouve rien, il retourne false.
            if (!$pdo) {
                return false;
            }
            $array = $pdo->fetch(PDO::FETCH_ASSOC);
            // Création de l'objet avec les données récupérées.
            return new Bids(
                $array['owner'],
                $array['item'],
                $array['created_at'],
                $array['amount']
            );
        } catch (PDOException $e) {
            return false;
        }
    }


    // Pour savoir si la durée de la mise enchère est écoulée ou pas.
    public function time_has_passed():bool{
        // Date de fin = created_at + duration_days.
        $end_date = clone $this->get_created_at();
        $end_date->modify("+{$this->get_duration_days()} days");
        $now = new DateTime();
        if($now >= $end_date){return true;}
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
        $array = $pdo->fetch(PDO::FETCH_ASSOC);
        // Création de l'objet avec les données récupérées.
        return new Bids(
            $array['owner'],
            $array['item'],
            $array['created_at'],
            $array['amount']
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
                                AND b.amount >= i.buy_now_price
                                ORDER BY b.created_at ASC
                                LIMIT 1",
                array(
                    "itemId" => $this->get_id(),
                ));
            // Si la requête ne trouve rien, il retourne false.
            if (!$pdo) {
                return false;
            }
            $array = $pdo->fetch(PDO::FETCH_ASSOC);
            // Création de l'objet avec les données récupérées.
            return new Bids(
                $array['owner'],
                $array['item'],
                $array['created_at'],
                $array['amount']
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
        $filename_thumb_path =  $clean_name ."_thumbnail".".jpg";

        // 4. Chemin complet de destination.
        $dest_path = $upload_dir . $filename_dest_path;
        $thumb_path = $upload_dir . "_thumbnail" . $filename_thumb_path;

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
    /*
     Redimensionne, compresse et convertit une image en JPEG.
     */
    private function process_and_save_image($tmp_name, $dest_path, $max_size, $quality = 80):bool {
        // 1. Récupérer les dimensions et le type.
        list($width, $height, $type) = getimagesize($tmp_name);

        // 2. Calculer les nouvelles dimensions en gardant le ratio.
        $ratio = $width / $height;
        if ($width > $height) {
            $new_width = min($width, $max_size);
            $new_height = $new_width / $ratio;
        } else {
            $new_height = min($height, $max_size);
            $new_width = $new_height * $ratio;
        }

        // 3. Créer la ressource source selon le format d'origine.
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
        imagecopyresampled($destination, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        // 6. Sauvegarde en JPEG (compression incluse).
        $result = imagejpeg($destination, $dest_path, $quality);

        // Libération mémoire
        imagedestroy($source);
        imagedestroy($destination);

        return $result;
    }

    // Modifie le chemin et l'enregistre dans la BDD.
    public function update_picture(string $dest_path):bool{
        if($this->set_picture_path($dest_path)){
            $this->save();
            return true;
        }
        return false;
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

    // Mets à jours les informations dans la BDD.
    public function save(): bool {
        try{
            $pdo = self::execute("
                        UPDATE users
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
                    "created_at" => $this->get_created_at(),
                    "buy_now_price" => $this->get_buy_now_price(),
                    "duration_days" => $this->get_duration_days(),
                    "starting_bid" => $this->get_starting_bid()
                )
            );
            if (!$pdo) {return false;}
            return true;
        }catch (PDOException $e) {
            return false;
        }
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
                    "created_at" => $created_at->format('Y-m-d H:i:s'),
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

}
