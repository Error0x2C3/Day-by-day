<?php

session_start();


function connect(){
    $dbhost = "localhost";
    $dbname = "my_social_network_base";
    $dbuser = "root";
    $dbpassword = "root";

    try
    {
        $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
    catch (Exception $exc)
    {
        abort("Erreur lors de l'accès à la base de données.");
    }
}

function sanitize($str) : string 
{
    return trim(htmlspecialchars($str, ENT_QUOTES, "UTF-8"));
}

function redirect($url, $statusCode = 303)
{
    header('Location: ' . $url, true, $statusCode);
    die();
}

function check_login()
{
    global $user;
    if (!isset($_SESSION['user']))
        redirect('index.php');
    else
        $user = $_SESSION['user'];
}

function abort($err)
{
    global $error;
    $error = $err;
    include 'error.php';
    die;
}

function is_pseudo_available($pseudo) {
    $pdo = connect();
    try{
        $query = $pdo->prepare("SELECT * FROM Members WHERE pseudo=:pseudo");
        $query->execute(array("pseudo"=>$pseudo));
        $result = $query->fetchAll();
        return count($result) === 0;
    } catch (Exception $e){
        abort("Error while accessing database. Please contact your administrator.");
    }
}

function get_member($pseudo){
    $pdo = connect();
    try
    {
        $query = $pdo->prepare("SELECT * FROM Members where pseudo = :pseudo");
        $query->execute(array("pseudo" => $pseudo));
        $profile = $query->fetch(); // un seul résultat au maximum
    }
    catch (Exception $exc)
    {
        abort("Error while accessing database. Please contact your administrator.");
    }
    if($query->rowCount()==0){
        return false;
    }
    else{
        return $profile;
    }
}

function get_all_members(){
    $pdo = connect();
    try
    {
        $query = $pdo->prepare("SELECT pseudo FROM Members");
        $query->execute();
        $members = $query->fetchAll();
        return $members;
    }
    catch (Exception $exc)
    {
        abort("Erreur lors de l'accès à la base de données.");
    }
}

//pre : user does'nt exist yet
function add_member($pseudo, $password){
    $pdo = connect();
    try{
        $query = $pdo->prepare("INSERT INTO Members(pseudo,password)
                                        VALUES(:pseudo,:password)");
        $query->execute(array("pseudo"=>$pseudo, "password"=>password_hash($password, PASSWORD_BCRYPT)));
        return true;
    } catch (Exception $ex) {
        abort("Error while accessing database. Please contact your administrator.");
        return false;
    }
}

function update_member($pseudo, $profile, $picture_path){
    $actual = get_member($pseudo);
    if($profile == NULL)
        $profile = $actual['profile'];
    if($picture_path == NULL)
        $picture_path = $actual['picture_path'];
    $pdo = connect();
    try{
        $query = $pdo->prepare("UPDATE Members SET picture_path=:path, profile=:profile WHERE pseudo=:pseudo ");
        $query->execute(array("path"=>$picture_path,"profile"=>$profile,"pseudo"=>$pseudo));
        return true;
    } catch (Exception $ex) {
        abort("Error while accessing database. Please contact your administrator.");
        return false;
    }
    
}

function log_user($pseudo){
    $_SESSION["user"] = $pseudo;
    redirect("profile.php");
}

?>