<?php
require_once "framework/Model.php";
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'model/Role.php';
class User extends Model {
    public function __construct(
        // public ?type $nom_variable => peut-être nullabe.
        public string $full_Name,
        public string $email,
        public string $pseudo,
        public string $password,
        public Role $role,
        public ?string $picture_path="",
        public ?string $iban="",
        public int $id=0,
    ) {
    }


    public function get_email(): string {
        return $this->email;
    }
    public function get_password(): string {
        return $this->password;
    }
    public function get_full_Name(): string {
        return $this->full_Name;
    }
    public function get_pseudo(): string {
        return $this->pseudo;
    }
    public function get_role(): Role {
        return $this->role;
    }
    public function get_picture_path(): ?string {
        return $this->picture_path;
    }
    public function get_iban(): ?string {
        return $this->iban;
    }
    public function get_id(){
        return $this->id;
    }

    public function set_full_name(String $full_name): bool {
        if( self::validate_full_name($full_name)){
            $this->full_Name = $full_name;
            return true;
        }
        return false;
    }
    public function set_email($email): bool {
        if(self::validate_mail($email)){
            $this->email = $email;
            return true;
        }
        return false;
    }
    public function set_pseudo(string $pseudo): bool{
        if(self::validate_pseudo($pseudo)){
            $this->pseudo = $pseudo;
            return true;
        }
        return false;
    }
    public function set_password(string $password): bool{
        if(self::validate_password($password)){
            $this->password = $password;
            return true;
        }
        return false;
    }
    public function set_role(Role $role): bool{
        if(self::validate_role($role)){
            $this->role = $role;
            return true;
        }
        return false;
    }
    public function set_picture_path(string $picture_path):bool{
        if(self::validate_picture_path($picture_path)){
            $this->picture_path = $picture_path;
            return true;
        }
        return false;
    }
    public function set_iban(string $iban): bool{
        if(self::validate_iban($iban)){
            $this->iban = $iban;
            return true;
        }
        return false;
    }
    public static function getUserById(int $id): User|bool {
        $pdo = self::execute(
            "SELECT * FROM users WHERE id = :id",
            [
                "id" => $id
            ]
        );
    
        $user = $pdo->fetch(PDO::FETCH_ASSOC);
    
        if ($user !== false) {
            return new User(
                full_Name:  $user["full_name"] ?? "",
                email:      $user["email"],
                pseudo:     $user["pseudo"] ?? "",
                password:   $user["password"],
                role:       $user["role"] === "admin" ? Role::Admin : Role::User,
                picture_path: $user["picture_path"] ?? "",
                iban:         $user["iban"] ?? "",
                id:           $user["id"]
            );
        }
    
        return false;
    }


    /*
     * Vérifie si l'utilisateur n'existe pas déjà.
     */
    public static function is_exists(string $email) : bool{
        $pdo = self::execute("Select * from users where email = :email",
            array(
                "email"=>$email
            ));
        $user = $pdo->fetch(PDO::FETCH_ASSOC);
        if($user !== false){
            return true;
        }
     return false;
    }

    /*
     * Crée une instance User correspondant à celui de l'utilisateur qui vient de ce connecter.
     */
    public static function get_instance_user(string $email, string $password): User|bool {
        $pdo = self::execute("Select * from users where email = :email",
            array(
                "email"=>$email
            ));
        $user = $pdo->fetch(PDO::FETCH_ASSOC);

        // if (password_hash($passwordEntre, PASSWORD_DEFAULT) === $hashDansLaBDD) --> tjrs faux.
        /*
         password_hash() génère un nouveau hash à chaque appel, même si le mot de passe est le même,
         parce qu’il ajoute automatiquement :
            un sel aléatoire (salt)
            éventuellement d’autres paramètres (coût, algo, etc.)
         */
        if($user !== false &&  password_verify($password, $user["password"]) == true){
            return new User(
                full_Name:  $user["full_name"] ?? "",
                email: $user["email"],
                pseudo: $user["pseudo"] ?? "",
                password: $user["password"],
                role: $user["role"] ==  Role::from("user") ? Role::User : Role::Admin,
                picture_path: $user["picture_path"] ?? "",
                iban: $user["iban"] ?? "",
                id : $user["id"],
            );
        }
        return $user;
    }


    /* =========================================================
       Respect des règle métiers.
   ========================================================= */

    /*
     * Vérifie si le full_name vérifie bien les règles métiers.
     */
    public static function validate_full_name($full_Name): bool {
        // Minimum 3 caractères.
        if(strlen($full_Name) >= Configuration::get("full_name_carac_min") ){
            return true;
        }
        return false;
    }

    /*
     * Vérifie si le mail vérifie bien les règles métiers.
     */
    public static function validate_mail($email): bool {
        // Doit avoir le format d'une adresse mail valide ; il doit être unique.
         $pdo = self::execute("Select * from users where email = :email",
            array(
                "email"=>$email
            ));
        $user = $pdo->fetch(PDO::FETCH_ASSOC);
        if(isset($user["email"]) && $user["email"] == $email){return false;}
        
        if(filter_var($email, FILTER_VALIDATE_EMAIL) ){
            return true;
        }
        return false;
    }

