<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un article</title>
    <base href="<?= $web_root ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/navigation.css">
    <link rel="stylesheet" href="css/add_edit_item.css">
</head>
    <body class="bg-dark text-light">
    <?php

    echo Navigation::top_bar("item/add_edit_item_view","Add item","historynav/manage_btn_back/back/".$item->get_id(),"item-form");
    ?>
        <main class="page-content p-3">
            <form id="item-form" action="Item/save_item_from_form" method="POST">
                <div class="mb-4 border p-3">
                    <h4>Basic Information</h4>
                    <div class="mb-3">
                        <label class="form-label">Item Title *</label>
                        <input type="text"
                         name="title"
                        class="form-control"
                        placeholder="Enter item title"
                        value="<?= isset($title) ? htmlspecialchars($title) : '' ?>"
                        required>
                        <?php if (isset($press_save) && $press_save && !empty($errors["bool_text_min_3_max_255"])): ?>
                            <div class="text-danger small">
                            <?= htmlspecialchars($errors["bool_text_min_3_max_255"])  ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4"><?= isset($description) ? htmlspecialchars($description) : '' ?></textarea>

                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sale Duration (days) *</label>
                        <input type="number"
                        name="duration"
                        class="form-control"
                        value="<?= isset($duration) ? $duration : '7' ?>"
                        required>
                    </div>
                </div>

                <div class="mb-4 border p-3">
                    <h4>Sale Type</h4>

                    <div class="border p-3 mb-3">
                        <h5>Option 1: Auction</h5>

                        <div class="mb-3">
                            <label class="form-label">Starting Bid</label>
                            <div class="input-group">
                            <input type="number"
                                name="starting_bid"
                                class="form-control"
                                value="<?= isset($starting_bid) ? $starting_bid : '' ?>">
                            <span class="input-group-text">€</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Instant Purchase Price (optional)</label>
                            <div class="input-group">
                            <input type="number"
                                name="buy_now_price"
                                class="form-control"
                                value="<?= isset($buy_now_price) ? $buy_now_price : '' ?>">
                            <span class="input-group-text">€</span>
                            </div>
                            <?php if ( isset($press_save) && $press_save && !empty($errors['bool_buy_now_price_UP_starting_bid'])): ?>
                                <div class="text-danger small">
                                    <?= htmlspecialchars($errors['bool_buy_now_price_UP_starting_bid']) ?>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>

                    <div class="border p-3">
                        <h5>Option 2: Direct Sale</h5>

                        <div class="mb-3">
                            <label class="form-label">Sale Price</label>
                            <div class="input-group">
                            <input type="number"
                                name="direct_price"
                                class="form-control"
                                value="<?= isset($direct_price) ? $direct_price : '' ?>">
                            <span class="input-group-text">€</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </main>
        <?php
        echo Navigation::bottom_nav("browseItems");
        echo Navigation::bottom_bar();
        ?>
    </body>
</html>
