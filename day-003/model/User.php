<?php
require_once "framework/Model.php";
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'model/Role.php';
class User extends Model {
    public function __construct(
        public string $full_Name,
        public string $email,
        public string $pseudo,
        public string $password,
        public Role $role,
        public string $picture_path="",
        public string $iban="",
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
    public function get_picture_path(): string {
        return $this->picture_path;
    }
    public function get_iban(): string {
        return $this->iban;
    }
    public function get_id(){
        return $this->id;
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
        if($user["email"] == $email){return false;}
        
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
        $pdo = self::execute("Select * from users where email = :pseudo",
        array(
            "pseudo"=>$pseudo
        ));
        $user = $pdo->fetch(PDO::FETCH_ASSOC);
        if($user["pseudo"] == $pseudo){return false;}
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
        return false;
    }
    /*
     * Vérifie si le login, password et iban respectent bien les contraintes métiers.
    */
    public static function validate_all(string $mail, string $password, string $iban): array{
        $valid = true;
        $error =[];
        if(strlen($mail) < Configuration::get("pseudo_carac_min") || strlen($mail) > Configuration::get("pseudo_carac_max") ){
            $valid = false;
            $error = "Mail must be greater than tree !";
        }
        if(strlen($password) < 8 || !(preg_match('/[0-9]/',$password)) || !(preg_match('/[A-Z]/', $password)) || !(preg_match('/[a-z]/', $password)) || !(preg_match('/[^a-zA-Z0-9]/', $password)) ){
            $valid = false;
            $error = "Password must contain the hashed version of the user's password. 
                      The password must be at least 8 characters long and must contain at least one number, 
                      one uppercase letter, one lowercase letter, and one non-alphanumeric character.!";

        }
        if( !(preg_match('/^BE[0-9]{2} (?:[0-9]{4} ){2}[0-9]{4}$/', $iban)) ){
            /*
             * ^BE → commence par BE.
             * [0-9]{2} → les 2 chiffres du contrôle → un espace.
             * (?:[0-9]{4} ) → deux groupes de 4 chiffres suivis d’un espace.
             * [0-9]{4}$ → les 4 derniers chiffres, fin de chaîne.
             */
            $valid = false;
            $error = "IBAN must be a valid iban number! ";
        }
        return [$valid,$error];
    }
}
