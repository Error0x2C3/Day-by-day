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
    $back_link = ($item !== null) ? "historynav/manage_btn_back/back/" . $item->get_id() : "";
    echo Navigation::top_bar("item/add_edit_item_view", $page_title, $back_link, "item-form");
    ?>
        <main class="page-content p-3">
        <?php if (isset($errors["global"])): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($errors["global"]) ?>
            </div>
        <?php endif; ?>
            <?php if ($page_title !== "add_item"): ?>
            <!-- Cas ou on edit un item -->
            <form id="item-form" action="Item/save_item_from_form" method="POST">
            <?php else: ?>
            <form id="item-form" action="Item/add_item_from_form" method="POST">
            <?php endif; ?>
                <div class="mb-4 border p-3">
                    <h4>Basic Information</h4>
                    <div class="mb-3">
                    <input type="hidden" name="item_id" value="<?= ($item !== null) ? $item->get_id() : '' ?>">                        <label class="form-label">Item Title *</label>
                        <input type="text"
                         name="title"
                        class="form-control <?= !empty($errors['title']) ? 'is-invalid' : '' ?>"
                        placeholder="Enter item title"
                        value="<?= isset($title) ? htmlspecialchars($title) : '' ?>"
                        required>
                        <?php if (!empty($errors['title'])): ?>
                            <div class="text-danger small">
                                <?= htmlspecialchars($errors['title']) ?>
                            </div>
                        <?php elseif (isset($errors["global"]) && $press_save && str_contains($errors["global"], "titre")): ?>
                            <div class="text-danger small">
                                <?= htmlspecialchars($errors["global"]) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control <?= !empty($errors['description']) ? 'is-invalid' : '' ?>" rows="4"><?= isset($description) ? htmlspecialchars($description) : '' ?></textarea>
                        <?php if (!empty($errors['description'])): ?>
                            <div class="text-danger small">
                                <?= htmlspecialchars($errors['description']) ?>
                            </div>
                        <?php elseif (isset($errors["global"]) && str_contains($errors["global"], "description")): ?>
                            <div class="text-danger small">
                                <?= htmlspecialchars($errors["global"]) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sale Duration (days) *</label>
                        <input type="number"
                        name="duration"
                        class="form-control <?= !empty($errors['duration']) ? 'is-invalid' : '' ?>"
                        value="<?= isset($duration) ? $duration : '7' ?>"
                        required>
                        <?php if (!empty($errors['duration'])): ?>
                            <div class="text-danger small">
                                <?= htmlspecialchars($errors['duration']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mb-4 border p-3">
                    <h4>Sale Type</h4>

                    <?php if (!empty($errors['sale_type'])): ?>
                        <div class="alert alert-warning">
                            <?= htmlspecialchars($errors['sale_type']) ?>
                        </div>
                    <?php endif; ?>

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
                            <?php if (isset($errors["global"]) && str_contains($errors["global"], "règles")): ?>
                                <div class="text-danger small">
                                    <?= htmlspecialchars($errors["global"]) ?>
                                </div>
                            <?php endif; ?>
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
                            <?php if (!empty($errors['buy_now_price'])): ?>
                                <div class="text-danger small">
                                    <?= htmlspecialchars($errors['buy_now_price']) ?>
                                </div>
                            <?php elseif (isset($press_save) && $press_save && !empty($errors['bool_buy_now_price_UP_starting_bid'])): ?>
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
        <!-- FOOTER NAV -->
        <?php
        require_once 'view/view_bottom_bar.php';
        //echo Navigation::bottom_nav("profile",$user ?? null);
        //echo Navigation::bottom_bar();
        //?>
    </body>
</html>
