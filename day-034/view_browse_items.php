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

</head>


<body class="text-light custom-bg">


<?php
echo Navigation::top_bar("item/browse_items_view","Browse");
?>

<?php
$monTableau = ["item/browse_items_view","item/add_edit_item_view"];
$donneesCodees = base64_encode(json_encode($monTableau));
?>

<!-- Test pour controllerHistoryNav -->
<!--<a href="historynav/manage_btn_back/forward/<?= $donneesCodees?>"> ajouter un item</a> -->

<main class="page-content p-3">
    <div class="container py-2">


        <?php if ( isset($list_items_user_participing) &&!empty($list_items_user_participing)): ?>
            <h2 class="h5 mb-4 title-participating">Items I'm Participating In </h2>
            <i class="fa-sharp fa-solid fa-gavel fa-flip-horizontal"></i>
            <div class="row g-3 mb-5">
                <?php foreach ($list_items_user_participing as $item) {?>
                    <?php echo $item->get_max_bid()?>
                    <?php $list_pictures_item =  $item->get_picture_path_item()?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card ">
                            <img src="<?php  echo $list_pictures_item[0];?>" class="card-img-top" alt="Card image">
                            <div class="card-img-overlay">
                                <i class="fa-sharp fa-solid fa-gavel"></i>
                            </div>
                            <div class="card-body">
                                <p class="card-title-custom">
                                    <?php echo $item->get_title(); ?>
                                </p>
                                <p class="card-owner-custom">
                                    <span class="by">by</span> <?php echo $item->get_owner_name(); ?>
                                </p>
                                <?php if($item->has_auction() && !$item->has_buy_now()): ?>
                                    <p class="card-starting-bid-custom">
                                        <?php echo "€"." ".number_format($item->get_starting_bid(), 2, ',', '.'); ?>
                                    </p>
                                <?php elseif(( $item->has_auction() && $item->has_buy_now() )||  $item->has_buy_now() ): ?>
                                    <p class="card-buy-now-price-custom">
                                        <?php echo "€"." ".number_format($item->get_starting_bid(), 2, ',', '.'); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php endif; ?>
        <?php if(isset($list_other_available_items) && !empty($list_other_available_items)) :?>
            <h2 class="h5 mb-4 other-available-items">Other Available Items</h2>
            <div class="row g-3 mb-5">
                <?php foreach ($list_other_available_items as $item) {?>
                    <?php $list_pictures_item =  $item->get_picture_path_item()?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card">
                            <img src="<?php  echo $list_pictures_item[0];?>" class="card-img-top" alt="Card image">
                            <div class="card-body">
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php endif; ?>
    </div>

</main>

<?php
echo Navigation::bottom_nav("browseItems");
echo Navigation::bottom_bar();
?>

</body>
</html>
