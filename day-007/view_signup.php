<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>EPayFC - Sign Up</title>
    <base href="<?= $web_root ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/signup.css">
</head>

<body>
<div class="d-flex justify-content-center align-items-center logo-header">
    <i class="bi bi-cart3 fs-3 me-2"></i>
    <span class="fs-4 fw-semibold">EPayFC</span>
</div>
<div class="min-vh-100 d-flex align-items-center justify-content-center">
    <div class="login-card p-4"> <h5 class="text-center mb-1">Sign up</h5>
        <hr class="border-secondary my-3">

        <form method="post" action="Signup/signup">
            <div class="mb-1">
                <label class="form-label small mb-1">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0">
                        <i class="bi bi-envelope"></i> </span>
                    <?php if (isset($answerArray) && isset($answerArray["email"]) && $answerArray["email"] != "") : ?>
                    <input type="email" class="form-control border-start-0" id="email" name="email" placeholder="example@epfc.eu" value="<?php echo $answerArray["email"]; ?>" required>
                    <?php else : ?>
                    <input type="email" class="form-control border-start-0" id="email" name="email" placeholder="example@epfc.eu" required>
                    <?php endif; ?>
                </div>
                <?php if (isset($errorArray) && isset($errorArray["email"]) && $errorArray["email"] != "") : ?>
                    <div class="field-error">- <?= htmlspecialchars( $errorArray["email"]) ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-1">
                <label class="form-label small mb-1">Full Name</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0">
                        <i class="bi bi-person"></i> </span>
                    <?php if (isset($answerArray) && isset($answerArray["full_name"]) && $answerArray["full_name"] != "") : ?>
                    <input type="text" class="form-control border-start-0" id="full_name" name="full_name" placeholder="full_Name" value ="<?php echo  $answerArray["full_name"]; ?>" required>
                    <?php else : ?>
                    <input type="text" class="form-control border-start-0" id="full_name" name="full_name" placeholder="full_Name" required>
                    <?php endif; ?>
                </div>
                <?php if (isset($errorArray) && isset($errorArray["full_name"]) && $errorArray["full_name"] != "") : ?>
                    <div class="field-error">- <?= htmlspecialchars( $errorArray["full_name"]) ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-1">
                <label class="form-label small mb-1">Pseudo</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0">
                        <i class="bi bi-people"></i> </span>
                    <?php if (isset($answerArray) && isset($answerArray["pseudo"]) && $answerArray["pseudo"] != "") : ?>
                        <input type="text" class="form-control border-start-0" id="pseudo" name="pseudo" placeholder="pseudo" value ="<?php echo $answerArray["pseudo"]; ?>" required>
                    <?php else : ?>
                        <input type="text" class="form-control border-start-0" id="pseudo" name="pseudo" placeholder="pseudo" required>
                    <?php endif; ?>
                </div>
                <?php if (isset($errorArray) && isset($errorArray["pseudo"]) && $errorArray["pseudo"] != "") : ?>
                    <div class="field-error">- <?= htmlspecialchars( $errorArray["pseudo"]) ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-1">
                <label class="form-label small mb-1">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0">
                        <i class="bi bi-key"></i> </span>
                    <?php if (isset($answerArray) && isset($answerArray["password"]) && $answerArray["password"] != "") : ?>
                        <input type="password" class="form-control border-start-0" id="password" name="password" placeholder="••••••••" value="<?php echo $answerArray["password"];  ?> " required>
                    <?php else : ?>
                        <input type="password" class="form-control border-start-0" id="password" name="password" placeholder="••••••••" required>
                    <?php endif; ?>
                </div>
                <?php if (isset($errorArray) && isset($errorArray["password"]) &&  $errorArray["password"] != "") : ?>
                    <div class="field-error">- <?= htmlspecialchars($errorArray["password"]) ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label small mb-1">Confirm your password</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0">
                        <i class="bi bi-key-fill"></i> </span>
                    <?php if (isset($answerArray) && isset($answerArray["confirm_password"]) && $answerArray["confirm_password"] != "") : ?>
                        <input type="password" class="form-control border-start-0" id="confirm_password" name="confirm_password" placeholder="••••••••" value="<?php echo $answerArray["confirm_password"];?>" required>
                    <?php else : ?>
                        <input type="password" class="form-control border-start-0" id="confirm_password" name="confirm_password" placeholder="••••••••" required>
                    <?php endif; ?>
                </div>
                <?php if (isset($errorArray) && isset($errorArray["password_confirmation"]) && $errorArray["password_confirmation"] != "") : ?>
                    <div class="field-error">- <?= htmlspecialchars($errorArray["password_confirmation"]) ?></div>
                <?php endif; ?>
            </div>
            <?php if (isset($error) && $error != "") : ?>
                <div class="alert alert-danger d-flex align-items-center mt-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>
                        <?= htmlspecialchars($error) ?>
                    </div>
                </div>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary w-100 mb-2">Sign Up</button>
            <div>
                <a href="login" class="btn btn-danger w-100 mb-3">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>