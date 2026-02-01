<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Browse Items</title>
    <base href="<?= $web_root ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/navigation.css">
    <link rel="stylesheet" href="css/brows_items.css">
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

<?php

if(isset($user) &&  isset($list_items_user_participing)){

    if($user instanceof User){
        echo "sscscscscscsc";
        foreach ($list_items_user_participing as $item){
            echo $item->get_title();
        }
        // print_r($list_items_user_participing);
    }else{
        echo",,,,,,";
    }
}
?>
<main class="page-content p-3">
    <div class="container py-2">


    <?php if ( isset($list_items_user_participing) &&!empty($list_items_user_participing)): ?>
            <h2 class="h5 mb-4 text-primary-emphasis">Items I'm Participating In</h2>
            <div class="row g-3 mb-5">

            <?php foreach ($list_items_user_participing as $item) {?>
                <?php $list_pictures_item =  $item->get_picture_path_item()?>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card">
                        <img src="<?php  echo $list_pictures_item[0];?>" class="card-img-top" alt="Card image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $item->get_title();?></h5>
                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                            <a href="#" class="btn btn-primary">Go somewhere</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
            </div>

        <?php endif; ?>

        <h2 class="h5 mb-4 text-primary-emphasis">Other Available Items</h2>
        <div class="row g-4">
        </div>
    </div>

</main>

<?php
echo Navigation::bottom_nav("browseItems");
echo Navigation::bottom_bar();
?>

</body>
</html>
