<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>EPayFC - Dashboard</title>
    <base href="<?= $web_root ?>">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icônes Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Ton CSS -->
    <link rel="stylesheet" href="css/browseItems.css">
</head>
<body>


<!-- Barre top -->
<nav class="navbar navbar-dark topbar">
    <div class="container-fluid position-relative">

        <!-- LOGO + TEXTE CENTRÉ -->
        <div class="topbar-center">
            <a href="#" class="topbar-link">
                <i class="bi bi-compass-fill me-1"></i>
                Browse
            </a>
        </div>

        <!-- ICÔNE PANIER À DROITE -->
        <button class="btn btn-sm btn-outline-light topbar-cart-btn">
            <i class="bi bi-cart"></i>
        </button>

    </div>
</nav>


<main class="py-4">
    <div class="container">

        <!-- SECTION 1 -->
        <h6 class="section-title mb-3">Items I'm Participating In</h6>
        <div class="row g-3 row-cols-1 row-cols-md-2 row-cols-xl-4">

            <!-- Carte 1 -->
            <div class="col">
                <div class="card item-card h-100">
                    <div class="card-img-top-wrapper">
                        <img src="https://via.placeholder.com/400x250?text=Dell+Screen" class="card-img-top" alt="">
                        <div class="badge-pill-left">
                            <span class="badge rounded-pill badge-secondary">Bidder</span>
                        </div>
                        <div class="badge-pill-right d-flex flex-column gap-1">
                            <span class="badge rounded-pill badge-warning text-dark">Auction</span>
                            <span class="badge rounded-pill badge-dark">4 images</span>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title mb-1">Écran 27" 4K Dell UltraSharp</h6>
                        <div class="small text-muted mb-2">by Xavier</div>

                        <div class="mt-auto">
                            <div class="d-flex justify-content-between small">
                                <div>
                                    <div class="text-muted">Your bid</div>
                                    <div class="price-main">€ 135,00</div>
                                </div>
                                <div class="text-end">
                                    <div class="text-muted">Current bid</div>
                                    <div class="price-secondary">€ 330,00</div>
                                </div>
                            </div>
                            <div class="mt-2 small text-success">
                                <i class="bi bi-clock"></i> 2d 4h left
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte 2 -->
            <div class="col">
                <div class="card item-card h-100">
                    <div class="card-img-top-wrapper">
                        <img src="https://via.placeholder.com/400x250?text=Piano" class="card-img-top" alt="">
                        <div class="badge-pill-left">
                            <span class="badge rounded-pill badge-secondary">Bidder</span>
                        </div>
                        <div class="badge-pill-right d-flex flex-column gap-1">
                            <span class="badge rounded-pill badge-warning text-dark">Auction</span>
                            <span class="badge rounded-pill badge-dark">2 images</span>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title mb-1">Piano numérique Yamaha P-45</h6>
                        <div class="small text-muted mb-2">by Xavier</div>

                        <div class="mt-auto">
                            <div class="d-flex justify-content-between small">
                                <div>
                                    <div class="text-muted">Your bid</div>
                                    <div class="price-main">€ 385,00</div>
                                </div>
                                <div class="text-end">
                                    <div class="text-muted">Current bid</div>
                                    <div class="price-secondary">€ 229,00</div>
                                </div>
                            </div>
                            <div class="mt-2 small text-success">
                                <i class="bi bi-clock"></i> 3d 7h left
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte 3 -->
            <div class="col">
                <div class="card item-card h-100">
                    <div class="card-img-top-wrapper">
                        <img src="https://via.placeholder.com/400x250?text=Keyboard" class="card-img-top" alt="">
                        <div class="badge-pill-left">
                            <span class="badge rounded-pill badge-success">Highest Bidder</span>
                        </div>
                        <div class="badge-pill-right d-flex flex-column gap-1">
                            <span class="badge rounded-pill badge-warning text-dark">Auction</span>
                            <span class="badge rounded-pill badge-primary">Buy Now</span>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title mb-1">Clavier mécanique Keychron K2</h6>
                        <div class="small text-muted mb-2">by Xavier</div>

                        <div class="mt-auto">
                            <div class="d-flex justify-content-between small">
                                <div>
                                    <div class="text-muted">Your bid</div>
                                    <div class="price-main">€ 275,00</div>
                                </div>
                                <div class="text-end">
                                    <div class="text-muted">Current bid</div>
                                    <div class="price-secondary">€ 182,00</div>
                                </div>
                            </div>
                            <div class="mt-2 small text-success">
                                <i class="bi bi-clock"></i> 7d 17h left
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte 4 -->
            <div class="col">
                <div class="card item-card h-100">
                    <div class="card-img-top-wrapper">
                        <img src="https://via.placeholder.com/400x250?text=Lens" class="card-img-top" alt="">
                        <div class="badge-pill-left">
                            <span class="badge rounded-pill badge-secondary">Bidder</span>
                        </div>
                        <div class="badge-pill-right d-flex flex-column gap-1">
                            <span class="badge rounded-pill badge-warning text-dark">Auction</span>
                            <span class="badge rounded-pill badge-primary">Buy Now</span>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title mb-1">Objectif Fujinon 23mm f/2</h6>
                        <div class="small text-muted mb-2">by Xavier</div>

                        <div class="mt-auto">
                            <div class="d-flex justify-content-between small">
                                <div>
                                    <div class="text-muted">Your bid</div>
                                    <div class="price-main">€ 407,00</div>
                                </div>
                                <div class="text-end">
                                    <div class="text-muted">Buy now</div>
                                    <div class="price-secondary">€ 405,00</div>
                                </div>
                            </div>
                            <div class="mt-2 small text-success">
                                <i class="bi bi-clock"></i> 10d 12h left
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 2 -->
        <h6 class="section-title mt-4 mb-3">Other Available Items</h6>
        <div class="row g-3 row-cols-1 row-cols-md-2 row-cols-xl-4">

            <!-- Tu peux dupliquer / adapter ces cartes -->
            <div class="col">
                <div class="card item-card h-100">
                    <div class="card-img-top-wrapper">
                        <img src="https://via.placeholder.com/400x250?text=Chair" class="card-img-top" alt="">
                        <div class="badge-pill-right">
                            <span class="badge rounded-pill badge-warning text-dark">Auction</span>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title mb-1">Chaise de bureau ergonomique</h6>
                        <div class="small text-muted mb-2">by Quentin</div>

                        <div class="mt-auto">
                            <div class="text-muted small">Current bid</div>
                            <div class="price-main mb-1">€ 149,00</div>
                            <div class="small text-success">
                                <i class="bi bi-clock"></i> 1d 1h left
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ... autres cartes, même structure ... -->

        </div>
    </div>
