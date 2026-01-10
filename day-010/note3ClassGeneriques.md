# Les classes génériques en Java

## 1. Qu’est-ce qu’une classe générique ?

Une classe générique est une classe **paramétrée par un type**.
Cela signifie que tu ne fixes pas à l’avance le type des données que la classe va manipuler, mais tu laisses l’utilisateur de la classe choisir ce type lors de la création d’un objet.

Exemple :

```java
public class Pair<E> {
    private E first, second;

    public Pair(E first, E second) {
        this.first = first;
        this.second = second;
    }

    public E getFirst() {
        return first;
    }

    public E getSecond() {
        return second;
    }
}
```

Ici, `<E>` indique que la classe `Pair` est **générique** et que `E` représente un **paramètre de type**.
Lorsqu’on utilise la classe, on précise le type concret :

```java
Pair<Integer> p1 = new Pair<>(1, 2);     // Ici E = Integer
Pair<String> p2 = new Pair<>("a", "b"); // Ici E = String
```

---

## 2. Pourquoi mettre le type entre chevrons `< >` ?

* Les chevrons indiquent que l’on travaille avec un **paramètre de type**.
* C’est la notation standard en Java (et aussi dans d’autres langages comme C++ avec les *templates*).
* Cela permet d’écrire :

  > « Cette classe dépend d’un type que tu préciseras plus tard. »

---

## 3. Différence avec une classe normale

### Classe normale (non générique)

* Le type est fixé à l’avance.
* Exemple :

  ```java
  public class PairInt {
      private int first, second;
      // ...
  }

  public class PairString {
      private String first, second;
      // ...
  }
  ```
* Si tu veux gérer des `int`, des `String`, des `double`, etc., tu dois créer plusieurs classes → duplication de code.

---

### Classe générique

* Une seule classe suffit :

  ```java
  Pair<Integer> intPair = new Pair<>(10, 20);
  Pair<String> stringPair = new Pair<>("a", "b");
  Pair<Double> doublePair = new Pair<>(1.5, 2.7);
  ```
* Le code est **réutilisable**, **flexible** et garde la **sécurité de typage**.

---

## ✅ En résumé

* Les chevrons `<E>` définissent un **paramètre de type**.
* Une classe générique permet de manipuler des données de n’importe quel type **sans réécrire la classe**.
* Une classe normale est figée sur un type précis.
* Les génériques évitent la duplication de code et rendent le programme plus clair et maintenable.



### Question

```java
import java.util.ArrayList;

public class Pair<E> {
    private E first, second;

    public Pair(E first, E second) {
        this.first = first;
        this.second = second;
    }

    public E getFirst() {
        return first;
    }

    public E getSecond() {
        return second;
    }

    public static <E> ArrayList<E> listAff(ArrayList<E> tab1){
        return tab1;
    }
}

import java.sql.Array;
import java.util.ArrayList;

public class Main {
    public static void main(String[] args) {
        System.out.printf("Hello and welcome!");
        Pair<Integer> p1 = new Pair<>(1,2);
        ArrayList<Integer> list1 = new ArrayList<Integer>();
        list1.add(1);
        list1.add(2);
        Pair.listAff(list1);
        System.out.println(Pair.listAff(list1));
    }
}
```

```java
public static <E> ArrayList<E> listAff(ArrayList<E> tab1){
    return tab1;
}
```

Ah donc je dois mettre `<E>` avant `ArrayList<E> listAff(ArrayList<E> tab1)` ?

Car par exemple :

```java
ArrayList<Integer> list1 = new ArrayList<>();
list1.add(1);
Pair.listAff(list1);
```

Là, `ArrayList<E> tab1` → `E` est un `Integer`.

Mais dans la fonction, à tout moment je pourrais modifier ou créer une nouvelle `ArrayList`, par exemple :

```java
ArrayList<String> tab2 = new ArrayList<>("A", "B");
```

et retourner `tab2`, qui au lieu d’être un `ArrayList<Integer>` serait un `ArrayList<String>`.

Et ce `E` après le `public static` doit être dans un chevron pour faire comprendre que c’est un paramètre de type générique ?
Car à tout moment j’aurais pu créer manuellement une classe `E` locale et Java aurait compris que `E` sans chevron fait référence à cette classe locale ?

