<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>EPayFC - Login</title>
    <base href="<?= $web_root ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
<div class="d-flex justify-content-center align-items-center logo-header">
    <i class="bi bi-cart3 fs-3 me-2"></i>
    <span class="fs-4 fw-semibold">EPayFC</span>
</div>
<div class="min-vh-100 d-flex align-items-center justify-content-center">
    <div class="login-card p-4">
        <h5 class="text-center mb-3">Sign in</h5>
        <hr class="border-secondary my-3">
        <form action="login/login_check" method="post">
            <div class="mb-3">
                <label class="form-label small mb-1">Email</label>
                <div class="input-group">
                  <span class="input-group-text bg-transparent border-end-0">
                    <i class="bi bi-person"></i>
                  </span>
                    <?php if(isset($error) && isset($errorArray)) : ?>
                        <input type="email" class="form-control border-start-0" placeholder="example@epfc.eu" name ="loginId" value="<?php echo $errorArray[0];?>">
                    <?php else : ?>
                        <input type="email" class="form-control border-start-0" placeholder="example@epfc.eu" name ="loginId" >
                    <?php endif; ?>

                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small mb-1">Password</label>
                <div class="input-group">
              <span class="input-group-text bg-transparent border-end-0 ">
                <i class="bi bi-key"></i>
              </span>
                    <?php if(isset($error) && isset($errorArray)) : ?>
                        <input type="password" class="form-control border-start-0" placeholder="••••••••" name ="loginMdp" value="<?php echo $errorArray[1]?>">
                    <?php else : ?>
                        <input type="password" class="form-control border-start-0" placeholder="••••••••" name ="loginMdp">
                    <?php endif; ?>
                </div>
            </div>
            <?php if (isset($error)) : ?>
                <div class="alert alert-danger d-flex align-items-center mt-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>
                        <?= htmlspecialchars($error) ?>
                    </div>
                </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary w-100 mb-2">Login</button>
            <div>
                <a href="login/login_connect_as_guest" class="btn btn-secondary w-100 mb-3"> Continue as guest</a>
            </div>

        </form>


        <div class="text-center mb-4 small">
            New here ? <a href="signup" class="link-primary text-decoration-none">Click here to subscribe !</a>
        </div>


        <div class="small">
            <div class="text-warning fw-semibold mb-2">For Debug Purpose</div>
            <div>
                <a href="login/login_connect_as_bover" class="debug-line text-secondary"> Login as <span class="text-info">boverhaegen@epfc.eu</span></a>
            </div>
            <div>
                <a href="login/login_connect_as_marc" class="debug-line text-secondary"> Login as <span class="text-success">boverhaegen@epfc.eu</span></a>
            </div>
            <div>
                <a href="login/login_connect_as_quhouben" class="debug-line text-secondary"> Login as <span class="text-primary">quhouben@epfc.eu</span></a>
            </div>
            <div>
                <a href="login/login_connect_as_xavier" class="debug-line text-secondary"> Login as <span class="text-danger">xapiegolet@epfc.eu</span></a>
            </div>
        

            <hr class="border-secondary my-3">
            <div>
                <a href="setup/install" class="debug-line text-secondary"> Restore original data</a>
            </div>

            <div>
                <a href="setup/export" class="debug-line text-secondary"> Backup personal data</a>
            </div>
            <div>
                <a href="setup/restore" class="debug-line text-secondary"> Restore personal data</a>
            </div>
        </div>

    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
