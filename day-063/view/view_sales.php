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
    <link rel="stylesheet" href="css/sales.css">

</head>

<body class="bg-dark text-light">
<?php
echo Navigation::top_bar("profile/sales_view","Sales",$btn_back ?? "");
?>
<main class="page-content p-3">
    <div class="container py-2">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Completed Sales</h2>
                <p class="text-secondary mb-0">A snapshot of the deals you've wrapped up.</p>
            </div>
            <span class="badge rounded-pill bg-success px-3 py-2">2 sales</span>
        </div>
        <!--Les 3 cartes statistiques.-->
        <div class="row g-3 mb-5">
            <div class="col-md-4">
                <div class="stats-card p-4 h-100">
                    <p class="stats-label">TOTAL REVENUE</p>
                    <h3 class="stats-value">€ 828,00</h3>
                    <p class="stats-subtext">Across 2 sales</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stats-card p-4 h-100">
                    <p class="stats-label">AVERAGE TICKET</p>
                    <h3 class="stats-value">€ 414,00</h3>
                    <p class="stats-subtext">Median buyer appetite indicator</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stats-card p-4 h-100">
                    <p class="stats-label">LOYAL BIDDER</p>
                    <h3 class="stats-value">Marc</h3>
                    <p class="stats-subtext">Most recurring winning bidder</p>
                </div>
            </div>

        </div>
    </div>
</main>
<!-- FOOTER NAV -->
<?php
require_once 'view/view_bottom_bar.php';
?>
</body>
</html>
