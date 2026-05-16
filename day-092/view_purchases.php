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
    <link rel="stylesheet" href="css/card_custom.css">
    <link rel="stylesheet" href="css/purchases.css">

</head>

<body class="bg-dark text-light">
<?php
echo Navigation::top_bar("profile/sales_view","Purchases","profile/profile_view");
$btn_back[]="profile/purchases_view/";
?>
<main class="page-content p-3">
    <div class="container py-2">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">My Purchases</h2>
                <p class="text-secondary mb-0">Track the gear you've successfully secured.</p>
            </div>
            <span class="badge rounded-pill bg-primary px-3 py-2"><?php if(isset($word_sale)){echo $word_sale;}?></span>
        </div>
        <!--Les 3 cartes statistiques.-->
        <div class="row g-3 mb-5">
            <div class="col-md-4">
                <div class="stats-card p-4 h-100">
                    <p class="stats-label">TOTAL SPENT</p>
                    <h3 class="stats-value">€ <?php if(isset($sum_spent)){echo number_format($sum_spent, 2, ',', '.');}?></h3>
                    <p class="stats-subtext">Across <?php if(isset($word_sale)){echo $word_sale;}?></p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stats-card p-4 h-100">
                    <p class="stats-label">AVERAGE TICKET</p>
                    <h3 class="stats-value">€ <?php if(isset($average_ticket)){echo number_format($average_ticket, 2, ',', '.');}?></h3>
                    <p class="stats-subtext">Helps you plan future bids</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stats-card p-4 h-100">
                    <p class="stats-label">TOP SELLER</p>
                    <h3 class="stats-value"><?php if(isset($top_seller)){echo $top_seller;}?></h3>
                    <p class="stats-subtext">Who you've bought from the most</p>
                </div>
            </div>
        </div>
        <!--Les 3 cartes statistiques.-->
        <!--Les cartes des produits -->
        <div class="row">
            <?php if(isset($list_items_win) && isset($user)): ?>
                <?php foreach($list_items_win as $item): ?>
                    <?php $list_pictures_item =  $item->get_picture_path_item();?>
                    <?php echo Navigation::generate_cards_for_sales_purchase($user,$item,$btn_back,$list_pictures_item);?>

                <?php endforeach; ?>
            <?php endif;?>
        </div>
    </div>
</main>
<!-- FOOTER NAV -->
<?php
require_once 'view/view_bottom_bar.php';
?>
</body>
</html>