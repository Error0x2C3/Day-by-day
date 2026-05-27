<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Categories</title>
    <base href="<?= $web_root ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/navigation.css">
    <link rel="stylesheet" href="css/categories.css">

</head>
<body class="bg-dark text-light">
<?php
echo Navigation::top_bar("categories/categories_view/","Categories", "","");
?>
<main class="page-content p-3">
    <div class="page-content py-2">
        <table>
<!--            <caption>-->
<!--                Formation développeur·euse front-end 2021-->
<!--            </caption>-->
<!--            <thead>-->
<!--            <tr>-->
<!--                <th scope="col">Nom</th>-->
<!--                <th scope="col">Principal intérêt</th>-->
<!--                <th scope="col">Âge</th>-->
<!--            </tr>-->
<!--            </thead>-->
            <tbody>
            <?php if (!empty($list_categories) && isset($list_categories)): ?>
                <?php for($i=0; $i<count($list_categories);$i++): ?>
                    <!--Une ligne du tableau correspond à un formulaire.-->
                    <form action="categories/manage_categorie" method="POST" class="category-row">
                        <tr>
                            <td><input type="text" name="categorie_name" value="<?php echo $list_categories[$i]->get_name() ?>"> </td>
                            <?php if ($i == 0):?>
                                <td> <button type="submit" name="action" value="move_up" disabled >Up</button> </td>
                                <td> <button type="submit" name="action" value="move_down">Down</button> </td>
                            <?php elseif($i == count($list_categories)-1): ?>
                                <td> <button type="submit" name="action" value="move_up" >Up</button> </td>
                                <td> <button type="submit" name="action" value="move_down" disabled >Down</button> </td>
                            <?php else:?>
                                <td> <button type="submit" name="action" value="move_up" >Up</button> </td>
                                <td> <button type="submit" name="action" value="move_down">Down</button> </td>
                            <?php endif; ?>
                            <td> <button type="submit" name="action" value="save" class="btn-save">Save</button> </td>
                            <?php if(count(item_categories::get_items_category_by_category($list_categories[$i]->get_id())) == 0):?>
                                <td> <button type="submit" name="action" value="delete" class="btn-delete">Delete</button> </td>
                            <?php else:?>
                                <td> <button type="submit" name="action" value="delete" class="btn-delete" disabled>Delete</button> </td>
                            <?php endif; ?>
                            <input type="hidden" name="category_id" value="<?php echo $list_categories[$i]->get_id()?>">
                        </tr>
                        <!--Affichage du message d'erreur.-->
                        <?php if(isset($error_array_save_category) && isset($error_array_save_category["category_id"])&& isset($error_array_save_category["error"])):
                            // Il faut mieux récupérer l'id de la catégorie que l'instance elle-même car sera convertit en array.
                            $category_error = categories::get_categorie_by_id( (int)$error_array_save_category['category_id']);
                        ?>
                            <?php if($category_error->get_id() == $list_categories[$i]->get_id()): ?>
                                <tr>
                                    <td> <?php echo $error_array_save_category["error"];?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endif;?>

                    </form>
                <?php endfor; ?>

            <?php endif; ?>
            </tbody>
            <tfoot>
                <form action="categories/add" method="POST" class="add-row">
                    <tr>
                        <td colspan="4"> <input type="text" name="action" value="new_categorie_name"></td>
                        <td><button type="submit" name="add_categorie" value="">+</button></td>
                    </tr>
                    <?php if(isset($error_array_new_category["error"]) && isset( $error_array_new_category)):?>
                        <tr>
                            <td> <?php echo $error_array_new_category["error"];?></td>
                        </tr>
                    <?php endif;?>
                </form>
            </tfoot>
        </table>
    </div>
<main>
<!-- FOOTER NAV -->
<?php
require_once 'view/view_bottom_bar.php';
?>
</body>

</html>
