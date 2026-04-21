<?php
require_once 'functions.php';
$pseudo = '';
$password = '';

if (isset($_POST['pseudo']) && isset($_POST['password'])) //note : pourraient contenir
                                                          //des chaînes vides
{
    $pseudo = sanitize($_POST['pseudo']);
    $password = sanitize($_POST['password']);

    $member = get_member($pseudo);
    if($member){
        if(password_verify($password, $member['password'])){
            log_user($pseudo);
        } else {
            $error = "Wrong password. Please try again.";
        }
    } else {
        $error = "Can't find a member with the pseudo '$pseudo'. Please sign up.";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Log In</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title">Log In</div>
        <div class="menu">
            <a href="index.php">Home</a>
            <a href="signup.php">Sign Up</a>
        </div>
        <div class="main">
            <form action="login.php" method="post">
                <table>
                    <tr>
                        <td>Pseudo:</td>
                        <td><input id="pseudo" name="pseudo" type="text" value="<?php echo $pseudo; ?>"></td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td><input id="password" name="password" type="password" value="<?php echo $password; ?>"></td>
                    </tr>
                </table>
                <input type="submit" value="Log In">
            </form>
            <?php
            if (isset($error))
                echo "<div class='errors'><br><br>$error</div>";
            ?>
        </div>
    </body>
</html>
