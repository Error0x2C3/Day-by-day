# Héritage et visibilité des classes en Java

## 🧩 Question de départ

> **Une classe ne peut hériter que d’une classe visible :**  
> – les classes **publiques**  
> – les classes **default** qui se trouvent dans le même package  
>
> **Peut-on hériter d’une classe `protected` ?**

---

## 🔹 Clarification importante
En **Java**, il faut distinguer :
- la **visibilité d’une classe**
- la **visibilité des membres** (attributs, méthodes, classes internes)

La confusion vient souvent de là.

---

## 🔹 Visibilité possible d’une classe (top-level)

Une **classe de premier niveau (top-level class)** ne peut être que :

### ✅ `public`
- visible **partout**

### ✅ `default` (aucun mot-clé)
- visible **uniquement dans le même package**

👉 **Une classe top-level ne peut PAS être `protected` ni `private`.**

---

## ✅ Conclusion intermédiaire

La phrase est **correcte** :

> Une classe ne peut hériter que d’une classe visible :  
> – classes `public`  
> – classes `default` du même package

---

## ❌ Pourquoi on ne peut pas hériter d’une classe `protected` ?

Parce que **`protected` n’est pas autorisé pour les classes de premier niveau** en Java.

```java
protected class A { }   // ❌ ERREUR de compilation
```

👉 Donc :
- une classe **ne peut pas être `protected`**
- donc on **ne peut pas hériter d’une classe `protected`** (top-level)

---

## ⚠️ Cas particulier : classes imbriquées (nested classes)

Une **classe interne** peut être `protected`.

```java
public class A {
    protected class B {
    }
}
```

### ➜ Dans ce cas :
`B` est héritable uniquement :
- dans le **même package**
- ou par une **sous-classe de `A`**

⚠️ Cela **ne concerne PAS** les classes normales (top-level).

---

## 📊 Tableau récapitulatif

| Élément | public | protected | default | private |
|------|--------|-----------|-----------|----------|
| Classe top-level | ✅ | ❌ | ✅ | ❌ |
| Classe interne | ✅ | ✅ | ✅ | ✅ |
| Héritage possible | selon visibilité | seulement classe interne | même package | jamais |

---

## ✅ Conclusion finale (à retenir pour l’examen)

- ✔️ Une classe peut hériter d’une classe **`public`**
- ✔️ Une classe peut hériter d’une classe **`default`** du même package
- ❌ Une classe **ne peut pas hériter d’une classe `protected`**
- ✔️ `protected` s’applique aux **membres** et aux **classes internes**, pas aux classes top-level

> **Phrase clé** :  
> *Une classe ne peut hériter que d’une classe visible : publique ou default dans le même package.*

