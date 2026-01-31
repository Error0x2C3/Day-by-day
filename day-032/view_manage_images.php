<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <title>Manage Images</title>

    <!-- Bootstrap -->
    <base href="<?= $web_root ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/navigation.css">
</head>

<body class="text-light custom-bg">
<?php
echo Navigation::top_bar("item/browse_items_view","Browse");
?>
<main class="page-content p-3">
    <div class="container py-4">

        <!-- Header -->
        <div class="d-flex align-items-center mb-4">
            <a href="#" class="btn btn-outline-light me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h3 class="mb-0 text-primary">
                Manage Images for "<span class="text-light"><?= $titre ?></span>"
            </h3>
        </div>

        <!-- Add new images -->
        <div class="card bg-secondary text-light mb-4">
            <div class="card-header fw-bold">
                Add New Images
            </div>
            <div class="card-body">
                <form method="post"
                      action="ManageImages/upload_images"
                      enctype="multipart/form-data">

                    <div class="mb-3">
                        <label class="form-label">Select Images</label>
                        <input type="file"
                               class="form-control"
                               name="images[]"
                               multiple>
                    </div>

                    <input type="hidden" name="item_id" value="<?= $item_id ?>">

                    <p class="text-light opacity-75 mb-3">
                        You can select multiple images (JPG, PNG, GIF, WebP).
                        Images will be added to the end of your current list.
                    </p>

                    <button type="submit" class="btn btn-primary">
                        Upload Images
                    </button>
                </form>
            </div>
        </div>

        <!-- Current images -->
        <div class="card bg-secondary text-light">
            <div class="card-header fw-bold">
                Current Images
            </div>
            <div class="card-body">

                <div class="d-flex gap-4 flex-wrap">

                    <?php foreach ($tab_photo as $index => $elem) { ?>

                        <div class="text-center">

                            <div class="bg-light rounded p-2 mb-2">
                                <img src="<?= $elem['picture_path'] ?>"
                                     class="img-fluid rounded"
                                     style="width:180px; height:auto;">
                            </div>

                            <div class="btn-group">

                                <!-- Move left -->
                                <button class="btn btn-outline-light btn-sm"
                                        <?= $elem['priority'] == 0 ? 'disabled' : '' ?>>
                                    <i class="bi bi-arrow-left"></i>
                                </button>

                                <!-- Move right -->
                                <button class="btn btn-outline-light btn-sm"
                                        <?= $elem['priority'] == count($tab_photo)-1 ? 'disabled' : '' ?>>
                                    <i class="bi bi-arrow-right"></i>
                                </button>

                                <!-- Delete -->
                                <button class="btn btn-danger btn-sm">
                                    <i class="bi bi-x-lg"></i>
                                </button>

                            </div>
                        </div>

                    <?php } ?>

                </div>

            </div>
        </div>

    </div>
</main>
</body>
</html>
