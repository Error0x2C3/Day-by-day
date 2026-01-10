package labo15.ex03;

import java.util.HashSet;
import java.util.Set;
import java.util.TreeSet;

public class Program {

    public static void main(String[] args) {
        Set<Person> set = new TreeSet<>(); // Ordre + pas de doublon.
        set.add(new Person("Donald", "Knuth", 10, 1, 1938));
        set.add(new Person("Donald", "Knuth", 10, 1, 1940));
        set.add(new Person("Dennis", "Ritchie", 9, 9, 1941));
        set.add(new Person("Linus", "Torvalds", 28, 12, 1969));
        set.add(new Person("Linus", "Torvalds", 28, 12, 1969));
        set.add(new Person("James", "Gosling", 19, 5, 1955));
        set.add(new Person("Roulio", "Gosling", 19, 5, 1955));
        set.add(new Person("Edsger", "Dijkstra", 11, 5, 1930));
        set.add(new Person("Tony", "Hoare", 11, 1, 1934));
        System.out.println(set);
        Set<Person> set2 = new HashSet<>();
        set2.add(new Person("Donald", "Knuth", 10, 1, 1938));
        set2.add(new Person("Donald", "Knuth", 10, 1, 1940));
        set2.add(new Person("Dennis", "Ritchie", 9, 9, 1941));
        set2.add(new Person("Linus", "Torvalds", 28, 12, 1969));
        set2.add(new Person("Linus", "Torvalds", 28, 12, 1969));
        set2.add(new Person("James", "Gosling", 19, 5, 1955));
        set2.add(new Person("Roulio", "Gosling", 19, 5, 1955));
        set2.add(new Person("Edsger", "Dijkstra", 11, 5, 1930));
        set2.add(new Person("Tony", "Hoare", 11, 1, 1934));
        // Pas triée + pas de doublon.
        /*
        Sans la méthode hashCode définit dans la classe Person,
        il accepte les doublons car par défaut c'est la méthode Object.hashCode()
        Et Object.hashCode() :
            ❌ ne dépend PAS des champs.
            ✅ dépend de l’identité mémoire de l’objet.
        Donc :
            new Person("Linus", "Torvalds", 28, 12, 1969).
            créé deux fois ⇒
            ➡️ deux hashCodes différents, même si les données sont identiques.
        */
        System.out.println(set2);
    }
}
