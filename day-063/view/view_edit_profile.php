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
    <link rel="stylesheet" href="css/profile_edit.css">

</head>

<body class="bg-dark text-light">

<?php
echo Navigation::top_bar("profile/profile_edit_view","Edit profile",$btn ?? "profile/profile_view","edit-profil-form");
?>

<div class="container my-4">
    <form id="edit-profil-form" method="POST" action="profile/save_edit_profil">
        <!-- PERSONAL INFORMATION -->
        <div class="card bg-secondary mb-4">
            <div class="card-header fw-bold">
                Personal Information
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Full Name *</label>
                    <input type="text" name="full_name" class="form-control bg-dark text-light"
                        value=<?php echo $user->get_full_Name() ?>  >
                    <div class="form-text text-muted">
                        Your complete name as it should appear on your profile
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Username *</label>
                    <input type="text" name="username" class="form-control bg-dark text-light"
                        value=<?php echo $user->get_pseudo() ?>>
                    <div class="form-text text-muted">
                        This will be your public display name on the platform
                    </div>
                </div>

            </div>
        </div>

        <!-- CONTACT & PAYMENT -->
        <div class="card bg-secondary">
            <div class="card-header fw-bold">
                Contact & Payment
            </div>
            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label fw-bold">Email Address *</label>
                    <input type="email" name="email" class="form-control bg-dark text-light"
                        value=<?php echo $user->get_email() ?>>
                    <div class="form-text text-muted">
                        We'll use this email to send you notifications about your sales and purchases
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">
                        IBAN <span class="text-muted">(Optional)</span>
                    </label>
                    <input type="text" name="iban" class="form-control bg-dark text-light"
                        value=<?php echo $user->get_iban() ?>>
                    <div class="form-text text-muted">
                        For receiving payments from your sales. Leave empty if you don't want to receive payments directly.
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<!-- FOOTER NAV -->
<?php
require_once 'view/view_bottom_bar.php';
?>
</body>
</html>
