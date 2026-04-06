<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change password</title>
    <base href="<?= $web_root ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/navigation.css">
    <link rel="stylesheet" href="css/change_password.css">

</head>
<body class="bg-dark text-light">
<?php
echo Navigation::top_bar("profile/profile_change_password_view/","Change password", "profile/profile_view/","change-password-form");
?>
<main class="page-content p-3">
<form id="change-password-form" method="post" action="Profile/upload_password" id="changePasswordForm">
<div class="container my-4">

    <!-- Current password -->
    <div class="card bg-secondary text-light mb-4">
        <div class="card-header fw-bold">
            Current Password
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Current Password *
                </label>
                <input type="password" name="current_password"
                    class="form-control <?= !empty($errors['current_password']) ? 'is-invalid' : '' ?>"
                    placeholder="Enter your current password" required>
                <?php if (!empty($errors['current_password'])): ?>
                    <div class="text-danger small mt-1">
                        <?= htmlspecialchars($errors['current_password']) ?>
                    </div>
                <?php else: ?>
                    <div class="form-text text-light">
                        Enter your current password to verify your identity
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- New password -->
    <div class="card bg-secondary text-light">
        <div class="card-header fw-bold">
            New Password
        </div>
        <div class="card-body">

            <div class="mb-3">
                 <label class="form-label fw-semibold">
                    New Password *
                </label>
                <input type="password" name="new_password"
                    class="form-control <?= !empty($errors['new_password']) ? 'is-invalid' : '' ?>"
                    placeholder="Enter your new password" required>
                <?php if (!empty($errors['new_password'])): ?>
                    <div class="text-danger small mt-1">
                        <?= htmlspecialchars($errors['new_password']) ?>
                    </div>
                <?php else: ?>
                    <div class="form-text text-light">
                        Password must be 8+ characters with uppercase, lowercase, number, and special character
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Confirm New Password *
                </label>
                <input type="password" name="confirm_password"
                    class="form-control <?= !empty($errors['confirm_password']) ? 'is-invalid' : '' ?>"
                    placeholder="Confirm your new password" required>
                <?php if (!empty($errors['confirm_password'])): ?>
                    <div class="text-danger small mt-1">
                        <?= htmlspecialchars($errors['confirm_password']) ?>
                    </div>
                <?php else: ?>
                    <div class="form-text text-light">
                        Re-enter your new password to confirm it matches
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

</form>
<main>
<!-- FOOTER NAV -->
<?php
require_once 'view/view_bottom_bar.php';
?>
</body>

</html>
