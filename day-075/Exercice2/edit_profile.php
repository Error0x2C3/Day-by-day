<?php
    require_once "functions.php";
    check_login();
    
    $profile = '';
    $picture_path = '';
    

    
    if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != ''){
        if($_FILES['image']['error']==0){
            
            $typeOK = TRUE;
            
            if($_FILES['image']['type']=="image/gif")
                $saveTo = $user.".gif";
            else if($_FILES['image']['type']=="image/jpeg")
                $saveTo = $user.".jpg";
            else if($_FILES['image']['type']=="image/png")
                $saveTo = $user.".png";
            else {
                $typeOK = FALSE;
                $error = "Unsupported image format : gif, jpeg ou png !";
            }
            
            if($typeOK){
                move_uploaded_file($_FILES['image']['tmp_name'], $saveTo);
                if(update_member($user, NULL, $saveTo)){
                    $success = "Your profile has been successfully updated.";
                    
                }
            }
        } else {
            $error = "Error while uploading file.";
        }
    }
    
    if(isset($_POST['profile'])){
        $profile = sanitize($_POST['profile']);  
        if(update_member($user,$profile,NULL)){
            $success = "Your profile has been successfully updated."; 
        } 
    }
    
    $member = get_member($user);
    $profile = $member['profile'];
    $picture_path = $member['picture_path'];

?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $user;?>'s Profile</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title">Update Your Profile</div>
        <?php include('menu.html'); ?>
        <div class="main">
            <form method='post' action='edit_profile.php' enctype='multipart/form-data'>
                <p>Enter or edit your details and/or upload an image.</p>
                <textarea name='profile' cols='50' rows='3'><?php echo $profile; ?></textarea><br><br>

                Image: <input type='file' name='image' accept="image/x-png, image/gif, image/jpeg"><br><br>
                <image src='<?php echo $picture_path; ?>' width="100" alt="Profile image"><br><br>

                <input type='submit' value='Save Profile'>
            </form>
            <?php
            if(isset($success))
                echo "<p><span class='success'>$success</span></p>";
            if(isset($error))
                echo "<p><span class='errors'>$error</span></p>"
            ?>
        </div>
    </body>
</html>

