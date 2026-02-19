<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un article</title>
    <base href="<?= $web_root ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/navigation.css">
    <link rel="stylesheet" href="css/open_item.css">
</head>
</head>
<body class="bg-dark text-light">
<?php
echo Navigation::top_bar("item/browse_items_view","Browse","historynav/manage_btn_back/back");
?>

<main class=" p-3">
    <div class="page-content py-2"> <!---classe parent qui gére la grille, le container. --->
        <div class="colonne-gauche">  <!--- classe parent qui gére la colonne gauche. --->
            <!-- class card gèrent le style visuel (fond gris), indépendantes les unes des autres. -->
            <div class="product-images card "> <!--- Classe card => style css | class product-images => identité --->
            </div>
            <div class="history-bids card"> <!--- Classe card => style css | class history-bids => identité --->
            </div>
        </div>
        <div class="colonne-droite">   <!--- classe parent qui gére la colonne droite. --->
            <div class="pricing card ">
                <h3>Pricing</h3>
                <div class="bid-row">
                    <p>Current Bid</p>
                    <p class="bid-row-price"> <span>€</span> 229,00</p>
                </div>
                <div class="buy-row">
                    <p>Buy Now</p>
                    <p class="buy-row-price"> <span>€</span> 385,00</p>
                </div>
                <hr> <!-- Horizontal Rule -->
                <div class="custom-input-group">
                    <span class="input-prefix">€</span> <!-- Input Group/(groupe de saisie) -->
                    <input type="number" value="230">
                </div>
                <!-- w-100" force à prendre toute la largeur de la carte. -->
                <!-- la classe magique de Bootstrap btn : -->
                <!--    le joli padding ni les coins arrondis par défaut. -->
                <button class="btn bid-row-btn w-100">Place Bid</button>
                <button class="btn buy-row-btn w-100">Buy Now at </button>
            </div>
            <div class="seller-information card ">
                <h3>Seller information</h3>
                <div class="seller-details">
                    <!-- Car nous voulons que le Titre reste seul en haut,
                    et que seulement la Photo et le nom + type de membre soient sur la même ligne en dessous.-->
                    <!-- Avec Le système Flexbox pour que ces deux blocs se mettent côte à côte sans
                    que le titre <h3> ne vienne s'incruster sur la même ligne,
                    nous devons isoler (class="profile-picture design-profile" et class="info-profile design-profile")
                    ensemble dans une boîte parente.-->
                    <div class="profile-picture">
                        <img src="https://i.pravatar.cc/150?img=32" alt="Girl in a jacket">
                    </div>
                    <div class="info-profile">
                        <p>Xavier</p>
                        <p>Member</p>
                    </div>
                </div>
            </div>
            <?php if($item->get_owner_id() == $user->get_id()): ?>
                <div class="manage-your-item card">
                    <h3>Manage Your Item</h3>
                    <?php
                    $mon_tableau = ["item/open_item_view/", "item/add_edit_item_view/".$item->get_id()];
                    $json = json_encode($mon_tableau);
                    $donnees_codees = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($json));
                    ?>
                    <a href="historynav/manage_btn_back/forward/<?=$donnees_codees?>" class="btn edit-item-details-btn w-100">Edit item Details </a>
                    <?php
                    $mon_tableau = ["item/open_item_view/", "manageimages/manage_images/".$item->get_id()];
                    $json = json_encode($mon_tableau);
                    $donnees_codees = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($json));
                    ?>
                    <a href="historynav/manage_btn_back/forward/<?=$donnees_codees?>" class="btn manage-images-btn w-100">Manage Images </a>
                    <?php
                    $mon_tableau = ["item/open_item_view/", "manageimages/manage_images/".$item->get_id()];
                    $json = json_encode($mon_tableau);
                    $donnees_codees = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($json));
                    ?>
                    <a href=""  class="btn delete-item-btn w-100">Delete item </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>
<?php
echo Navigation::bottom_nav("browseItems");
echo Navigation::bottom_bar();
?>
</body>
</html>
