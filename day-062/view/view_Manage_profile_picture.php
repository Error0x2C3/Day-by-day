<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage profile picture</title>

    <!-- Bootstrap 5 -->
    <base href="<?= $web_root ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/navigation.css">
    <link rel="stylesheet" href="css/manage_profile_picture.css">

</head>
<body class="bg-dark text-light">
<?php
echo Navigation::top_bar("profile/manage_profile_picture_view","Manage Profile picture","historynav/manage_btn_back/back","manage-picture-profile");
?>
<main class="page-content p-3">
    <div class="container py-4">
        <!-- Current profile picture -->
        <div class="card bg-secondary text-light mb-4">
            <div class="card-header fw-bold">
                Current Profile Picture
            </div>
            <div class="card-body text-center">
                <p ><?php echo $user->get_picture_path();?></p>
                <img src="<?php if(isset($user)):echo $user->get_picture_path(); ?><?php endif;?>"
                     class="rounded-circle mb-3"
                     width="140"
                     height="140"
                     alt="Profile picture">

                <h5 class="mb-0"><?php if(isset($user)):echo $user->get_full_name(); ?><?php endif;?></h5>
                <small class="text-light opacity-75">@<?php if(isset($user)):echo $user->get_pseudo() ?><?php endif;?></small>
            </div>
        </div>

        <!-- Upload new picture -->
        <div class="card bg-secondary text-light mb-4">
            <div class="card-header fw-bold">
                Upload New Picture
            </div>
            <div class="card-body">
            <form id="manage-picture-profile" action="<?php echo $web_root . 'profile/upload_profile_picture'; ?>"
                      method="post"
                      enctype="multipart/form-data">

                    <div class="mb-3">
                        <label for="profile_picture" class="form-label">
                            Select Image <span class="text-danger">*</span>
                        </label>
                        <input type="file"
                               class="form-control"
                               id="profile_picture"
                               name="profile_picture"
                               accept="image/png,image/jpeg,image/gif,image/webp"
                               required>
                    </div>

                    <p class="text-light opacity-75">
                        Supported formats: JPG, PNG, GIF, WebP.<br>
                        This will replace your current profile picture.
                    </p>

                    <button type="submit" class="btn btn-primary">
                        Upload Picture
                    </button>
                </form>
            </div>
        </div>

        <!-- Remove picture -->
        <div class="card border-warning bg-dark text-light">
            <div class="card-header text-warning fw-bold">
                Remove Current Picture
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    This will permanently delete your current profile picture.
                    You can upload a new one anytime.
                </div>

                <form action="Profile/delete_profile_picture" method="post">
                    <button type="submit" class="btn btn-danger">
                        Delete Picture
                    </button>
                </form>
            </div>
        </div>

    </div>
<main >
<!-- FOOTER NAV -->
<?php
require_once 'view/view_bottom_bar.php';
//echo Navigation::bottom_nav("profile",$user ?? null);
//echo Navigation::bottom_bar();
//?>

</body>
</html>
