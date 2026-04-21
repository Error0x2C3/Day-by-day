<?php
    require_once("functions.php");
    $pdo = connect();
    $pseudo = '';
    $password = '';
    $password_confirm = '';
    $regex_password = "#^(?=.*[A-Z])(?=.*\d)(?=.*['\";:,.\/?\\\\-])[A-Za-z\d'\" ;:,.\/?\\\\-]{8,16}$#";
    $regex_pseudo = "#/^[a-zA-Z][a-zA-Z0-9]{2,15}$#";
    if(isset($_POST['pseudo']) && isset($_POST['password']) && isset($_POST['password_confirm'])){
        $pseudo = sanitize($_POST['pseudo']);
        $password = sanitize($_POST['password']);
        $password_confirm = sanitize($_POST['password_confirm']);
        
        if (!is_pseudo_available($pseudo))
            $errors[] = "Le nom d'utilisateur existe déjà";
        if(trim($pseudo) == '')
            $errors[] = "Le pseudo est obligatoire";
        if(trim($pseudo) == '')
            $errors[] = "Le mot de passe est obligatoire.";
        if(!preg_match($regex_pseudo, $pseudo)  && trim($pseudo) !='')
            $errors[] = "Le pseudo être au bon format.";
        if(!preg_match($regex_password, $password) && trim($password) !='')
            $errors[] = "Le mot de passe doit être au bon format.";
        if($password != $password_confirm && trim($password) !='')
            $errors[] = "Les mots de passe doivent être identiques";
        
        if(!isset($errors)){
            add_member($pseudo,$password);
            log_user($pseudo);
        }
    }
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Sign Up</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, init
        ial-scale=1.0">
        <link href="styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title">Sign Up</div>
        <div class="menu">
            <a href="index.php">Home</a>
        </div>
        <div class="main">
            Please enter your details to sign up :
            <br><br>
            <form action="signup.php" method="post">
                <table>
                    <tr>
                        <td>Pseudo:</td>
                        <td><input id="pseudo" name="pseudo" type="text" value="<?php echo $pseudo; ?>"  onchange="checkPseudo(this);" ><br></td>
                         <td id="errPseudo"></td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td><input id="password" name="password" type="password" value="<?php echo $password; ?>" onchange="checkPassword(this);"><br></td>
                        <td id="errPassword"></td>
                    </tr>
                    <tr>
                        <td>Confirm Password:</td>
                        <td><input id="password_confirm" name="password_confirm" type="password" value="<?php echo $password_confirm; ?>" onchange="checkPasswords(this);"><br></td>
                        <td id="errPassword_confirm"></td>
                    </tr>
                </table>
                <input type="submit" value="Sign Up" onclick="checkAll()();">
            </form>
            <?php 
                if(isset($errors)){
                    echo "<div class='errors'>
                          <br><br><p>Veuillez corriger les erreurs suivantes :</p>
                          <ul>";
                    foreach($errors as $error){
                        echo "<li>".$error."</li>";
                    }
                    echo '</ul></div>';
                } 
            ?>
        </div>
    </body>
    <script>
         // La regex pour le mot de passe :
            // ^(?=.*[A-Z])          -> Doit contenir au moins 1 majuscule.
            // (?=.*\d)              -> Doit contenir au moins 1 chiffre.
            // (?=.*['";:,.\/?\\-])  -> Doit contenir au moins 1 caractère spécial de votre liste.
            // [A-Za-z\d'" ;:,.\/?\\-]{8,16}$ -> Autorise ces caractères et impose une longueur de 8 à 16.
        const regex_password = /^(?=.*[A-Z])(?=.*\d)(?=.*['";:,.\/?\\-])[A-Za-z\d'" ;:,.\/?\\-]{8,16}$/;
         // La regex pour le pseudo :
            // [a-zA-Z]              -> Le premier caractère doit être une lettre (majuscule ou minuscule).
            // [a-zA-Z0-9]{2,15}     -> Le reste des caractères doit être composé de lettres ou de chiffres.
        const regex_pseudo = /^[a-zA-Z][a-zA-Z0-9]{2,15}$/;
        let pseudo,password,password_confirm;
        let count_err_password;
        let count_err_password_confirm;
        document.onreadystatechange = function () {
            if (document.readyState === 'complete') {
                pseudo = document.getElementById("pseudo");
                password = document.getElementById("password");
                password_confirm = document.getElementById("password_confirm");
                count_err_password = false;
                count_err_password_confirm = false;
            }
        };
        function checkAll(){
            checkPseudo(pseudo);
            checkPassword(password);
            checkPasswords(password_confirm);
            return condition_pseudo && condition_password && condition_password_confirm;
        }
        
        function checkPseudo(field){
            let condition_pseudo = regex_pseudo.test(field.value);
            pseudo = field.value;
            if(!condition_pseudo){
                document.getElementById("errPseudo").style.color="red";
                document.getElementById("errPseudo").innerHTML=" La condition n'est pas acceptée.";
                console.log("La condition n'est pas acceptée.");
                }else{
                document.getElementById("errPseudo").style.color="green";
                document.getElementById("errPseudo").innerHTML=" La condition est acceptée.";
            }
            console.log("C'est field Pseudo : "+field.value);
            return false;
        
        }

        function checkPassword(field){
            let condition_password = regex_password.test(field.value);
            pseudo = field.value;
            count_err_password = true;
            if(!condition_password ){
                document.getElementById("errPassword").style.color="red";
                document.getElementById("errPassword").innerHTML=" La condition n'est pas acceptée.";
                console.log("La condition n'est pas acceptée.");
            }else{
                document.getElementById("errPassword").style.color="green";
                document.getElementById("errPassword").innerHTML=" La condition est acceptée.";
        
            }
            return condition_password;
        }
        function checkPasswords(field){
            let condition_password_confirm = field.value === password && regex_password.test(field.value) ? true:false;
            count_err_password_confirm = true;
            if(count_err_password && count_err_password_confirm){
                if(condition_password_confirm ){
                    document.getElementById("errPassword_confirm").style.color="green";
                    document.getElementById("errPassword_confirm").innerHTML=" La condition est acceptée.";
                }else{
                    document.getElementById("errPassword_confirm").style.color="red";
                    document.getElementById("errPassword_confirm").innerHTML=" La condition n'est pas acceptée.";
                    console.log("La condition n'est pas acceptée.");
                }
            }
            return condition_password_confirm;
        }
    </script>

</html>
