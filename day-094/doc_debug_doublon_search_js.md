# Explication du bug des doublons dans la recherche

## Origine du bug

Le bug des doublons vient d’un **conflit de vitesse** dans le fichier `js/search.js`.

### Ce qui se passe

À chaque fois que l’utilisateur tape une lettre, par exemple :

- `c`
- puis `l`
- puis `a`

une nouvelle recherche est lancée.

### Le conflit

Ces recherches sont **asynchrones** : elles prennent du temps pour contacter le serveur et récupérer les résultats.

Si l’utilisateur tape vite, une recherche plus ancienne peut se terminer **après** une recherche plus récente.

Par exemple, la recherche pour `c` peut finir après la recherche pour `cl`.

### Le résultat

Les deux recherches essaient alors d’afficher leurs résultats dans la même zone HTML.

Au lieu de se remplacer correctement, les résultats peuvent s’ajouter les uns aux autres, ce qui crée des **doublons**.

---

# Scénario : l’utilisateur tape `CL` très vite

## Étape 1 : l’utilisateur tape `C`

1. Une première fonction `mamange_search`, appelée ici **Recherche n°1**, se lance.
2. `currentSearchId` passe de `0` à `1`.
3. À l’intérieur de la Recherche n°1, la variable `mySearchId` est fixée à `1`.
4. La Recherche n°1 envoie sa requête au serveur.  
   Cette requête prend environ `200 ms`.

---

## Étape 2 : l’utilisateur tape `L` après seulement `50 ms`

1. Une deuxième fonction `mamange_search`, appelée ici **Recherche n°2**, se lance.
2. `currentSearchId`, le compteur global, passe de `1` à `2`.
3. À l’intérieur de la Recherche n°2, la variable `mySearchId` est fixée à `2`.
4. La Recherche n°2 envoie sa requête au serveur.  
   Cette requête prend aussi environ `200 ms`.

---

## Étape 3 : le serveur répond à la Recherche n°1 pour `C`

1. La Recherche n°1 se réveille et s’apprête à afficher les résultats pour `C`.
2. Elle effectue un test de sécurité.
3. Elle compare son ticket :

   ```js
   mySearchId = 1
   ```

   avec le compteur global :

   ```js
   currentSearchId = 2
   ```

4. Le résultat est :

   ```js
   1 !== 2
   ```

5. La fonction comprend donc qu’elle est **dépassée**, car une recherche plus récente a été lancée.
6. Elle s’arrête avec un `return`, sans rien afficher.

Résultat : on évite ainsi les premiers doublons.

---

## Étape 4 : le serveur répond à la Recherche n°2 pour `CL`

1. La Recherche n°2 se réveille.
2. Elle effectue le même test de sécurité.
3. Elle compare son ticket :

   ```js
   mySearchId = 2
   ```

   avec le compteur global :

   ```js
   currentSearchId = 2
   ```

4. Le résultat est :

   ```js
   2 === 2
   ```

5. C’est donc bien la recherche la plus récente.
6. Elle vide la zone HTML avec :

   ```js
   .empty()
   ```

7. Elle affiche ensuite les résultats pour `CL`.

---

# Conclusion

Le système avec `currentSearchId` et `mySearchId` sert à vérifier qu’une recherche est toujours la plus récente avant d’afficher ses résultats.

Si une recherche est dépassée, elle s’arrête immédiatement.

Cela permet d’éviter que plusieurs recherches asynchrones affichent leurs résultats en même temps et créent des doublons.
