package labo15.ex02;

import java.util.HashSet;
import java.util.Set;

public class Test {
    public static void main(String[] args){
        Set<Date> s = new HashSet<>();
        System.out.println(s); // []
        Date d1 = new Date(1, 1, 1);
        s.add(d1);
        System.out.println(s+" "+d1.hashCode()); // [Lundi 1 Janvier 1] 30784
        d1.increment();
        System.out.println(s+" "+d1.hashCode()); // [Mardi 2 Janvier 1] 31745
          /*
          Le HashSet ne réorganise pas ses buckets automatiquement.
          L’objet est dans le mauvais bucket après assignation.
          L'objet modifié après insertion reste physiquement dans l’ancien bucket,
          mais son hashCode() change.
          Donc contains() cherche au mauvais endroit
           */
        Date d2 = new Date(1, 1, 1);
        if(!s.contains(d2)) {
            System.out.println("Bizarre!!"); // Est affiché.
        }
        if(!s.contains(d1)) {
            System.out.println("Bizarre, bizarre!!");
        }
    }
    /*
    ❌ FAUX
    « Les indices d’une HashSet sont les hashCode. »

    ✅ VRAI
    Une HashSet utilise :
    hashCode() → pour choisir un bucket.
    equals() → pour comparer les objets dans ce bucket.

    1) Un bucket, c’est quoi ? (version simple)
    👉 Un bucket = une case dans la structure interne d’une HashSet ou HashMap.
    Imagine une rangée de boîtes :
        [ ] [ ] [ ] [ ] [ ] [ ] [ ]
    Chaque boîte = 1 bucket

    Quand tu ajoutes un objet :
    Java calcule son hashCode()
    Il transforme ce hash en numéro de bucket
    Il met l’objet dans cette boîte
    [référence vers l'objet]

    2) Comment Java choisit le bucket ?
    Java ne fait PAS :
    bucket = hashCode
    Il fait quelque chose du genre :
    bucketIndex = hashCode % nombreDeBuckets

    Exemple :

    hashCode = 30784
    nombre de buckets = 16
    30784 % 16 = 0
    ➡️ l’objet va dans le bucket

    3) À quoi ressemble un bucket à l’intérieur ?
    Un bucket peut contenir :
    soit rien
    soit un objet
    soit plusieurs objets (collision)
    Exemple :
    bucket 0 → [Date(1/1/1), Date(5/3/2024)]
    bucket 1 → []
    bucket 2 → [Date(10/10/2020)]

    4) Pourquoi ton code casse tout ?

    Avant increment()
    hashCode = 30784
    → bucket 0

    Après increment()
    hashCode = 31745
    → bucket

    Mais l’objet est toujours physiquement dans bucket 0 😬
    car : Le HashSet ne réorganise pas ses buckets automatiquement.
          L’objet est dans le mauvais bucket.
    Donc :
    contains() va chercher dans bucket 1
    il ne trouve rien.
    alors que l’objet existe bien.

    4) Analogie très parlante  :

    📦 HashSet = immeuble
    📬 Buckets = boîtes aux lettres
    📮 hashCode = numéro de boîte

    Si tu changes l’adresse après avoir reçu ton courrier :
    le facteur cherche à la nouvelle adresse
    mais ton courrier est resté à l’ancienne
     */
}

/*
Set<Date> s = new HashSet<>();
System.out.println(s); => []
Date d1 = new Date(1, 1, 1);
s.add(d1);
Son hashCode =>30784
ex: 30784%16 = 0 => 30784 va être stocker dans la 1 er bucket :
[Lundi 1 Janvier 1] 30784.

d1.increment()
Lorsque je fais ça ,d1 dont la valeur est (1,1,1) et le hashCode 30784 dans le 1 er bucket,
Devient d1 de valeur (2,1,1) et le hashCode devient 31745 (par exemple) mais toujours dans le 1 er bucket.
s :
[Lundi 1 Janvier 1] 31745 Ce qui est un problème
car 31745%16=>2 donc il devrait être dans le 2 ème bucket,
mais Le HashSet ne réorganise pas ses buckets automatiquement après assignation.

Donc s :
[Lundi 1 Janvier 1] 31745

1)
Alors :
Date d2 = new Date(1, 1, 1);
if(!s.contains(d2)){
System.out.println("Bizarre!!"); Est affiché.
}

Car :
avec Date d2 = new Date(1, 1, 1);
le programme cherche le hashCode 30784 dans le premier bucket de s mais
s :
[Lundi 1 Janvier 1] 31745
=> ERREUR.

2)
if(!s.contains(d1)){
System.out.println("Bizarre, bizarre!!"); Est affiché.
}
Car maintenant :
d1 a la valeur (2,1,1) et son hasCode vaut maintenant 31745 et 31745%16 = 1.
Donc le programme cherche dans le 2ème bucket le hasCode 31745.
Mais il ne se trouve pas dans le 2 ème bucket mais toujours dans le 1 er.
1er bucket :
[Lundi 1 Janvier 1] 31745
2 ème bucket :
[]

3)
Date d3 = new Date(2, 1, 1);
if(!s.contains(d3)){
System.out.println("Encore plus bizarre!!"); Est affiché.
}
Car :
d3 a la valeur (2,1,1) et son hashcode vaut 31745 et 31745%16 = 1.
Donc le programme cherche dans le 2ème bucket le hasCode 31745.
Mais il ne ce trouve pas dans le 2 ème bucket mais toujours dans le 1 er.
[Lundi 1 Janvier 1] 31745
2 ème bucket :
[]
 */





