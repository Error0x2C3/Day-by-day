<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Bottom Bar</title>
    <base href="<?= $web_root ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/browseItems.css">
</head>


<body class="bg-dark text-light">

<nav class="top-nav">
    <div class="top-wrap">
        <a href="browseItems/view" class="brand">
            <span class="brand-name">Browse</span>
            <i class="bi bi-cart"></i>
        </a>
    </div>
</nav>

<nav class="bottom-nav">
    <?php $active = "browseItems"; ?>
    <div class="nav-wrap">
        <a class="nav-item-btn <?= $active === "browseItems" ? "active" : "" ?>" href="browseItems/view">
            <i class="bi bi-compass"></i><span>Browse</span>
        </a>
        <a class="nav-item-btn <?= $active === "myItems" ? "active" : "" ?>" href="myItems/view">
            <i class="bi bi-house-door"></i><span>My Items</span>
        </a>
        <a class="nav-item-btn <?= $active === "addOffer" ? "active" : "" ?>" href="addOffer/view">
            <i class="bi bi-plus-circle"></i><span>Add Offer</span>
        </a>
        <a class="nav-item-btn <?= $active === "profile" ? "active" : "" ?>" href="profile/view">
            <i class="bi bi-gear"></i><span>Profile</span>
        </a>
    </div>
</nav>

<div class="bottom-bar fixed-bottom">
    <div class="container-fluid  h-100 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2 time-display">
            <i class="bi bi-clock"></i>
            <span>10/11/25 20:55</span>
        </div>

        <div class="right-actions d-flex gap-2">
            <button type="button" class="btn-time"><i class="bi bi-clock"></i> +1h</button>
            <button type="button" class="btn-time"><i class="bi bi-calendar2"></i> +1d</button>
            <button type="button" class="btn-time"><i class="bi bi-calendar2"></i> +1w</button>
            <button type="button" class="btn-reset"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>
        </div>

    </div>
</div>

</body>
</html>
