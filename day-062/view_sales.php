<?php
?>
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
echo Navigation::top_bar("profile/sales_view","Sales",$btn_back ?? "");
?>
<main class="page-content p-3">
    <div class="container py-2">
        <header class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">Completed Sales</h1>
                <p class="text-secondary small">A snapshot of the deals you've wrapped up.</p>
            </div>
            <span class="badge rounded-pill bg-success px-3 py-2">2 sales</span>
        </header>

        <div class="row g-3 mb-5">
            <div class="col-md-4">
                <div class="card bg-dark border-secondary text-light p-3 h-100">
                    <small class="text-secondary text-uppercase fw-semibold">Total Revenue</small>
                    <h2 class="fw-bold">€ 828,00</h2>
                    <small class="text-secondary">Across 2 sales</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-dark border-secondary text-light p-3 h-100">
                    <small class="text-secondary text-uppercase fw-semibold">Average Ticket</small>
                    <h2 class="fw-bold">€ 414,00</h2>
                    <small class="text-secondary">Median buyer appetite indicator</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-dark border-secondary text-light p-3 h-100">
                    <small class="text-secondary text-uppercase fw-semibold">Loyal Bidder</small>
                    <h2 class="fw-bold text-info">Marc</h2>
                    <small class="text-secondary">Most recurring winning bidder</small>
                </div>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

            <div class="col">
                <div class="card bg-transparent border-0 h-100">
                    <div class="position-relative bg-white rounded-3 p-4 overflow-hidden shadow-sm" style="min-height: 200px;">
                        <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2"><i class="bi bi-hammer"></i> Auction</span>
                        <span class="badge bg-primary position-absolute top-0 end-0 m-2 mt-5"><i class="bi bi-bag"></i> Buy Now</span>
                        <img src="https://via.placeholder.com/150" class="img-fluid d-block mx-auto" alt="Apple Watch">
                        <span class="badge bg-dark opacity-75 position-absolute bottom-0 end-0 m-2"><i class="bi bi-images"></i> 3 images</span>
                    </div>
                    <div class="card-body px-0 py-3">
                        <h5 class="card-title mb-0 fw-bold">Apple Watch Series 7</h5>
                        <p class="text-secondary small mb-3">by Boris</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-primary fw-bold h5 mb-0">€ 318,00</span>
                            <div class="text-end">
                                <small class="text-secondary d-block">Current bid</small>
                                <span class="text-success fw-bold small">€ 278,00</span>
                            </div>
                        </div>
                    </div>
                    <div class="border-top border-secondary pt-2">
                        <p class="mb-1 small"><i class="bi bi-currency-euro"></i> Final price € 278,00</p>
                        <p class="mb-1 small text-info"><i class="bi bi-trophy"></i> Marc</p>
                        <p class="mb-0 text-secondary" style="font-size: 0.75rem;"><i class="bi bi-clock"></i> Closed on 29/10/2025</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- FOOTER NAV -->
<?php
require_once 'view/view_bottom_bar.php';
//echo Navigation::bottom_nav("profile",$user ?? null);
//echo Navigation::bottom_bar();
//?>
</body>
</html>
