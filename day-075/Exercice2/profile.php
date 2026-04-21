<?php
require_once "functions.php";
check_login();

if(isset($_GET["pseudo"])){
    $pseudo = sanitize($_GET["pseudo"]);
}
else {
    $pseudo = $user;
}

$profile = get_member($pseudo);

if(!$profile){
    abort("Can't find user '$pseudo' in the database.");
}
else {
    $description = $profile["profile"];
    $picture_path = $profile["picture_path"];
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $pseudo; ?>'s Profile!</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title"><?php echo $pseudo; ?>'s Profile!</div>
        <?php include('menu.html'); ?>
        <div class="main">
            <?php
            if(!$description){
                echo 'No profile string entered yet!';
            } else {
                echo $description;
            }
            ?>
            <br><br>
            <?php
            if(!$picture_path){
                echo 'No picture loaded yet!';
            } else {
                echo "<image src='$picture_path' width='100' alt='$pseudo&apos;s photo!'>";
            }
            ?>
        </div>
    </body>
</html>
