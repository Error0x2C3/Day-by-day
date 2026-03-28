<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <base href="<?= $web_root ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/navigation.css">
    <link rel="stylesheet" href="css/profile.css">

</head>

<body class="bg-dark text-light">
<?php
echo Navigation::top_bar("profile","Profile");
?>
<main class=" p-3">
<div class="page-content py-2">

    <!-- PROFILE CARD -->
    <div class="card bg-secondary text-center mb-4">
        <div class="card-body">
        <img src="<?php if(isset($user)): echo $user->get_picture_path(); ?><?php endif;?>"
                     class="rounded-circle mb-3"
                     alt="Profile picture">
                <h5 class="mb-0"><?php if(isset($user)):echo $user->get_full_Name(); ?><?php endif;?></h5>
                <small class="text-light opacity-75"><?php if(isset($user)):echo $user->get_pseudo(); ?><?php endif;?></small>
            <div class="text-muted"><?php if(isset($user)):echo $user->get_email(); ?><?php endif;?></div>
        </div>
    </div>

    <!-- MY ACTIVITIES -->
    <div class="card bg-secondary mb-4">
        <div class="card-header fw-bold">
            My Activities
        </div>
        <div class="card-body">
            <?php
            $btn_back = Navigation::sanitize("profile/profile_view/");
            ?>
            <a href="profile/sales_view/<?=$btn_back?>" class="btn btn-outline-primary w-100 text-start mb-3">
                <i class="bi bi-receipt me-2"></i>
                <strong>Sales</strong><br>
                <small class="text-muted">Items you have sold</small>
            </a>
            <?php
            $btn_back = Navigation::sanitize("profile/profile_view/");
            ?>
            <a href="profile/purchases_view/" class="btn btn-outline-primary w-100 text-start">
                <i class="bi bi-cart me-2"></i>
                <strong>Purchases</strong><br>
                <small class="text-muted">Items you have purchased</small>
            </a>

        </div>
    </div>

    <!-- ACCOUNT SETTINGS -->
    <div class="card bg-secondary">
        <div class="card-header fw-bold">
            Account Settings
        </div>
        <div class="card-body">
            <?php
            $btn_back = Navigation::sanitize("profile/profile_view/");
            ?>
            <a href="profile/profil_edit_view/<?=$btn_back?>" class="btn btn-outline-primary w-100 text-start mb-3">
                <i class="bi bi-person-gear me-2"></i>
                <strong>Edit Profile</strong><br>
                <small class="text-muted">Update your personal information</small>
            </a>
            <?php
            $btn_back = Navigation::sanitize("profile/profile_view/");
            ?>
            <a href="profile/profile_change_password_view/<?=$btn_back?>" class="btn btn-outline-primary w-100 text-start mb-3">
                <i class="bi bi-shield-lock me-2"></i>
                <strong>Change Password</strong><br>
                <small class="text-muted">Update your account security</small>
            </a>
            <?php
            $btn_back = Navigation::sanitize("profile/profile_view/");
            ?>
            <a href="profile/manage_profile_picture_view/<?=$btn_back?>" class="btn btn-outline-primary w-100 text-start mb-3">
                <i class="bi bi-image me-2"></i>
                <strong>Profile Picture</strong><br>
                <small class="text-muted">Upload or change your profile picture</small>
            </a>

            <a href="profile/logout_user" class="btn btn-outline-danger w-100 text-start">
                <i class="bi bi-box-arrow-right me-2"></i>
                <strong>Logout</strong><br>
                <small class="text-muted">Sign out of your account</small>
            </a>

        </div>
    </div>

</div>

<!-- FOOTER NAV -->
<?php
require_once 'view/view_bottom_bar.php';
?>

</body>
</html>