</main>

<!-- Barre de navigation bas -->
<nav class="navbar navbar-dark bottombar fixed-bottom">
    <div class="container-fluid px-4 d-flex justify-content-between align-items-center">

        <!-- DATE À GAUCHE -->
        <div class="small text-muted d-none d-md-flex align-items-center">
            <i class="bi bi-clock me-2"></i> 10/11/25 20:55
        </div>

        <!-- NAVIGATION CENTRALE -->
        <div class="d-flex gap-5 text-center mx-auto bottom-nav">

            <div class="bottom-item active">
                <i class="bi bi-compass-fill"></i>
                <div>Browse</div>
            </div>

            <div class="bottom-item">
                <i class="bi bi-collection"></i>
                <div>My Items</div>
            </div>

            <div class="bottom-item">
                <i class="bi bi-plus-lg"></i>
                <div>Add Offer</div>
            </div>

            <div class="bottom-item">
                <i class="bi bi-person-circle"></i>
                <div>Profile</div>
            </div>

        </div>

        <!-- BOUTONS +1h etc -->
        <div class="d-none d-lg-flex gap-2 small">
            <span class="badge rounded-pill bg-secondary">+1h</span>
            <span class="badge rounded-pill bg-secondary">+1d</span>
            <span class="badge rounded-pill bg-secondary">+1w</span>
            <span class="badge rounded-pill bg-danger">Reset</span>
        </div>

    </div>
</nav>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
