<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>My items</title>
    <base href="<?= $web_root ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/my_items.css">
    <link rel="stylesheet" href="css/navigation.css">
    <link rel="stylesheet" href="css/card_custom.css">
    <link rel="stylesheet" href="css/search_bar.css">
    <link rel="stylesheet" href="css/err_no_item_found.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.14.2/jquery-ui.js"></script>
</head>


<body class="bg-dark text-light">
<script>
    let word_search = <?php echo json_encode(Navigation::decode($word_search))?>
</script>
<script src="js/search.js"></script>
<?php
echo Navigation::top_bar("item/my_items_view","My items",);
$btn_back[] = "item/my_items_view/";
?>

<main class="page-content p-3">
    <div class="container py-2" id="bar_search">
        <!--Active items-->
        <?php if ( isset($activeItems) && !empty($activeItems) && isset($user)): ?>
            <h2 class="h5 mb-4 active-items">Active Items</h2>
            <div class="row g-3 mb-5 " id="tag_result_filter-active-items">
                <?php foreach ( $activeItems as $item) {?>
                    <?php $list_pictures_item =  $item->get_picture_path_item();?>
                    <?php echo Navigation::generate_cards($user,$item,$btn_back,$list_pictures_item);?>
                <?php } ?>
            </div>
        <?php endif; ?>
        <!--Active Items-->
        <!--Close Unsold Items-->
        <?php if(isset($closedUnsoldItems) && !empty( $closedUnsoldItems)) :?>
            <h2 class="h5 mb-4 closed-unsold-items">Closed Unsold Items</h2>
            <div class="row g-3 mb-5"  id="tag_result_filter-closed-unsold-items">
                <?php foreach ( $closedUnsoldItems as $item) {?>
                    <?php $list_pictures_item =  $item->get_picture_path_item()?>
                    <?php echo Navigation::generate_cards($user,$item,$btn_back,$list_pictures_item);?>
                <?php } ?>
            </div>
        <?php endif; ?>
        <!--Closed Unsold Items-->
        <!--Closed Unsold Items-->
        <?php if(isset($soldItems) && !empty( $soldItems )) :?>
            <h2 class="h5 mb-4 closed-unsold-items">Sold Items</h2>
            <div class="row g-3 mb-5" id="tag_result_filter-sold-items">
                <?php foreach (  $soldItems  as $item) {?>
                    <?php $list_pictures_item =  $item->get_picture_path_item()?>
                    <?php echo Navigation::generate_cards($user,$item,$btn_back,$list_pictures_item);?>
                <?php } ?>
            </div>
        <?php endif; ?>
        <!--Closed Unsold Items-->
    </div>
</main>

<!-- FOOTER NAV -->
<?php
require_once 'view/view_bottom_bar.php';
?>

</body>
</html>

