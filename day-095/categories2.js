// Equivalent de document.onreadystatechange{if (document.readyState === 'complete'){}}
$(async function () {
    // On vide le tableau des catégories en php.
    $("#tableau_categories").empty();
    let list_categories_co = await get_all_categories();
    console.log(list_categories_co);
    // On vérifie que le JSon qu'on a, a bien un résultat.
    if(list_categories_co && Object.keys(list_categories_co).length > 0){
        // Ajout du tableau des catégories en html.
        $("#tableau_categories").html(tableau_categories_html(list_categories_co));
        // On cache la dernière ligne qui permet d'ajouter une nouvelle catégorie.
        $("#add_new_category_input").hide();
        // On supprime l'ancienne modale si elle exitait déjà pour éviter les doublons.
        $('#modal_for_delete').remove();
        // Ajout de la modal en html.
        $('body').append(model_for_delete());
        
        let id_category_to_delete = null;

        // Lorsqu'on clique sur le btn delete d'un categorie.
        // off : Oublie toutes les anciennes actions liées au clic sur .btn-delete.
        // Ecoute tous les btn ayant la classe btn_delete de la page MAIS n'exécute la fonction QUE si l'élément cliqué possède la classe .btn-delete.
        // $(document) car les btn sont générés après le chargement initial de la page.
        $(document).off('click', '.btn_delete_category').on('click', '.btn-delete-category', function(e) {
            e.preventDefault(); // Sécurité : empêche le bouton de faire autre chose (ex: comportement par défaut).
            // L'id de la catégorie sur laquelle on a cliqué.
            id_category_to_delete = $(this).data('id_category');
            // console.log("Category ID to delete selected: " + id_category_to_delete);
            // On ordonne à la modale de s'ouvrir
            $('#modal_for_delete').modal('show');
        });
        /*
        Si je mets dans le $(document).off('click', '.btn-delete').on('click', '.btn-delete', function(e){});
        Malheureusement, ça va ajouter un nouvel "écouteur" sur le bouton de confirmation à chaque fois qu'on ouvre la modale.
        Si on ouvre la modale 3 fois, le bouton essayera de  supprimer 3 fois la catégorie, provoquant des erreurs en
        chaîne.
         */
        $(document).off('click', '#btn_confirm_delete_modal').on('click', '#btn_confirm_delete_modal', async function(e) {
            e.preventDefault(); // Sécurité : empêche le bouton de faire autre chose (ex: comportement par défaut).
            if (id_category_to_delete) {
                // console.log(id_category_to_delete);
                let resp = await delete_category(id_category_to_delete);
                // Si ça renvoie un json non vide => echo "true".
                if( resp && Object.keys(resp).length > 0){
                    $('#modal_for_delete').modal('hide');
                    // Force le rechargement depuis le serveur (ignore le cache).
                    location.reload(true);
                }
                console.log("erreur lors la suppression de la catégorie dans la BDD !");

            }
        });

        // lorsqu'on clique sur le btn edit d'une catégorie.
        $(document).off('click', '.btn_edit_category').on('click', '.btn_edit_category',function(e) {
            e.preventDefault(); // Sécurité : empêche le bouton de faire autre chose (ex: comportement par défaut).
            manage_btn_edit_category($(this));
        });

        // lorsqu'on clique sur le btn + pour ajouter une nouvelle categorie.
        $(document).on("click", '#btn_add_new_category_btn', function() {
            console.log("aa");
            manage_input_add_new_category($("#add_new_category_input"));
        });
    }

});


