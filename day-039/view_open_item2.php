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
            <!-- padding: 0; enlève le padding de 20px tout autour de l'image.-->
            <div class="product-images card" style="padding: 0;"> <!--- Classe card => style css | class product-images => identité --->
                <?php $list_pictures_item =  $item->get_picture_path_item()?>
                <img class="main-img" src="<?= $list_pictures_item[0];?>" alt="main-img">
                <!-- p : Signifie Padding (marge interne). C'est l'espace entre le contenu de l'élément et ses bordures.-->
                <div class="product-content p-4">
                    <div class="description-1">
                        <h1 style="font-size: 24px; font-weight: bold; margin: 0;">Piano numérique Yamaha P-45</h1>
                        <span class="badge bg-secondary">AUCTION</span>
                    </div>
                    <p class="product-desc">Touches lestées, pédale incluse.</p>
                    <div class="product-dates">
                        <div><span class="date-label">Start:</span> 10/11/2025 04:16:04</div>
                        <div><span class="date-label">Ends:</span> 17/11/2025 04:16:04</div>
                    </div>
                </div>
            </div>
            <div class="galerie-picture card">
                <h3>Additional Images</h3>
                <div class="galerie-picture-grid">
                    <?php foreach($list_pictures_item as $index => $path): ?>
                        <?php if($index === 0) continue; ?>
                        <img class="thumb-img" src="<?= $path; ?>" alt="gallery-thumb">
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="history-bids card"> <!--- Classe card => style css | class history-bids => identité --->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>Bid History</h3>
                    <span class="badge bg-light text-dark">3 entries</span>
                </div>
                <div class="history-list">
                    <div class="history-row">
                        <div class="bidder-info">
                            <strong>Boris</strong>
                            <div class="bid-date">11/11/2025 21:55:03</div>
                        </div>
                        <div class="bid-price">€ 229,00</div>
                    </div>
                    <div class="history-row">
                        <div class="bidder-info">
                            <strong>Boris</strong>
                            <div class="bid-date">11/11/2025 21:55:03</div>
                        </div>
                        <div class="bid-price">€ 229,00</div>
                    </div>
                </div>
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
