// Equivalent de document.onreadystatechange{if (document.readyState === 'complete'){}}
$(async function () {
    let result_participating_browse_item =false;
    let list_balises = [$("#tag_result_filter-participating"),$("#tag_result_filter-avaible-items"),$("#tag_result_filter-active-items"),$("#tag_result_filter-closed-unsold-items"),$("#tag_result_filter-sold-items")];
    // Génére la barre de recherche.
    let search_bar_html = `<div class="search-wrapper mb-4" id="search">
                                    <i class="bi bi-search"></i>
                                    <input id="fulltext-search" type="text" placeholder="Search items...">
                                  </div>`;
    // Je prends le premier élément ayant l'id bar_search.
    $("#bar_search").prepend(search_bar_html);
    // Pour avoir le focus dessus dès le chargement de la page.
    $("#fulltext-search").focus();
    // Si on est dans le cas ou on revient d'une autre page ex open item.
    if (typeof(word_search) !== 'undefined' && word_search) {
        // console.log("Mot trouvé : " + word_search);
        // On reprends le mots déjà tapé et on lance la recherche dessus.
        $('#fulltext-search').val(word_search);
        await mamange_search(word_search, list_balises, search_bar_html);
    }
    // Si je tape quelque chose dans la barre de recherche.
    $('#fulltext-search').on('input', async function () {
        await mamange_search($(this).val().toLowerCase(), list_balises, search_bar_html); // On récupére la version minuscule du mot.
    });
});

// Gestion du processus de recherche du mot.
async function mamange_search(search_content,list_balises, search_bar_html){
    let bool_participating_browse_item_no_found =false;
    let bool_avaible_items_browse_item_no_found =false;
    // let element in list =>  element correspond à l'index [0,1...].
    // let element of list => element correspond à chaque élément du tableau ["a","b"...].
    for(let i=0 ; i <= list_balises.length-1 ; i++){
        let element = list_balises[i];
        // On vide le contenu html de la balise.
        element.empty();
        if(i===0){ // 0 correspond à Items I'm Participating In de Browse Item.
            let resultat_search_word = await search_filter_list_items_user(search_content,"participating");
            // On vérifie qu'on a bien un résultat pour la recherche de notre mot.
            if( resultat_search_word && Object.keys(resultat_search_word).length > 0){
                // On obtient les items en html qui ont le mot tapé dans la barre de recherche.
                get_cards_search_resultats(resultat_search_word, list_balises[0]);
            }else{ bool_participating_browse_item_no_found= true; console.log(resultat_search_word+"a");}
        }else if( i===1){ // 1 correspond à Other Available Items de Browse Item.
            let resultat_search_word = await search_filter_list_items_user(search_content,"available-items");
            if(resultat_search_word &&Object.keys(resultat_search_word).length > 0){
                get_cards_search_resultats(resultat_search_word, list_balises[1]);
            }else{bool_avaible_items_browse_item_no_found =true; console.log(resultat_search_word+"b");}
        }else if(i===2){ // 2 correspond à Active Items de My items.
            let resultat_search_word = await search_filter_list_items_user(search_content,"active-items");
            if(resultat_search_word &&Object.keys(resultat_search_word).length > 0){
                get_cards_search_resultats(resultat_search_word, list_balises[2]);
            }
        }else if(i===3){ // 3 correspond à Closed Unsold Items de My items.
            let resultat_search_word = await search_filter_list_items_user(search_content,"closed-unsold-items");
            if(resultat_search_word && Object.keys(resultat_search_word).length > 0){
                get_cards_search_resultats(resultat_search_word, list_balises[3]);
            }
        }else if(i ===4){
            let resultat_search_word = await search_filter_list_items_user(search_content,"sold-items");
            if(resultat_search_word && Object.keys(resultat_search_word).length > 0){
                get_cards_search_resultats(resultat_search_word, list_balises[4]);
            }
        }
    }
    if(bool_participating_browse_item_no_found && bool_avaible_items_browse_item_no_found){
        $("#bar_search").children().slice(1).hide();
        console.log($("#bar_search").children());
        if($("#bar_search").find("#no_item_found").length ===0) {$("#bar_search").append(create_page_no_found());}
    }else {
        $("#no_item_found").remove();
        $("#bar_search").children().slice(1).show();
    }
}
// Recherche sur les items auquels l'utilisateur connecté participe.
async function search_filter_list_items_user(word, mode) {
    try {
        // La requête attend ici la réponse avant de passer à la suite.
        let response = await $.ajax({
            url: 'item/get_card_search_service',
            type: 'POST', // type que recevra côté php.
            data: { word_search: word,mode:mode}, // ce qu'elle va recevoir.
            dataType: 'json' // le format au quel moi je recois la côté js.
        });
        // console.log(response);
        // Tout ce qui est ici se passe APRÈS la réussite de la requête.
        return response;
    } catch (error) {
        // Tout ce qui est ici se passe en cas d'erreur.
        console.error("Erreur critique :", error);
    }
}

// Génére les carts html à partir des mots tapés.
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
                        <a href="item/open_item_view/${item.id}/${item.btn_back}" class="stretched-link"></a>
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
                                        <span>${item.data.pictures.length}${item.data.pictures.length > 1 ? " images" : " image"}</span>
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



function create_page_no_found(){
    return `
        <div class="empty-state-container d-flex justify-content-center align-items-center" id="no_item_found">
            <div class="text-center empty-state-content">
                <i class="bi bi-search empty-state-icon"></i>
                <h2 class="empty-state-text mt-3">No item found</h2>
            </div>
        </div>
        `;

// Exemple d'utilisation : l'injecter dans un élément de votre page
// document.getElementById('votre-conteneur').innerHTML = emptyStateHtml;
}