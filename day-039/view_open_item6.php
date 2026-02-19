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
                <?php if(!empty($list_pictures_item)): ?>
                    <!--Variable $image_index existe lorsqu'il faut parcourir la galarie d'image.-->
                    <?php if(isset($image_index)): ?>
                        <img class="main-img"  src="<?=$list_pictures_item[$image_index];?>" alt="">
                    <?php else: ?>
                        <img class="main-img"  src="<?=$list_pictures_item[0];?>" alt="">
                    <?php endif; ?>
                <?php else: ?>
                    <i class="bi bi-image main-img-placeholder d-flex align-items-center justify-content-center" style="font-size: 10rem; height: 100%; width: 100%;"></i>
                <?php endif; ?>
                <!-- p : Signifie Padding (marge interne). C'est l'espace entre le contenu de l'élément et ses bordures.-->
                <div class="product-content p-4">
                    <div class="description-1">
                        <h1 style="font-size: 24px; font-weight: bold; margin: 0;"><?php echo $item->get_title();?></h1>
                        <?php if($item->has_buy_now() && !$item->has_auction()):?>
                            <span class="badge bg-secondary">BUY NOW</span>
                        <?php else : ?>
                            <span class="badge bg-secondary">AUCTION</span>
                        <?php endif;?>
                    </div>
                    <?php if($item->get_description()):?>
                    <p class="product-desc"><?php echo $item->get_description()?></p>
                    <?php endif; ?>
                    <div class="product-dates">
                        <div><span class="date-label">Start:</span> <?php echo $item->get_created_at()->format('d/m/Y H:i:s');?></div>
                        <div><span class="date-label">Ends: </span><?php echo $item->get_end_at()->format('d/m/Y H:i:s');?></div>
                    </div>
                </div>
            </div>
            <?php if(!empty($list_pictures_item) && count($list_pictures_item) > 1): ?>
            <div class="galerie-picture card">
                <h3>Additional Images</h3>
                <div class="galerie-picture-grid">
                    <!--Variable $image_index existe lorsqu'il faut parcourir la galarie d'image.-->
                    <?php if((isset($image_index))): ?>
                        <?php
                        // Permet de parcourir tab[1,2,3] de cette manière par ex : 2,3,1.
                        $taille = count($list_pictures_item);
                        $depart = $image_index+ 1;
                        for ($i = 0; $i < $taille; $i++) {
                            if($i == $taille -1){
                                continue;
                            }else{
                                $index = ($depart + $i) % $taille;
                            }
                            ?>
                                <a href="item/additional_images_manage_for_open_item_view/<?= $item->get_id();?>/<?= $index;?>" >
                                    <img class="thumb-img" src="<?= $list_pictures_item[$index]; ?>" alt="gallery-thumb">
                                </a>
                            <?php
                        }
                        ?>
                    <?php else : ?>
                        <?php foreach($list_pictures_item as $index => $path): ?>
                            <?php if($index === 0) continue; ?>
                            <a href="item/additional_images_manage_for_open_item_view/<?= $item->get_id();?>/<?= $index;?>" >
                                <img class="thumb-img" src="<?= $path; ?>" alt="gallery-thumb">
                            </a>
                        <?php endforeach; ?>
                    <?php endif;?>
                </div>
            </div>
            <?php endif; ?>
            <div class="history-bids card"> <!--- Classe card => style css | class history-bids => identité --->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>Bid History</h3>
                    <?php
                        $word = $item->get_bid_count() > 1 ?  $item->get_bid_count()." "."entries" :  $item->get_bid_count()." "."entrie";
                    ?>
                    <span class="badge bg-light text-dark"><?php echo $word;?></span>
                </div>
                <div class="history-list">
                    <?php foreach ($item->get_bid_history() as $bid_item) { ?>
                        <div class="history-row">
                            <div class="bidder-info">
                                <strong><?php echo $bid_item->get_owner_pseudo()?></strong>
                                <div class="bid-date"><?php echo $bid_item->get_created_at()->format("Y-m-d H:i:s");?></div>
                            </div>
                            <div class="bid-price"><span>€ </span><?php echo $bid_item->get_amount();?></div>
                        </div>
                    <?php }?>
                </div>
            </div>
        </div>
        <div class="colonne-droite">   <!--- classe parent qui gére la colonne droite. --->
            <div class="pricing card ">
                <h3>Pricing</h3>
                <!--- Annonce ouverte. --->
                <?php if(!$item->should_close()):?>
                    <!--- Utilisateur non créateur. --->
                    <!--- AFFICHAGE DES PRIX -->
                    <!--- AFFICHAGE DES PRIX -->
                    <?php if($item->get_owner_id() != $user->get_id()): ?>
                        <!-- 1) Pas encore d'enchère et pas d'achat immédiat.-->
                        <?php if($item->has_auction() && !$item->is_has_bids() && !$item->has_buy_now()):?>
                            <div class="bid-row">
                                <p>Current Bid</p>
                                <p class="bid-row-price"> <span>€</span> <?php echo  number_format($item->get_starting_bid(), 2, ',', '.');?></p>
                            </div>
                            <div class="bid-starting-block">
                                <p class="starting-text">
                                    Starting at <span class="starting-price">€ <?php echo  number_format($item->get_starting_bid(), 2, ',', '.'); ?></span>
                                </p>
                            </div>
                        <!-- 1) BIS A déjà des enchères et pas d'achat immédiat.-->
                        <?php elseif($item->has_auction() && $item->is_has_bids() && !$item->has_buy_now()):?>
                            <div class="bid-row">
                                <p>Current Bid</p>
                                <?php $resultat = $item->get_max_bid() !== null ? $item->get_max_bid() : $item->get_starting_bid() ; ?>
                                <p class="bid-row-price"> <span>€</span> <?php echo  number_format($resultat, 2, ',', '.');?></p>
                            </div>
                        <!-- 2) Enchères existantes, prix d'achat immédiat non atteint. -->
                        <?php elseif($item->has_auction() && $item->is_has_bids() && $item->has_buy_now() && !$item->get_first_winning_buy_now_bid()): ?>
                            <div class="bid-row">
                                <p>Current Bid</p>
                                <?php $resultat = $item->get_max_bid() !== null ? $item->get_max_bid() : $item->get_starting_bid() ; ?>
                                <p class="bid-row-price"> <span>€</span><?php echo  number_format( $resultat, 2, ',', '.');?></p>
                            </div>
                            <div class="buy-row">
                                <p>Buy Now</p>
                                <p class="buy-row-price"> <span>€</span><?php echo  number_format($item->get_buy_now_price(), 2, ',', '.');?></p>
                            </div>
                        <!-- 2) BIS Enchères existantes, prix d'achat immédiat non atteint. -->
                        <?php elseif($item->has_auction() && !$item->is_has_bids() && $item->has_buy_now() && !$item->get_first_winning_buy_now_bid()): ?>
                            <div class="bid-row">
                                <p>Current Bid</p>
                                <?php $resultat = $item->get_max_bid() !== null ? $item->get_max_bid() : $item->get_starting_bid() ; ?>
                                <p class="bid-row-price"> <span>€</span><?php echo  number_format($resultat, 2, ',', '.');?></p>
                            </div>
                            <div class="buy-row">
                                <p>Buy Now</p>
                                <p class="buy-row-price"> <span>€</span><?php echo  number_format($item->get_buy_now_price(), 2, ',', '.');?></p>
                            </div>
                        <!-- 3) Vente directe uniquement et n'a pas déjà reçu une enchère. -->
                        <?php elseif($item->has_buy_now() && !$item->has_auction() && !$item->is_has_bids()):?>
                            <div class="bid-row">
                                <p>Price</p>
                                <p class="bid-row-price"> <span>€</span><?php echo  number_format($item->get_buy_now_price(), 2, ',', '.');?></p>
                            </div>
                        <?php endif; ?>
                        <!--- AFFICHAGE DES PRIX -->
                        <!--- AFFICHAGE DES PRIX -->
                    <!-- Utilisateur créateur. -->
                    <?php elseif($item->get_owner_id() == $user->get_id()) : ?>
                        <!-- Vente directe uniquement et n'a pas déjà reçu une enchère. -->
                        <?php if($item->has_buy_now() && !$item->has_auction()):?>
                            <div class="bid-row">
                                <p>Price</p>
                                <p class="bid-row-price"> <span>€</span><?php echo  number_format($item->get_buy_now_price(), 2, ',', '.');?></p>
                            </div>
                        <!-- Enchère uniquement et (n'a pas déjà reçu une enchère);On s'en fou. -->
                        <?php elseif($item->has_auction() && !$item->has_buy_now()):?>
                            <div class="bid-row">
                                <p>Bid</p>
                                <p class="bid-row-price"> <span>€</span><?php echo  number_format($item->get_starting_bid(), 2, ',', '.');?></p>
                            </div>
                        <!-- Enchère et paiement immédiat. -->
                        <?php elseif($item->has_auction() && $item->has_buy_now()):?>
                            <div class="bid-row">
                                <p>Price</p>
                                <p class="bid-row-price"> <span>€</span><?php echo  number_format($item->get_buy_now_price(), 2, ',', '.');?></p>
                            </div>
                            <div class="bid-row">
                                <p>Bid</p>
                                <p class="bid-row-price"> <span>€</span><?php echo  number_format($item->get_starting_bid(), 2, ',', '.');?></p>
                            </div>
                        <?php endif; ?>
                        <!--- AFFICHAGE DES PRIX -->
                        <!--- AFFICHAGE DES PRIX -->
                    <?php endif;?>
                <!-- Annonce fermée-->
                <!-- Visible par le gagnant et le propriètaire. -->
                <?php else: ?>
                    <!--- AFFICHAGE DES PRIX -->
                    <!--- AFFICHAGE DES PRIX -->
                    <!-- Le gagnant et le propriètaire ont la même affiche ici.-->
                    <?php if($item->has_auction() && $item->has_buy_now()):?>
                        <div class="bid-row">
                            <p>Current Bid</p>
                            <?php $resultat = $item->get_max_bid() !== null ? $item->get_max_bid() : $item->get_starting_bid() ; ?>
                            <p class="bid-row-price"> <span>€</span><?php echo  number_format( $resultat, 2, ',', '.');?></p>
                        </div>
                        <div class="buy-row">
                            <p>Buy Now</p>
                            <p class="buy-row-price"> <span>€</span><?php echo  number_format($item->get_buy_now_price(), 2, ',', '.');?></p>
                        </div>
                    <?php elseif($item->has_auction() && !$item->has_buy_now()): ?>
                        <div class="bid-row">
                            <p>Current Bid</p>
                            <?php $resultat = $item->get_max_bid() !== null ? $item->get_max_bid() : $item->get_starting_bid() ; ?>
                            <p class="bid-row-price"> <span>€</span><?php echo  number_format( $resultat, 2, ',', '.');?></p>
                        </div>
                    <?php elseif($item->has_buy_now() && !$item->has_auction()): ?>
                        <div class="buy-row">
                            <p>Buy Now</p>
                            <p class="buy-row-price"> <span>€</span><?php echo  number_format($item->get_buy_now_price(), 2, ',', '.');?></p>
                        </div>
                    <?php endif;?>
                <?php endif; ?>
                <!--- AFFICHAGE DES PRIX -->
                <!--- AFFICHAGE DES PRIX -->
                <hr> <!-- Horizontal Rule -->
                FOOOOORRRRRMMMUULLAIIREEEEaa
                <form action="item/buy_bid/<?= $item->get_id() ?>" method="POST">
                        <!--- Annonce ouverte. --->
                        <?php if(!$item->should_close()):?>
                            <!--- Utilisateur non créateur. --->
                            <?php if($item->get_owner_id() != $user->get_id()): ?>
                                <!-- BARRE POUR TAPER LE PRIX. -->
                                <!-- BARRE POUR TAPER LE PRIX. -->
                                <div class="custom-input-group">
                                    <span class="input-prefix">€</span> <!-- Input Group/(groupe de saisie) -->
                                    <!-- 1) Pas encore d'enchère et pas d'achat immédiat.-->
                                    <?php if(!$item->is_has_bids() && !$item->has_buy_now() && $item->has_auction()):?>
                                        <input name="bid_amount" type="number" value="<?= $item->get_starting_bid() ?>">
                                    <!-- 1) BIS A déjà des enchères et pas d'achat immédiat.-->
                                    <?php elseif($item->has_auction() && $item->is_has_bids() && !$item->has_buy_now()):?>
                                        <?php $resultat = $item->get_max_bid() !== null ? $item->get_max_bid() : $item->get_starting_bid() ; ?>
                                        <input name="bid_amount" type="number" value="<?=$resultat+1 ?>">
                                    <!-- 2) Enchères existantes, prix d'achat immédiat non atteint. -->
                                    <?php elseif($item->is_has_bids() && $item->has_buy_now() && !$item->get_first_winning_buy_now_bid()): ?>
                                        <?php $resultat = $item->get_max_bid() !== null ? $item->get_max_bid() : $item->get_starting_bid() ; ?>
                                        <input name="bid_amount" type="number" value="<?=$resultat+1 ?>">
                                    <!-- 2) BIS Enchères existantes, prix d'achat immédiat non atteint. -->
                                    <?php elseif($item->has_auction() && !$item->is_has_bids() && $item->has_buy_now() && !$item->get_first_winning_buy_now_bid()): ?>
                                        <?php $resultat = $item->get_max_bid() !== null ? $item->get_max_bid() : $item->get_starting_bid() ; ?>
                                        <input name="bid_amount" type="number" value="<?=$resultat+1 ?>">
                                    <!-- 3) Vente directe uniquement et n'a pas déjà reçu une enchère. -->
                                    <?php elseif($item->has_buy_now() && !$item->has_auction() && !$item->is_has_bids()):?>
                                        <input name="bid_amount" type="number" value="<?= $item->get_buy_now_price()?>">
                                    <?php else : ?>
                                        <input name="bid_amount" type="number" value="<?= $item->get_starting_bid() ?>">
                                    <?php endif; ?>
                                    <!-- BARRE POUR TAPER LE PRIX. -->
                                    <!-- BARRE POUR TAPER LE PRIX. -->
                                </div>
                            <!-- Utilisateur créateur. -->
                            <?php elseif($item->get_owner_id() == $user->get_id()) : ?>
                                <!-- Vente directe uniquement et n'a pas déjà reçu une enchère. -->
                                <?php if($item->has_buy_now() && !$item->has_auction()):?>
                                    <button class="btn buy-now-direct-only-owner w-100">
                                        <i class="bi bi-cart-dash me-2"></i> Buy now
                                    </button>
                                    <p class="buy-now-direct-only-owner-phrase">You cannot bid on your own listing.</p>
                                <!-- Enchère uniquement et (n'a pas déjà reçu une enchère);On s'en fou. -->
                                <?php elseif($item->has_auction() && !$item->has_buy_now()):?>
                                    <button class="btn bid-direct-only-owner w-100">
                                        <i class="bi bi-cart4 me-2"></i>Current bid
                                    </button>
                                    <p class="bid-direct-only-owner-phrase">You cannot purchase your own listing.</p>
                                <!-- Enchère et paiement immédiat. -->
                                <?php elseif($item->has_auction() && $item->has_buy_now()):?>
                                    <button class="btn buy-now-direct-only-owner w-100">
                                        <i class="bi bi-cart-dash me-2"></i> Buy now
                                    </button>
                                    <button class="btn bid-direct-only-owner w-100">
                                        <i class="bi bi-cart4 me-2"></i>Current bid
                                    </button>
                                    <p class="bid-direct-only-owner-phrase">You cannot bid or purchase your own listing.</p>
                                <?php endif; ?>
                            <?php endif; ?>
                            <!-- Utilisateur créateur -->
                        <?php endif; ?>
                    <!-- Boutons pour faires les achats.-->
                        <!-- w-100" force à prendre toute la largeur de la carte. -->
                        <!-- la classe magique de Bootstrap btn : -->
                        <!--    le joli padding ni les coins arrondis par défaut. -->
                    <!--- Annonce ouverte. --->
                    <?php if(!$item->should_close()):?>
                        <!--- Utilisateur non créateur. --->
                        <!--- BOUTON POUR ACHETER/SOUMETTRE -->
                        <!--- BOUTON POUR ACHETER/SOUMETTRE -->
                        <?php if($item->get_owner_id() != $user->get_id()): ?>
                            <!-- 1) Pas encore d'enchère et pas d'achat immédiat.-->
                            <?php if(!$item->is_has_bids() && !$item->has_buy_now() && $item->has_auction()):?>
                                <button  type="submit" name="action" value="bid" class="btn bid-row-btn w-100">Place Bid</button>
                            <!-- 1) BIS A déjà des enchères et pas d'achat immédiat.-->
                            <?php elseif($item->has_auction() && $item->is_has_bids() && !$item->has_buy_now()):?>
                                <button type="submit" name="action" value="bid" class="btn bid-current-only w-100">
                                    <i class="bi bi-cart-dash me-2"></i>Place Bid
                                </button>
                            <!-- 2) Enchères existantes, prix d'achat immédiat non atteint. -->
                            <?php elseif($item->is_has_bids() && $item->has_buy_now() && !$item->get_first_winning_buy_now_bid()): ?>
                                <button type="submit" name="action" value="bid" class="btn bid-row-btn w-100">Place Bid</button>
                                <button type="submit" name="action" value="buy_now" class="btn buy-row-btn w-100">Buy Now at <span>€</span><?php echo $item->get_buy_now_price()?></button>
                            <!-- 2) BIS Enchères existantes, prix d'achat immédiat non atteint. -->
                            <?php elseif($item->has_auction() && !$item->is_has_bids() && $item->has_buy_now() && !$item->get_first_winning_buy_now_bid()): ?>
                                <button type="submit" name="action" value="bid" class="btn bid-row-btn w-100">Place Bid</button>
                                <button type="submit" name="action" value="buy_now" class="btn buy-row-btn w-100">Buy Now at <span>€</span><?php echo $item->get_buy_now_price()?></button>
                            <!-- 3) Vente directe uniquement et n'a pas déjà reçu une enchère. -->
                            <?php elseif($item->has_buy_now() && !$item->has_auction() && !$item->is_has_bids()):?>
                                <button type="submit" name="action" value="buy_now" class="btn buy-now-direct-only w-100">
                                    <i class="bi bi-cart-dash me-2"></i> Buy now
                                </button>
                            <?php endif; ?>
                            <!--- BOUTON POUR ACHETER/SOUMETTRE -->
                            <!--- BOUTON POUR ACHETER/SOUMETTRE -->
                        <?php endif; ?>
                </form>
                FOOOOORRRRRMMMUULLAIIREEEEbbbbb
                <!-- Annonce fermée-->
                <!-- Visible par le gagnant et le propriètaire. -->
                <?php else: ?>
                    <!-- Le gagnant -->
                    <?php $winner = $item->has_a_winner(); ?>
                    <!-- Si on est connecté entant que gagnant. -->
                    <?php if($winner instanceof Bids && $winner->get_owner_user()->get_id() == $user->get_id()):?>
                        <?php if($item->has_auction() || $item->has_buy_now()):?>
                            <p class="winner-phrase">Congratulations!You purchased this for <span>€</span><?php echo number_format($item->get_max_bid(), 2, ',', '.'); ?>.</p>
                        <?php endif;?>
                    <!-- Si on est connecté entant que prorpiètaire. -->
                    <?php elseif($item->get_owner_user()->get_id() == $user->get_id()): ?>
                        <!-- S'il y a un gagnant.-->
                        <?php if($winner instanceof Bids):?>
                            <p class="winner-phrase"><span><?php echo $winner->get_owner_user()->get_pseudo()?> won this for </span><?php echo number_format($item->get_max_bid(), 2, ',', '.'); ?>.</p>
                        <?php elseif(!$winner):?>
                            <p class="defeat-phrase"><span>This listing ended without a buyer.</p>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
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
                    <?php if($owner_user->get_picture_path() !=""): ?>
                        <div class="profile-picture">
                            <img src="<?=$owner_user->get_picture_path() ?>" alt="User Picture">
                        </div>
                    <?php else : ?>
                        <div class="profile-picture">
                            <i class="bi bi-person-circle"></i>
                        </div>
                    <?php endif; ?>
                    <div class="info-profile">
                        <p><?php echo  $owner_user->get_pseudo(); ?></p>
                        <p><?php echo  $owner_user->get_role_to_strng(); ?></p>
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
