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
        <!--Les 3 cartes statistiques.-->
        <!--Les cartes des produits -->
        <div class="row">
            <?php if(isset($list_items) && isset($user)): ?>
                <?php foreach($list_items as $item): ?>
                    <?php $list_pictures_item =  $item->get_picture_path_item();?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card">
                            <a href="item/open_item_view/<?= $item->get_id()?>" class="stretched-link"></a>
                            <div class="position-relative">
                                <?php if(!empty($list_pictures_item)):?>
                                    <img src="<?php  echo $list_pictures_item[0];?>" class="card-img-top" alt="Card image">
                                <?php else : ?>
                                    <i class="bi bi-image card-img-top d-flex align-items-center justify-content-center" style="font-size: 8rem; height: 100%; width: 100%; color: grey;"></i>
                                <?php endif; ?>
                                <div class="card-img-overlay">
                                    <?php $bid_highest = Bids::get_highest_bidder($item->get_id()) instanceof Bids ?  $item->get_highest_bidder() : null;?>
                                    <?php if($bid_highest = $item->get_highest_bidder() instanceof Bids && $bid_highest->get_owner_id() == $user->get_id() && $item->has_auction()): ?>
                                        <div class="badge-highest-bidder">
                                            <i class="bi bi-trophy"></i><span>Highest Bidder</span>
                                        </div>
                                    <?php else : ?>
                                        <div class="badge-bidder">
                                            <i class="bi bi-bag"></i> <span>Bidder</span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="badges-top-right">
                                        <?php if($item->has_auction() && !$item->has_buy_now()): ?>
                                            <div class="badge-auction">
                                                <i class="bi bi-shop-window"></i>
                                                <span>Auction</span>
                                            </div>
                                        <?php elseif($item->has_buy_now() && !$item->has_auction()): ?>
                                            <div class="badge-buy-now">
                                                <i class="bi bi-bag"></i>
                                                <span>Buy Now</span>
                                            </div>
                                        <?php elseif($item->has_buy_now() && $item->has_auction()): ?>
                                            <div class="badge-auction">
                                                <i class="bi bi-shop-window"></i>
                                                <span>Auction</span>
                                            </div>
                                            <div class="badge-buy-now">
                                                <i class="bi bi-bag"></i>
                                                <span>Buy Now</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if(count($list_pictures_item ) >0): ?>
                                        <?php $phrase = count($list_pictures_item) > 1 ? "images" : "image" ; ?>
                                        <div class="badge-images">
                                            <i class="bi bi-images"></i>
                                            <span><?php echo count($list_pictures_item )." ".$phrase;?> </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="card-title-custom"><?php echo $item->get_title(); ?></p>
                                <p class="card-owner-custom"><span class="by">by</span> <?php echo $item->get_owner_pseudo(); ?></p>

                                <div class="d-flex justify-content-between align-items-end mt-3">
                                    <div class="main-price-container">
                                        <?php if($item->has_auction() && !$item->has_buy_now()): ?>
                                            <p class="card-starting-bid-custom mb-0">
                                                € <?php echo number_format($item->get_starting_bid(), 2, ',', '.'); ?>
                                            </p>
                                        <?php elseif($item->has_buy_now()): ?>
                                            <p class="card-buy-now-price-custom mb-0">
                                                € <?php echo number_format($item->get_buy_now_price(), 2, ',', '.'); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($item->get_max_bid() > 0): ?>
                                        <div class="text-end">
                                            <p class="card-current-bid-label mb-0">Current bid</p>
                                            <p class="card-current-bid-value mb-0">
                                                € <?php echo number_format($item->get_max_bid(), 2, ',', '.'); ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if( !$item->time_has_passed()): ?>
                                    <p class="card-timer-custom mt-3 mb-0">
                                        <i class="bi bi-clock"></i>
                                        <?php echo $item->time_elapsed_string(); ?>
                                    </p>
                                <?php else : ?>
                                    <p class="card-timer-expired-custom mt-3 mb-0">
                                        <i class="bi bi-clock"></i>
                                        <?php echo "Closed"; ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mt-3 text-secondary small">
                        <span class="input-group-text bg-transparent border-end-0 ">
                        <i class="bi bi-currency-dollar"></i>
                        Final price 278.00
                        </span>
                        </div>
                    </div>
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
