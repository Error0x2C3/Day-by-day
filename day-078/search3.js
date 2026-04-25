// Equivalent de document.onreadystatechange{if (document.readyState === 'complete'){}}
$(async function () {
    let list_balises = [$("#tag_result_filter-participating-1"),$("#tag_result_filter-avaible-items-2")];
    let search_bar_html = `<div class="search-wrapper mb-4" id="search">
                                    <i class="bi bi-search"></i>
                                    <input id="fulltext-search" type="text" placeholder="Search items...">
                                  </div>`;
    // Je prends le premier élément ayant l'id bar_search.
    $("#bar_search").prepend(search_bar_html);
    // Pour avoir le focus dessus dès le chargement de la page.
    $("#fulltext-search").focus();
    // Des que la page apparaît on a le focus sur la barre de recherche.
    $('#fulltext-search').on('input', async function () {
        let search_content = $(this).val().toLowerCase(); // On récupére la version minuscule.
        let resultat_search_word = await search_filter_list_items_user_participing(search_content);
        // console.log(resultat_search_word);
        // On vérifie qu'on a bien un résultat pour la recherche de notre mot.
        if( resultat_search_word && resultat_search_word !==""){
            // let element in list =>  element correspond à l'index [0,1...].
            // let element of list => element correspond à chaque élément du tableau ["a","b"...].
            for(let i=0 ; i <= list_balises.length-1 ; i++){
                // console.log(list_balises[i].html());
                let element = list_balises[i];
                element.empty();
                console.log(element);
                // Vide le contenu html de l'élément.

                if(i === 1){
                    // console.log(resultat_search_word);
                    // On obtient les items en html qui ont le mot tapé dans la barre de recherche.
                    get_cards_search_resultats(resultat_search_word, list_balises[0]);
                }else if( i === 2){

                }
            }
        }else {

        }
    });
});

// Recherche sur les items auquels l'utilisateur connecté participe.
async function search_filter_list_items_user_participing(word) {
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

// Parcoure la liste json qui contient les items ont le mot et l'ajoute dans une balise html.
async function get_cards_search_resultats(list_items_json, field){
    for(let item of list_items_json){
        field.append(create_card_for_item(item));
    }
    return field;
}

// Génére une carte pour un item en html.
function create_card_for_item(item){
    return `
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card">
                        <a href="item/open_item_view/${item.id}" class="stretched-link"></a>
                        <div class="position-relative">
                            ${item.data.pictures.length > 0 ? `<img src="${item.data.pictures[0]}" class="card-img-top" alt="Card image">` : `<i class="bi bi-image card-img-top d-flex align-items-center justify-content-center" style="font-size: 8rem; height: 100%; width: 100%; color: grey;"></i>`}
                            <div class="card-img-overlay">
                                ${item.data.highest_bidder_id  && item.data.highest_bidder_id === item.user_current_id && item.data.has_auction ?
                                `<div class="badge-highest-bidder">
                                    <i class="bi bi-trophy"></i><span>Highest Bidder</span>
                                </div>` :
                                `<div class="badge-bidder">
                                    <i class="bi bi-bag"></i> <span>Bidder</span>
                                </div>`}
                                <div class="badges-top-right">
                                    ${item.data.has_auction && !item.data.has_buy_now ?
                                        `<div class="badge-auction">
                                            <i class="bi bi-shop-window"></i>
                                            <span>Auction</span>
                                        </div>`: ""
                                    }
                                    ${ item.data.has_buy_now && !item.data.has_auction ?
                                        `<div class="badge-buy-now">
                                            <i class="bi bi-bag"></i>
                                            <span>Buy Now</span>
                                        </div>`
                                    :""
                                    }
                                    ${ item.data.has_buy_now && item.data.has_auction ?
                                        `<div class="badge-auction">
                                            <i class="bi bi-shop-window"></i>
                                            <span>Auction</span>
                                        </div>
                                        <div class="badge-buy-now">
                                            <i class="bi bi-bag"></i>
                                            <span>Buy Now</span>
                                        </div>`
                                    :""
                                    }                                   
                                </div>
                                ${ item.data.pictures.length > 0 ?
                                    `<div class="badge-images">
                                        <i class="bi bi-images"></i>
                                        <span>${item.data.pictures.length}${item.data.pictures.length > 1 ? "images" : "image"}</span>
                                    </div>`
                                :""
                                }
                            </div>
                        </div>
                        <div class="card-body">
                            
                            <p class="card-title-custom">${item.title}</p>
                            <p class="card-owner-custom"><span class="by">by</span>${item.data.owner_pseudo}</p>
                          
                            <div class="d-flex justify-content-between align-items-end mt-3">
                                <div class="main-price-container">
                                    ${item.data.has_auction && !item.data.has_buy_now ? (
                                        `<p class="card-starting-bid-custom mb-0">
                                            € ${Number(item.data.starting_bid).toLocaleString('de-DE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                                        </p>`
                                    ) : item.data.has_buy_now ? (
                                        `<p class="card-buy-now-price-custom mb-0">
                                            € ${Number(item.data.buy_now_price).toLocaleString('de-DE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                                        </p>`
                                    ) : ""}
                                </div>

                                ${item.data.max_bid > 0 ? `
                                    <div class="text-end">
                                        <p class="card-current-bid-label mb-0">Current bid</p>
                                        <p class="card-current-bid-value mb-0">
                                            € ${Number(item.data.max_bid).toLocaleString('de-DE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                                        </p>
                                    </div>
                                ` : ""}
                            </div>
                           ${!item.data.time_has_passed ? `
                                <p class="card-timer-custom mt-3 mb-0">
                                    <i class="bi bi-clock"></i>
                                    ${item.data.time_elapsed_string}
                                </p>
                            ` : `
                                <p class="card-timer-expired-custom mt-3 mb-0">
                                    <i class="bi bi-clock"></i>
                                    Closed
                                </p>
                            `}
                        </div>
                    </div>
                </div>
           `;
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
EXEMPLES :

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

if( resultat_search_word && resultat_search_word !==""){
    // Vide le contenu de l'élément $("#tag_result_filter").
    $('.search_content').empty();
    // On boucle sur chaque classe "search_content".
    $('.search_content').each(function(index, element) {
        // - element (ou this) : C'est l'élément DOM pur (ex: <div>).
        // - $(element) (ou $(this)) : C'est l'élément encapsulé par jQuery.

        // console.log(resultat_search_word);
        // On gére les cards qui ont le mot tapé dans la barre de recherche
        get_cards_search_resultats(resultat_search_word, $(element));
    });
}
 */