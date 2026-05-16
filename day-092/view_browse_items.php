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
                    <?php echo Navigation::generate_cards($user,$item,$btn_back,$list_pictures_item);?>
                <?php } ?>
            </div>
        <?php endif; ?>
        <h2 class="h5 mb-4 other-available-items">Other Available Items</h2>
        <?php if(isset($list_other_available_items) && !empty($list_other_available_items)) :?>
            <div class="row g-3 mb-5" id="tag_result_filter-avaible-items">
                <?php foreach ($list_other_available_items as $item) {?>
                    <?php $list_pictures_item =  $item->get_picture_path_item()?>
                    <?php echo Navigation::generate_cards($user,$item,$btn_back,$list_pictures_item);?>
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