    /*
     * Vérifie si le pseudo vérifie bien les règles métiers.
     */
    public static function validate_pseudo($pseudo): bool {
        // Doit avoir une longueur comprise entre 3 et 30 caractères et doit être unique.
        $pdo = self::execute("Select * from users where pseudo = :pseudo",
        array(
            "pseudo"=>$pseudo
        ));
        $user = $pdo->fetch(PDO::FETCH_ASSOC);
        if(isset($user["pseudo"]) && $user["pseudo"] == $pseudo){return false;}
        if(strlen($pseudo) >= Configuration::get("pseudo_carac_min") && strlen($pseudo) <= Configuration::get("pseudo_carac_max") ){
            return true;
        }
        return false;
    }

    /*
     * Vérifie si le mot de passe vérifie bien les règles métiers.
     */
    public static function validate_password($password): bool {
        /*
         *Doit contenir la version hachée du mot de passe de l'utilisateur.
         *ce dernier doit avoir au minimum une longueur de 8 caractères, 
         *doit contenir au moins un chiffre, une lettre majuscule, 
         *une lettre minuscule et un caractère non alphanumérique. 
        */
        if(strlen($password) >= 8 && preg_match('/[0-9]/',$password) == true && preg_match('/[A-Z]/', $password) == true && preg_match('/[a-z]/', $password) == true && preg_match('/[^a-zA-Z0-9]/', $password) == true ){
            return true;
        }
        return false;
    }

    /*
     * Vérifie si le mot de passe vérifie bien les règles métiers.
     */
    public static function validate_role($role): bool {
        /*
         * Contient le rôle de l'utilisateur (admin, user).  
         */
         if(Role::from($role) == Role::Admin || Role::from($role) == Role::User){
            return true;
         }
        return false;
    }

    /*
     * Vérifie si l'iban vérifie bien les règles métiers.
     */
    public static function validate_iban($iban): bool {
        // Si elle est remplie, doit avoir un format IBAN de forme BE99 9999 9999 9999.
        if((preg_match('/^BE[0-9]{2} (?:[0-9]{4} ){2}[0-9]{4}$/', $iban)) && strlen($iban) > 0){
            return true;
        }
        if(strlen($iban) == 0){
            return true;
        }
        return false;
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

    /*
     * Vérifie si le login, password et iban respectent bien les contraintes métiers lors de l'inscription.
    */
    public static function validate(string $full_name,string $email,string $pseudo, string $password): array{
        $error =array();
        if( !(User::validate_full_name($full_name)) ){
            $error["full_name"] = "Full name is invalid !";
        }
        if( !(User::validate_mail($email)) ){
            $error["email"]= "Mail must be greater than tree !";
        }
        if( !(User::validate_pseudo($pseudo)) ){
           $error["pseudo"] = "Pseudo is invalid !";

        }
        if( !(User::validate_password($password)) ){
            $error["password"] = "Password is invalid !";
            $error["password_confirmation"] = "Password confirmation is invalid !";
        }
        return $error;
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
        $upload_dir = "uploads/user/".$this->get_id()."/";

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


    // Enregistre les données d'un utilisateur qui vient de s'inscrire dans la BDD.
    // Création d'un nouvel objet dans la BDD.
    public static  function persist($full_name, $email, $pseudo, $password): bool{
        $picture_path ="";
        $iban ="";
        $role ="user";
        // Sans try/catch, une requête SQL peut échouer et le code continuer comme si de rien n’était.
        try {
        self::execute("INSERT INTO users (full_name, email, pseudo, password, role,picture_path,iban)
                                  VALUES (:full_name,:email,:pseudo,:password,:role,:picture_path,:iban)",
            array(
                "full_name"=> $full_name,
                "email"=>$email,
                "pseudo"=>$pseudo,
                "password"=>password_hash($password, PASSWORD_BCRYPT),
                "role"=> $role,
                "picture_path"=>$picture_path,
                "iban"=>$iban
            ));
        return true;
        } catch (PDOException $e) {
            // ici tu peux tester si c'est une erreur de contrainte UNIQUE (email/pseudo)
            return false;
        }
    }

    // Mets à jours les informations dans la BDD.
    public function save(): bool {
        try{
            return self::execute("
            UPDATE users
            SET
                full_name = :full_name,
                email = :email,
                pseudo = :pseudo,
                password = :password,
                role = :role,
                picture_path = :picture_path,
                iban = :iban
            WHERE id = :id",
                    array(
                        "full_name"    => $this->full_name,
                        "email"        => $this->email,
                        "pseudo"       => $this->pseudo,
                        "password"     => password_hash($this->password, PASSWORD_BCRYPT),
                        "role"         => $this->role,
                        "picture_path" => $this->picture_path,
                        "iban"         => $this->iban,
                        "id"           => $this->id
                    )
                );
        }catch (PDOException $e) {
            return false;
        }
    }
}