---

### Réponse

Excellente remarque 👏
Tu as parfaitement compris **presque tout le mécanisme**, il ne manque qu’un petit ajustement de logique.
Voici une explication claire point par point 👇

---

### 🧩 1. Oui, tu dois mettre `<E>` avant le retour

```java
public static <E> ArrayList<E> listAff(ArrayList<E> tab1) {
    return tab1;
}
```

Le `<E>` juste avant `ArrayList<E>` sert à **déclarer** le paramètre de type générique.
C’est exactement comme si tu disais à Java :

> « Attention, dans cette méthode je vais utiliser une *lettre de type* appelée `E`. »

Sans ce `<E>` placé avant, Java ne sait pas ce qu’est `E`.
Il croira que c’est une classe normale (qu’il faut avoir déclarée ailleurs) crée localement par toi,
et te donnera une erreur du type :

```
E cannot be resolved to a type
```

---

### 🧠 2. Quand tu appelles la méthode

```java
ArrayList<Integer> list1 = new ArrayList<>();
list1.add(1);

Pair.listAff(list1);
```

➡️ Ici, le compilateur **déduit automatiquement** que `E = Integer`.
Autrement dit, pour cet appel précis, la méthode devient :

```java
public static ArrayList<Integer> listAff(ArrayList<Integer> tab1)
```

---

### ⚠️ 3. Mais à l’intérieur de la méthode…

Tu ne peux **pas** faire ceci :

```java
ArrayList<String> tab2 = new ArrayList<>(List.of("A", "B"));
return tab2;
```

Pourquoi ?
Parce que `E` a déjà été fixé à `Integer` lors de l’appel.
Tu ne peux donc plus soudain retourner une `ArrayList<String>` :
cela provoquerait une **erreur de type** à la compilation.

---

### 🧱 4. Ton raisonnement sur le chevron est juste

Tu disais :

> Et ce E après le public static doit être dans un chevron pour le faire comprendre que c'est un paramètre de type générique.

✅ Exactement !
C’est comme une déclaration de variable de type, mais pour le compilateur.

Si tu écrivais sans les chevrons :

```java
public static E ArrayList<E> listAff(ArrayList<E> tab1)
```

Java croirait que `E` est **une classe réelle** (définie quelque part dans ton code).
Donc oui, tu pourrais très bien avoir une classe :

```java
class E {}
```

Et dans ce cas, `ArrayList<E>` voudrait dire « liste d’objets de la classe `E` »,
et **non plus** un paramètre de type générique.

---


### 5. Différence entre méthode sans et avec générique 

#### 5.1. Cas simple sans générique

Quand tu écris :

```java
public static int getInstanceVariable()
```

Tu indiques simplement : *« cette méthode retournera un `int` »*.
Aucun problème, car `int` est un type **connu** de Java.

---

#### 5.2. Cas avec générique

```java
public static <E> ArrayList<E> shuffle1(ArrayList<E> tab1){...}
```

Le E de l'argument Arraylist<E> tab1 est aussi un  *paramètre de type générique*,
mais c'est toi qui va le lui donner durant l'assignation dans le main.

Dans ton cas, tu veux que ta méthode retourne une `ArrayList` contenant des éléments d’un type **variable** (`E`).
le premier ArrayList<E> dans ArrayList<E> shuffle1(ArrayList<E> tab1){...}.

Exemple : une `ArrayList<Integer>`, une `ArrayList<String>`, etc.


👉 Le souci : Java **ne connaît pas** ce que représente le premier `E`.
C’est toi qui dois lui **déclarer** que `E` est un *paramètre de type générique*.

C’est exactement ce que fait le premier `<E>` ( mis entre chevron ) juste après `static` :

```java
public static <E> ArrayList<E> shuffle1(ArrayList<E> tab1)
```

Il faut lire cette ligne ainsi :

* `<E>` → *« Je déclare un type générique appelé E. »*
* `ArrayList<E>` → *« Je vais manipuler des listes contenant des éléments de type E. »*

---

#### 5.3. Pourquoi pas juste :

