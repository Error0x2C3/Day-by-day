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
    <div class="login-card p-4"> <h5 class="text-center mb-3">Sign up</h5>
        <hr class="border-secondary my-3">

        <form method="post" action="Signup/signup">

            <div class="mb-3">
                <label class="form-label small mb-1">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0">
                        <i class="bi bi-envelope"></i> </span>
                    <input type="email" class="form-control border-start-0" id="Email" name="Email" placeholder="example@epfc.eu" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small mb-1">Full Name</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0">
                        <i class="bi bi-person"></i> </span>
                    <input type="text" class="form-control border-start-0" id="Full_name" name="Full_name" placeholder="Full Name" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small mb-1">Pseudo</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0">
                        <i class="bi bi-people"></i> </span>
                    <input type="text" class="form-control border-start-0" id="pseudo" name="pseudo" placeholder="Pseudo" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small mb-1">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0">
                        <i class="bi bi-key"></i> </span>
                    <input type="password" class="form-control border-start-0" id="password" name="password" placeholder="••••••••" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small mb-1">Confirm your password</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0">
                        <i class="bi bi-key-fill"></i> </span>
                    <input type="password" class="form-control border-start-0" id="confirm_password" name="confirm_password" placeholder="••••••••" required>
                </div>
            </div>

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