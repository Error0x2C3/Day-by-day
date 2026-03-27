<?php

function render_items_section(string $title, array $items): void
{
?>
    <div class="mb-5">

        <h3 class="mb-3"><?= htmlspecialchars($title) ?></h3>

        <div class="row row-cols-1 row-cols-md-3 g-4">

            <?php foreach ($items as $item): ?>

                <div class="col">
                    <div class="card h-100">

                        <img
                            src="<?= htmlspecialchars($item['picture_path'] ?? 'uploads/items/default.png') ?>"
                            class="card-img-top"
                            alt="Item image"
                        >

                        <div class="card-body">
                            <h5 class="card-title">
                                <?= htmlspecialchars($item['title']) ?>
                            </h5>

                            <p class="card-text mb-1">
                                <strong>End:</strong>
                                <?= htmlspecialchars($item['end_at']) ?>
                            </p>

                            <?php if ($item['buy_now_price'] !== null): ?>
                                <p class="card-text">
                                    <strong>Buy now:</strong>
                                    <?= htmlspecialchars($item['buy_now_price']) ?> €
                                </p>
                            <?php endif; ?>
                        </div>

                        <div class="card-footer text-center">
                            <a href="item/details/<?= $item['id'] ?>"
                               class="btn btn-outline-primary btn-sm">
                                View
                            </a>
                        </div>

                    </div>
                </div>

            <?php endforeach; ?>

        </div>
    </div>
<?php
}