// Donne les catégories + les infos supplémentaires.
async function get_all_categories() {
    try {
        // La requête attend ici la réponse avant de passer à la suite.
        let response = await $.ajax({
            url: 'categories/get_all_categories_and_co',
            type: 'POST', // type de variable qu'on va recevoir côté php. ici on va rien donnée.
            data: { }, // les données qu'on va recevoir côté php. ici on va rien donnée.
            dataType: 'json' // le format au quel moi je recois la côté js.
        });
        // Tout ce qui est ici se passe APRÈS la réussite de la requête.
        // console.log(response);
        return response;
    } catch (error) {
        // Tout ce qui est ici se passe en cas d'erreur.
        console.error("Erreur critique :", error);
    }
}
function tableau_categories_html(list_categories_co){
    return `
            <tbody id="list_categories">
                ${list_categories_co.length > 0 ?
                    // Ex d'usage : ${idx}.
                    list_categories_co.map((element,idx) => ` 
                        <!--Une ligne du tableau correspond à un formulaire.-->
                        <form action="categories/manage_categorie" method="POST" class="category-row">
                            <tr>
                                <td><span class="category-name_${idx}" data-name="${element.name}" data-nbr_items="${element.nbr_items_for_category}"> ${element.name} (${element.nbr_items_for_category}) </span></td>
                                <td> <button type="button" name="action" value="move_up" class="btn_edit_category" data-idx="${idx}">Edit</button> </td>
                                <!--Logique ternaire imbriquée : -->
                                <!--condition1 ? vrai1 : condition2 ? vrai2 : faux_final; -->
                                ${element.nbr_items_for_category === 0 ? 
                                    `<td> <button type="button" name="action" value="delete" class="btn-delete-category" data-id_category="${element.id}" >Delete</button> </td>`
                                : 
                                    `<td> <button type="submit" name="action" value="delete" class="btn-delete-category" disabled>Delete</button> </td>`
                                }
                                <input type="hidden" name="category_id" value="${element.id}">
                            </tr>
                        </form>
                    `).join('') // Colle tous les morceaux ensemble sans rien mettre entre eux. Les virgules entre chque élément du tab disparaitront.
                : 
                ""
                }
            </tbody>
            <tfoot id="tfoot_new_add_category">
                <form action="categories/add" method="POST" class="add-row">
                    <tr>
                        <td colspan="2"> <input type="text" name="add_new_category_input" value="new_categorie_name" id="add_new_category_input"></td>
                        <td><button type="button" name="add_categorie" value="" id="btn_add_new_category_btn">+</button></td>
                    </tr>
                </form>
            </tfoot>
           `;
}

function model_for_delete(){
    return `
<div class="modal fade" id="modal_for_delete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modalDeleteLabel">
          Delete Category
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <p>
          Are you sure you want to delete this category?
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-cancel-delete" data-bs-dismiss="modal">
          Cancel
        </button>
        <button type="button" class="btn btn-confirm-delete" id="btn_confirm_delete_modal">
          Delete
        </button>
      </div>
    </div>
  </div>
</div>
    `;
}


// Donne les catégories + les infos supplémentaires.
async function delete_category(category_id) {
    try {
        // La requête attend ici la réponse avant de passer à la suite.
        // On utilise le format d'URL attendu par le framework : controller/action/param1
        let response = await $.ajax({
            url: 'categories/delete_service/' + category_id,
            type: 'GET',
            dataType: 'json'
        });
        // Tout ce qui est ici se passe APRÈS la réussite de la requête.
        console.log(response);
        return response;
    } catch (error) {
        // Tout ce qui est ici se passe en cas d'erreur.
        console.error("Erreur critique :", error);
    }
}

