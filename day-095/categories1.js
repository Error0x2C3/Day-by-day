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
        // On supprime l'ancienne modale si elle exitait déjà pour éviter les doublons.
        $('#modal_for_delete').remove();
        // Ajout de la modal en html.
        $('body').append(model_for_delete());
        
        let id_category_to_delete = null;

        // off : Oublie toutes les anciennes actions liées au clic sur .btn-delete.
        // Ecoute tous les btn ayant la classe btn_delete de la page MAIS n'exécute la fonction QUE si l'élément cliqué possède la classe .btn-delete.
        $(document).off('click', '.btn-delete').on('click', '.btn-delete', function(e) {
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
        $(document).off('click', '#btn_confirm_delete').on('click', '#btn_confirm_delete', async function() {
            if (id_category_to_delete) {
                // console.log(id_category_to_delete);
                let resp = await delete_category(id_category_to_delete);
                // Si ça renvoie un json non vide => echo "true".
                if( resp && Object.keys(resp).length > 0){
                    $('#modal_for_delete').modal('hide');
                    // Force le rechargement depuis le serveur (ignore le cache)
                    location.reload(true);
                }
                console.log("erreur lors la suppression de la catégorie dans la BDD !");

            }
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
                                <td><input type="text" name="categorie_name" value="${element.name} (${element.nbr_items_for_category})" disabled> </td>
                                <td> <button type="button" name="action" value="move_up" >Edit</button> </td>
                                <!--Logique ternaire imbriquée : -->
                                <!--condition1 ? vrai1 : condition2 ? vrai2 : faux_final; -->
                                ${element.nbr_items_for_category === 0 ? 
                                    `<td> <button type="button" name="action" value="delete" class="btn-delete" data-id_category="${element.id}" >Delete</button> </td>`
                                : 
                                    `<td> <button type="submit" name="action" value="delete" class="btn-delete" disabled>Delete</button> </td>`
                                }
                                <input type="hidden" name="category_id" value="${element.id}">
                            </tr>
                        </form>
                    `).join('') // Colle tous les morceaux ensemble sans rien mettre entre eux. Les virgules entre chque élément du tab disparaitront.
                : 
                ""
                }
            </tbody>
            <tfoot id="add_category">
                <form action="categories/add" method="POST" class="add-row">
                    <tr>
                        <td colspan="2"> <input type="text" name="action" value="new_categorie_name"></td>
                        <td><button type="button" name="add_categorie" value="">+</button></td>
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
        <button type="button" class="btn btn-confirm-delete" id="btn_confirm_delete">
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