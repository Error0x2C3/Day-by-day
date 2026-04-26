<?php

require_once 'framework/Configuration.php';
require_once 'utils/Navigation.php';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Browse Items</title>
    <base href="<?= $web_root ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/browse_items.css">
    <link rel="stylesheet" href="css/navigation.css">
    <link rel="stylesheet" href="css/card_custom.css">
    <link rel="stylesheet" href="css/search_bar.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.14.2/jquery-ui.js"></script>

</head>
<body class="bg-dark text-light">
<script>
let word_search = <?php echo json_encode(Navigation::decode($word_search))?>
</script>
<script src="js/search.js"></script>
<?php
echo Navigation::top_bar("item/browse_items_view","Browse");
$btn_back[] = "item/browse_items_view/";
?>

<main class="page-content p-3">
    <div class="container py-2" id="bar_search">
        <h2 class="h5 mb-4 title-participating">Items I'm Participating In </h2>
        <?php if ( isset($list_items_user_participing) && !empty($list_items_user_participing)): ?>
            <div class="row g-3 mb-5" id="tag_result_filter-participating">
                <?php foreach ($list_items_user_participing as $item) {?>
                    <?php $list_pictures_item =  $item->get_picture_path_item();?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card">
                            <a href="item/open_item_view/<?= $item->get_id();?>/<?=Navigation::encode($btn_back)?>" class="stretched-link"></a>
                            <div class="position-relative">
                                <?php if(!empty($list_pictures_item)):?>
                                    <img src="<?php  echo $list_pictures_item[0];?>" class="card-img-top" alt="Card image">
                                <?php else : ?>
                                    <i class="bi bi-image card-img-top d-flex align-items-center justify-content-center" style="font-size: 8rem; height: 100%; width: 100%; color: grey;"></i>
                                <?php endif; ?>
                                <div class="card-img-overlay">
                                    <?php $highest_bidder_id = $item->get_highest_bidder() instanceof Bids ?  $item->get_highest_bidder()->get_owner_id() : null;?>
                                    <?php if($highest_bidder_id  == $user->get_id() && $item->has_auction()): ?>
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
                    </div>
                <?php } ?>
            </div>
        <?php endif; ?>
        <h2 class="h5 mb-4 other-available-items">Other Available Items</h2>
        <?php if(isset($list_other_available_items) && !empty($list_other_available_items)) :?>
            <div class="row g-3 mb-5" id="tag_result_filter-avaible-items">
                <?php foreach ($list_other_available_items as $item) {?>
                    <?php $list_pictures_item =  $item->get_picture_path_item()?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card">
                            <a href="item/open_item_view/<?=$item->get_id();?>/<?=Navigation::encode($btn_back)?>" class="stretched-link"></a>
                            <div class="position-relative">
                                <img src="<?php  echo $list_pictures_item[0];?>" class="card-img-top" alt="Card image">
                                <div class="card-img-overlay">
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
                                        <?php echo $item->time_elapsed_string(); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- FOOTER NAV -->
<?php
require_once 'view/view_bottom_bar.php';
?>

</body>
</html>