// Gére toutes les actions qu'entraine btn edit.
function manage_btn_edit_category(btn_edit_balise) {
    // On remonte à la ligne (<tr>) qui contient le bouton cliqué.
    let row = btn_edit_balise.closest('tr');
    let idx = btn_edit_balise.data("idx");

    // On trouve le span que l'on cherche.
    let target_span = row.find(`.category-name_${idx}`);

    // On récupère les données d'origine.
    let old_name = target_span.data('name');
    let nbr_items = target_span.data('nbr_items');

    // On remplace le span du <td> par l'input.
    let input_category = `<input type="text" name="categorie_name_${idx}" value="${old_name}" data-idx="${idx}"  data-nbr="${nbr_items}" />`;
    target_span.replaceWith(input_category);

    // --------------------------------------------------------------
    // Gestion de l'evenement sur le input_category :
    let input_category_in_dom = row.find(`input[name="categorie_name_${idx}"]`);
    manage_input_category(input_category_in_dom);
    // --------------------------------------------------------------
    // On ajoute le bouton Cancel après le bouton Edit (ou on le remplace).
    let btn_cancel_html = `<button type="button" class="btn btn-secondary btn-cancel-edit ms-2 btn_cancel_${idx}" data-idx="${idx}" data-old_name="${old_name}" data-nbr_items="${nbr_items}">Cancel</button>`;
    btn_edit_balise.after(btn_cancel_html);

    // On cache le btn edit.
    btn_edit_balise.hide();

    // lorsqu'on clique sur le btn cancel.
    $(document).off('click', `.btn_cancel_${idx}`).on('click', `.btn_cancel_${idx}`, function(e) {
        e.preventDefault();

        let row = $(this).closest('tr');
        let idx = $(this).data('idx');
        let old_name = $(this).data('old_name');
        let nbr_items = $(this).data('nbr_items');

        // On reconstruit le HTML du span d'origine.
        let original_span_html = `<span class="category-name_${idx}" data-name="${old_name}" data-nbr_items="${nbr_items}"> ${old_name} (${nbr_items}) </span>`;

        // On remet le span à la place de l'input.
        row.find(`input[name="categorie_name_${idx}"]`).replaceWith(original_span_html);

        // On réaffiche le bouton "Edit".
        row.find('.btn_edit_category').show();
        // Et on supprime le bouton "Cancel".
        $(this).remove();
        // On recharge la page.

        // Nettoyage des erreurs précédentes s'il en a.
        row.find('.error-message').remove();
        // location.reload(true);
    });


    function manage_input_category(input_category){
        // On met le focus dessus automatiquement pour que l'utilisateur puisse taper.
        input_category.focus();
        // On récupre déjà l'ancien nom de la catégorie.
        let old_name_category = input_category.val();
        // On écoute quand l'utilisateur sort du champ (blur) :
        input_category.on('blur', async function() {
            // On récupère la valeur tapée et on enlève les espaces inutiles (trim).
            let new_name_category = $(this).val().trim();
            console.log("L'utilisateur a fini de taper : " + new_name_category);

            // Validation ASYNC.
            // On remonte à la ligne (<tr>) qui contient le bouton cliqué.
            let row = input_category.closest('tr');
            // Nettoyage des erreurs précédentes.
            row.find('.error-message').remove();
            $(this).removeClass('is-invalid');
            // Si le nom n'a pas changé, on annule simplement l'édition.
            if (new_name_category === old_name_category) {
                // On restaure le span.
                let row = input_category.closest('tr');
                let idx =  input_category.data('idx');
                let old_name = old_name_category;
                let nbr_items = input_category.data('nbr');

                // On reconstruit le HTML du span d'origine.
                let original_span_html = `<span class="category-name_${idx}" data-name="${old_name}" data-nbr_items="${nbr_items}"> ${old_name} (${nbr_items}) </span>`;

                // On remet le span à la place de l'input.
                row.find(`input[name="categorie_name_${idx}"]`).replaceWith(original_span_html);
                // On réaffiche le bouton "Edit" et on supprime le bouton "Cancel".
                row.find('.btn_edit_category').show();
                // On supprime le btn cancel.
                row.find(`.btn_cancel_${idx}`).remove();
                return;
            }
            let id_category = row.find(`input[name="category_id"]`).val();
            // Appel AJAX au service de validation/mise à jour.
            let result = await validate_and_update_category_name(id_category, new_name_category);
            if (result.success) {
                // Si c'est ok on recharge la page.
                location.reload(true);
            } else {
                // Erreur renvoyée par le serveur.
                $(this).addClass('is-invalid');
                // On affiche le messge d'erreur après l'input.
                $(this).after(`<span class="error-message text-danger d-block">${result.message}</span>`);
            }
            // Validation SYNC.
            // // On cherche s'il y a déjà un message d'erreur pour le supprimer (nettoyage).
            // row.find('.error-message').remove();
            //
            // // Test synchrone : longueur du nom.
            // if (new_name_category.length < 3) {
            //     // On crée un petit span d'erreur en rouge.
            //     let errorHtml = `<span class="error-message text-danger d-block" style="font-size: 0.8rem;">Le nom doit faire au moins 3 caractères.</span>`;
            //     // On l'ajoute juste après l'input.
            //     $(this).after(errorHtml);
            //     // On met une bordure rouge sur l'input pour bien montrer l'erreur.
            //     $(this).addClass('is-invalid');
            //     return; // On s'arrête ici, pas besoin d'appeler le serveur.
            // }
            // // Si on arrive ici, c'est que le test local est OK !
            // $(this).removeClass('is-invalid');
            // console.log("Validation locale OK, on va appeler le serveur pour : " + new_name_category);
        });
    }
}


