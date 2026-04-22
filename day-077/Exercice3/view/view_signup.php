<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sign Up</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        <script>
            let pseudo, errPseudo, password, errPassword, passwordConfirm, errPasswordConfirm;
            
            document.onreadystatechange = function(){
                if(document.readyState === 'complete') {
                    pseudo = document.getElementById("pseudo");
                    errPseudo = document.getElementById("errPseudo");
                    password = document.getElementById("password");
                    errPassword = document.getElementById("errPassword");
                    passwordConfirm = document.getElementById("passwordConfirm");
                    errPasswordConfirm = document.getElementById("errPasswordConfirm");
                }
            };
            
            function checkPseudo(){
                let ok = true;
                errPseudo.innerHTML = "";
                if(!(/^.{3,16}$/).test(pseudo.value)){
                    errPseudo.innerHTML += "<p>Pseudo length must be between 3 and 16.</p>";
                    ok = false;
                }
                if(pseudo.value.length > 0 && !(/^[a-zA-Z][a-zA-Z0-9]*$/).test(pseudo.value)){
                    errPseudo.innerHTML += "<p>Pseudo must start by a letter and must contain only letters and numbers.</p>";  
                    ok = false;
                }
                return ok;
            }
            
            function checkPassword(){
                let ok = true;
                errPassword.innerHTML = "";
                const hasUpperCase = /[A-Z]/.test(password.value);
                const hasNumbers = /\d/.test(password.value);
                const hasPunct = /['";:,.\/?\\-]/.test(password.value);
                if(!(hasUpperCase && hasNumbers && hasPunct)){
                    errPassword.innerHTML += "<p>Password must contain one uppercase letter, one number and one punctuation mark.</p>";
                    ok = false;
                }
                if(!(/^.{8,16}$/).test(password.value)){
                    errPassword.innerHTML += "<p>Password length must be between 8 and 16.</p>";
                    ok = false;
                }
                return ok;
            }
            
            function checkPasswords(){
                let ok = true;
                errPasswordConfirm.innerHTML = "";
                if(password.value !== passwordConfirm.value){
                    errPasswordConfirm.innerHTML += "<p>You have to enter twice the same password.</p>";
                    ok = false;
                }
                return ok;
            }
            
            function checkAll(){
                // les 3 lignes ci-dessous permettent d'éviter le shortcut
                // par rapport à checkPseudo()&&checkPassword()&&checkPasswords();
                let ok = checkPseudo();
                ok = checkPassword() && ok;
                ok = checkPasswords() && ok;
                return ok;
            }   
        </script>
    </head>
    <body>
        <div class="title">Sign Up</div>
        <div class="menu">
            <a href="index.php">Home</a>
        </div>
        <div class="main">
            Please enter your details to sign up :
            <br><br>
            <form id="signupForm" action="main/signup" method="post" onsubmit="return checkAll();">
                <table>
                    <tr>
                        <td>Pseudo:</td>
                        <td><input id="pseudo" name="pseudo" type="text" size="16" oninput='checkPseudo();' value="<?= $pseudo ?>"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="errors" id="errPseudo"></td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td><input id="password" name="password" type="password" size="16" oninput='checkPassword();' value="<?= $password ?>"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="errors" id="errPassword"></td>
                    </tr>
                    <tr>
                        <td>Confirm Password:</td>
                        <td><input id="passwordConfirm" name="password_confirm" size="16" oninput='checkPasswords();' type="password" value="<?= $password_confirm ?>"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="errors" id="errPasswordConfirm"></td>
                    </tr>
                </table>
                <input id="btn" type="submit" value="Sign Up">
            </form>
            <?php if (count($errors) != 0): ?>
                <div class='errors'>
                    <p>Please correct the following error(s) :</p>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </body>
</html>