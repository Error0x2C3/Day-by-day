# Le DOM n’a pas de mémoire : jQuery ne voit que **la page actuelle**.

Quand l'utilisateur est sur la page **Browse Items**, le navigateur télécharge le HTML de cette page et construit le DOM.
À cet instant précis, le code HTML de la page **My Items** n'existe littéralement pas dans la mémoire de l'onglet.

La fonction manage_search récupère la balise Jquery $("#bar_search") du DOM de CETTE PAGE **Browse Items**,
et donc si après je fais element.length === 0, Jsquery va vérifier si la balise (contenue dans le tableau) existe sur le DOOM de CETTE PAGE **Browse Items** ou pas.
---

# Le comportement de jQuery

Au tout début de du script, on déclares la liste :

```javascript
let list_balises = [
  $("#tag_result_filter-participating"),
  $("#tag_result_filter-active-items"),
  ...
];
```

Quand jQuery lit ça, il va chercher ces identifiants dans le DOM de la page actuelle : **Browse Items**.

## Exemple

- Il cherche `#tag_result_filter-participating`  
  ➔ Il le trouve !  
  ➔ Il le met dans la liste.  
  ➔ Son `length` sera égal à `1`.

- Il cherche `#tag_result_filter-active-items`  
  ➔ Il cherche partout dans la page **Browse Items**.  
  ➔ Il ne trouve rien, puisque cet élément est sur l'autre page.  
  ➔ Au lieu de faire planter le code, jQuery crée gentiment un objet vide.  
  ➔ Cet objet a un `length` égal à `0`.

---

# La conclusion magique

Quand qu'on lance la boucle `for` avec le fameux :

```javascript
if (element.length === 0) {
  continue;
}
```

Le code vérifie simplement :

> Est-ce que cet élément a été trouvé sur la page que l'utilisateur regarde en ce moment ?

## Résultat

- Si oui, la balise est à    
  ➔ Il lance la requête AJAX.

- Si non, la balise n'est pas sur cette page  
  ➔ Il passe directement au suivant, sans perdre 1 milliseconde.