async function validate_and_update_category_name(id_category , new_name_category){
    try {
        // La requête attend ici la réponse avant de passer à la suite.
        let response = await $.ajax({
            url: 'categories/update_category_service',
            type: 'POST', // type que recevra côté php.
            data: { id_category: id_category  ,new_name_category:new_name_category}, // ce qu'elle va recevoir.
            dataType: 'json' // le format au quel moi je recois la côté js.
        });
        // Tout ce qui est ici se passe APRÈS la réussite de la requête.
        // ex : de renvoie dans ce contexte : json_encode(["success" => true]);
        return response;
    } catch (error) {
        // Tout ce qui est ici se passe en cas d'erreur.
        console.error("Erreur critique :", error);
    }
}


// Gére les actions lorsqu'on clique sur le btn + d'add une nouvelle catégorie.
function manage_input_add_new_category(){
    let add_new_category_input = $("#add_new_category_input");
    // On montre l'intput pour ajouter une nouvelle catégorie.
    add_new_category_input.show();
    // On sélectionne le btn + pour ajouter le btn cancel à côté.
    let row = add_new_category_input.closest('tr');
    let btn_add_new_category_btn = row.find("#btn_add_new_category_btn");
    // Création du btn cancel.
    let btn_cancel_html = `<button type="button" class="btn btn-secondary btn-cancel-edit ms-2" id="btn_cancel_add_new_category">Cancel</button>`;
    // On place le btn cancel avant le btn + d'add une nouvelle catégorie.
    btn_add_new_category_btn.before(btn_cancel_html);
    // On cache le btn + d'add une nouvelle catégorie.
    btn_add_new_category_btn.hide();

    // lorsqu'on clique sur le btn cancel.
    $(document).off('click', `#btn_cancel_add_new_category`).on('click', `#btn_cancel_add_new_category`, function(e) {
        btn_add_new_category_btn.show();
        add_new_category_input.hide();

        // Nettoyage des erreurs précédentes s'il en a.
        row.find('.error-message').remove();
        row.find("#btn_cancel_add_new_category").remove();
    });

    // --------------------------------------------------------------
    // Gestion de l'evenement sur le add_new_category_input :
    manage_add_new_category_input(add_new_category_input);
    // --------------------------------------------------------------
}

function manage_add_new_category_input(add_new_category_input){
    // On met le focus dessus automatiquement pour que l'utilisateur puisse taper.
    add_new_category_input.focus();

    // On écoute quand l'utilisateur sort du champ (blur) :
    add_new_category_input.on('blur', async function() {
        // On récupère la valeur tapée et on enlève les espaces inutiles (trim).
        let new_name_category = $(this).val().trim();
        console.log("L'utilisateur a fini de taper : " + new_name_category);

        // Validation ASYNC.
        // On remonte à la ligne (<tr>) qui contient l'input de add_new_category_input.
        let row = add_new_category_input.closest('tr');
        // Nettoyage des erreurs précédentes.
        row.find('.error-message').remove();
        $(this).removeClass('is-invalid');

        let add_new_category_input_name = add_new_category_input.val();
        // Appel AJAX au service de validation/mise à jour.
        let result = await add_new_category_name(add_new_category_input_name);
        if (result.success) {
            // Si c'est ok on recharge la page.
            location.reload(true);
        } else {
            // Erreur renvoyée par le serveur.
            $(this).addClass('is-invalid');
            // On affiche le messge d'erreur après l'input.
            $(this).after(`<span class="error-message text-danger d-block">${result.message}</span>`);
        }
    });
}

async function add_new_category_name(add_new_category_input_name){
    try {
        // La requête attend ici la réponse avant de passer à la suite.
        let response = await $.ajax({
            url: 'categories/add_service',
            type: 'POST', // type que recevra côté php.
            data: { add_new_category_input_name: add_new_category_input_name}, // ce qu'elle va recevoir.
            dataType: 'json' // le format au quel moi je recois la côté js.
        });
        // Tout ce qui est ici se passe APRÈS la réussite de la requête.
        // ex : de renvoie dans ce contexte : json_encode(["success" => true]);
        return response;
    } catch (error) {
        // Tout ce qui est ici se passe en cas d'erreur.
        console.error("Erreur critique :", error);
    }
}