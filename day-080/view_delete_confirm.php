<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Delete confirmation</title>
    <base href="<?= $web_root ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/navigation.css">
    <link rel="stylesheet" href="css/delete_confirm.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

</head>

<body class="bg-dark text-light">
<?php
if(isset($item) && isset($btn_back)){
    echo Navigation::top_bar("item/delete_confirm_view/".$item->get_id(),"Delete confirmation","","");

}
?>
<main class="p-3 d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="page-content py-2 w-100 d-flex justify-content-center">
        <div class="card delete-card p-4 text-center">

            <div class="delete-icon mt-2">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#ff4d4d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="14" y2="15"></line>
                    <line x1="14" y1="11" x2="10" y2="15"></line>
                </svg>
            </div>

            <h4 class="delete-title">Are you sure?</h4>

            <hr class="custom-hr mb-4">

            <p class="delete-text mb-4">
                Do you really want to delete item <strong><?= $item->get_title()?></strong> by <?=$owner->get_full_Name()?> and all of its dependencies ?
                <br><br>
                This process cannot be undone.
            </p>

            <div class="d-flex justify-content-center gap-3 mb-2">
                <a href="<?=$btn_back[1]?>" type="button" class="btn btn-cancel rounded-3">Cancel</i></a>
                <!--$btn_back[0]; Ex: item/my_items_view/-->
                <a href="item/delete_item/<?=$item->get_id()?>/<?=Navigation::encode($btn_back[0])?>" type="button" class="btn btn-delete rounded-3">Delete</i></a>
            </div>

        </div>
    </div>
</main>
<?php
require_once 'view/view_bottom_bar.php';
?>
</body>
</html>