```java
public static ArrayList<E> shuffle1(ArrayList<E> tab1)
```

Parce que dans cette écriture, **`E` n’existe pas encore** !
Le compilateur ne sait pas ce qu’est `E` et renverra une erreur :

```
E cannot be resolved to a type
```

👉 Tu dois **déclarer `E`** avant de l’utiliser, exactement comme tu déclares une variable avant de t’en servir.

---

#### 5.4. Analogie simple

Si tu inventes un type :

```java
public static X addition(X a, X b)
```

Java ne connaît pas `X`. Il faut d’abord le déclarer :

```java
public static <X> X addition(X a, X b)
```

C’est la même logique pour ton `E`.

---

### 🧩 En résumé

---

💡 En résumé :

> La classe `Pair<E>` utilise un type générique pour stocker deux valeurs du même type.
> Le `<E>` après `static` **déclare le type générique**.
> Sans lui, Java penserait que `E` est une classe normale.
> Une fois `E` fixé (ici `Integer`), tu ne peux plus le changer en un autre type dans la même exécution de méthode.


| Élément                    | Rôle                                                     |
| -------------------------- | -------------------------------------------------------- |
| `<E>` dans `class Pair<E>` | déclare que la classe peut manipuler n’importe quel type |
| `<E>` avant la signature   | déclare un **paramètre de type générique**               |
| `ArrayList<E>`             | utilise ce type dans la signature                        |
| Sans `<E>`                 | Java cherche une **classe nommée `E`**                   |
| `ArrayList<E>`             | liste d’éléments du type générique `E`                   |
| Appel `Pair.listAff(list1)`                          | le compilateur **remplace `E` par `Integer`** |
| Tu ne peux pas changer `E` en `String` à l’intérieur | car `E` est fixé à `Integer` pour cet appel   |

---


### ❓ Question

```java
public <E> E poll() {
    E resultat = (E) this.getLi().getFirst();
}
```

Quelle est la différence entre `public <E> E poll()` et `public E poll()` ? Explique très simplement avec des exemples.

---

### 💡 Réponse

#### 🧩 1. `public E poll()`

Ici, **E** vient du **haut de ta classe**.

Exemple :

```java
public class MyQueue<E> {
    public E poll() {
        ...
    }
}
```

➡️ Cela veut dire :

> "Je vais renvoyer **le même type** que celui de ma classe."

Exemple d’utilisation :

```java
MyQueue<Integer> q = new MyQueue<>();
Integer x = q.poll(); // x est un Integer
```

Le type est lié à la déclaration de ta classe.

---

#### 🧩 2. `public <E> E poll()`

Ici, le `<E>` **crée un nouveau type local à la méthode**. Il **n’a rien à voir** avec celui de la classe.
le <E> est un type générique local à la méthode,
et il est déduit au moment de l’appel de la méthode.

Il est totalement indépendant du <T> de la classe.
Exemple :

```java
public class MyQueue<T> {
    public <E> E poll() {
        ...
    }
}
```

➡️ Ici, tu inventes un **autre E**, différent de `T`.
✔️ <T> de la classe → décidé à la création de l’objet
✔️ <E> d’une méthode → décidé à chaque appel
❌ Aucun lien entre les deux sauf si tu le veux explicitement
Exemple d’utilisation :

```java
MyQueue<Integer> q = new MyQueue<>();
String s = q.<String>poll(); // autorisé, mais absurde ! + ici, E = String.
```

Ta file contient des `Integer`, donc ce `E` n’a pas de sens ici.

---

### 🎯 En résumé simple

| Syntaxe               | Où est défini le type ? | Exemple d’usage                                    | Sens                      |
| --------------------- | ----------------------- | -------------------------------------------------- | ------------------------- |
| `public E poll()`     | dans la **classe**      | `MyQueue<Integer>` → `poll()` renvoie un `Integer` | ✅ C’est ce qu’il faut ici |
| `public <E> E poll()` | dans la **méthode**     | `poll()` pourrait renvoyer n’importe quel type     | ❌ Inutile ici             |

---

👉 Donc dans ton cas, il faut écrire :

```java
public E poll() {
    E resultat = li.remove(0);
    return resultat;
}
```


