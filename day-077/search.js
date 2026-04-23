// Equivalent de document.onreadystatechange{if (document.readyState === 'complete'){}}
$(async function () {
    let search_bar_html = `<div class="search-wrapper mb-4" id="search">
                                    <i class="bi bi-search"></i>
                                    <input id="fulltext-search" type="text" placeholder="Search items...">
                                  </div>`;
    // Je prends le premier élément ayant la classe container.
    $("#for_search").prepend(search_bar_html);
    // Pour avoir le focus dessus dès le chargement de la page.
    $("#fulltext-search").focus();
    $('#fulltext-search').on('input', async function () {
        let search_content = $(this).val().toLowerCase(); // On récupére la version minuscule.
        let test = await supprimerMessage(search_content);
        console.log(test);
    });
});

async function supprimerMessage(word) {
    try {
        // La requête attend ici la réponse avant de passer à la suite.
        let response = await $.ajax({
            url: 'item/get_card_search_service',
            type: 'POST',
            data: { word_search: word },
            dataType: 'json'
        });
        // Tout ce qui est ici se passe APRÈS la réussite de la requête.
        return response;
    } catch (error) {
        // Tout ce qui est ici se passe en cas d'erreur.
        console.error("Erreur critique :", error);
    }
}
/*

RAPPEL :
---------

$("div") tous les éléments <div> de la page
$("#test") l'élément dont l'id est "test"
$(".test") tous les éléments ayant la classe CSS "test"
$("*") tous les éléments de la page
$(this) l'élément HTML courant (dans un handler)
$("p.test") les éléments <p> ayant la classe "test"
$("p:first") le premier élément <p> de la page
$("ul li:last") le dernier élement <li> de tous les <ul>
$("[href]") tous les éléments ayant un attribut "href"
$("a[href='abc.php']") tous les éléments <a> avec href='abc.php'
$("input:button") tous les <input> de type "button"

Trois méthodes très utiles :
– html() : permet d’accéder au contenu HTML d’un
élément (équivalent de innerHTML)
– text() : permet d’accéder au contenu textuel d’un
élément (équivalent de innerText)
– val() : permet d’accéder à l’attribut "value" d’un
champ d’input
Utilisées avec un argument pour lire la valeur.
Utilisées avec deux arguments pour changer la valeur.
Exemples :
• alert($("#test").attr("name"));
• $("#content").attr("custom", "abcdefg");

.children() : Sélectionne uniquement les enfants directs d'un élément.
$('#parent').children('div'); (sélectionne les div qui sont enfants directs de #parent).

.parent() : Sélectionne le parent direct de l'élément.
$('span').parent(); // Sélectionne le div parent direct d'un span.

• prepend() : ajoute des élements HTML au début du parent.
• append()  : ajoute des élements HTML à la fin du parent.
• parent()
• children()
• siblings()
• next()
• previous()

BONUS :
// ?? false si renvoie null ou undefined => false par défaut.
const v = JSON.parse($(this).attr("expanded") ?? false);
$(this).html($(this).html().replace(v ? "-" : "+", v ? "+" : "-"));
 */

/*
async function supprimerMessage(id) {
    try {
        // La requête attend ici la réponse avant de passer à la suite
        const response = await $.ajax({
            url: 'message/delete',
            type: 'POST',
            data: { id_message: id }
        });

        // Tout ce qui est ici se passe APRÈS la réussite de la requête
        console.log("Supprimé !", response);
        displayTable(); // Rafraîchir le tableau

    } catch (error) {
        // Tout ce qui est ici se passe en cas d'erreur
        console.error("Erreur critique :", error);
    }
}
 */