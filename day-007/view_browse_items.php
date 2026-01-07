<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Browse Items</title>
    <base href="<?= $web_root ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/navigation.css">
</head>


<body class="bg-dark text-light">

<?php
echo Navigation::top_bar("item/browse_items_view","Browse");
?>

<?php
$monTableau = ["item/browse_items_view","item/add_edit_item_view"];
$donneesCodees = base64_encode(json_encode($monTableau));
?>
<a href="historynav/manage_btn_back/forward/<?= $donneesCodees?>"> ajouter un item</a>

<a href="historynav/test/<?=  $donneesCodees?>"> ajouter un item</a>
<main class="page-content p-3">
    <div class="container py-4">
        <h2 class="h5 mb-4 text-primary-emphasis">Items I'm Participating In</h2>
        <div class="row g-4 mb-5">
        </div>

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
