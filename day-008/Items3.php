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
    public function get_starting_bid(): ?int
    {
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
    public function get_id(): int
    {
        return $this->id;
    }

    public function set_title($title): bool{
        if($this->validate_title($title)){
            return true;
        }
        return false;
    }

    /* =========================================================
        Respect des règle métiers.
    ========================================================= */
    public function validate_title($title): bool{
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

    public function validate_description($description): bool{
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
        Starting_bid < 0 Ivalides / Interdit non valide.
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
        if($this->get_starting_bid() == null ){}
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
        return $this->has_buy_now();
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

    public function should_close(?float $highest_bid): bool {
        // Date de fin = created_at + duration_days
        $end_date = clone $this->get_created_at();
        $end_date->modify("+{$this->get_duration_days()} days");
        $now = new DateTime();

        // 1) Clôture à la fin de la période si c’est une enchère.
        if ($this->has_auction() && $now >= $end_date) {
            return true;
        }

        // 2) Si l'annonce a les deux systèmes :
        if($this->has_auction() && $this->has_buy_now()){
            /*
            La première enchère qui atteint ou dépasse
            le prix d'achat immédiat remporte l'annonce et clôture les enchères.
             */

        }
        // 3) clôture par buy-now si une enchère atteint buy_now
        if ($this->has_buy_now() && $highest_bid !== null && $highest_bid >= $this->buy_now_price) {
            return true;
        }

        // 4) si c’est uniquement buy-now (sans enchères), tu peux décider :
        // - soit jamais de clôture automatique sans achat
        // - soit une clôture à l’échéance (à définir selon ton énoncé)
        return false;
    }


    /*
     Récupère la première enchère ayant atteint ou dépassé le prix d'achat immédiat
     pour une annonce donnée ($itemId).
     */
    function getFirstWinningBuyNowBid(int $itemId):bool|Bids{

        // Sans try/catch, une requête SQL peut échouer et le code continuer comme si de rien n’était.
        try {
            $pdo = self::execute("SELECT b.* FROM bids b
                                JOIN items i ON b.item = i.id
                                WHERE b.item = :itemId 
                                AND i.buy_now_price IS NOT NULL 
                                AND i.buy_now_price > 0
                                AND b.amount >= i.buy_now_price
                                ORDER BY b.created_at ASC
                                LIMIT 1",
                array(
                    "itemId"=> $itemId,
                ));
            // Si fetch() ne trouve rien, il retourne false
            if (!$pdo) {
                return false;
            }
            $array = $pdo->fetch(PDO::FETCH_ASSOC);
            // Création de l'objet avec les données récupérées
            return new Bids(
                $array['owner'],
                $array['item'],
                $array['created_at'],
                $array['amount']
            );
        } catch (PDOException $e) {
            // ici tu peux tester si c'est une erreur de contrainte UNIQUE (email/pseudo)
            return false;
        }
    }

// Exemple d'utilisation :
// $winner = getFirstWinningBuyNowBid($pdo, 42);
// if ($winner) {
//     echo "L'annonce est clôturée par l'offre de " . $winner['amount'] . " €";
// }


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

    public function save ($owner,$created_at){

        if($this->validate()){

            $this->persist( $this->title , $this->description,$this->owner_id,$this->created_at,$this->duration_days ,$this->buy_now_price, $this->starting_bid );
        }
        else{
            return $this->errors;
        }
    }


    public static function persist(
        string $title,
        string $description,
        int $owner_id,
        DateTime $created_at,
        ?float $buy_now_price,
        int $duration_days,
        ?float $starting_bid
    ): bool {

        self::execute(
            "INSERT INTO items (title, description, owner, created_at, buy_now_price, duration_days, starting_bid)
         VALUES (:title, :description, :owner, :created_at, :buy_now_price, :duration_days, :starting_bid)",
            [
                "title" => $title,
                "description" => $description,
                "owner" => $owner_id,
                "created_at" => $created_at->format('Y-m-d H:i:s'),
                "buy_now_price" => $buy_now_price,   // null autorisé
                "duration_days" => $duration_days,
                "starting_bid" => $starting_bid      // null autorisé
            ]
        );

        return true;
    }

}
