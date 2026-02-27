package _02_unidirectional_binding;

import javafx.beans.property.IntegerProperty;
import javafx.beans.property.SimpleIntegerProperty;

public class Unidirectional {
    public static void main(String[] args) {
        // Création de deux propriétés
        IntegerProperty intProperty1 = new SimpleIntegerProperty(5),
                intProperty2 = new SimpleIntegerProperty();

        System.out.println(" intProperty1 = " + intProperty1.getValue()); // 5
        System.out.println(" intProperty2 = " + intProperty2.getValue()); // 0 par défaut

        intProperty2.bind(intProperty1); // “bind” (lie) intProperty2 à intProperty1

        System.out.println(" intProperty1 = " + intProperty1.getValue()); // 5
        System.out.println(" intProperty2 = " + intProperty2.getValue()); // 5

        intProperty1.set(7); // Donne une nouvelle valeur à intProperty1

        System.out.println(" intProperty1 = " + intProperty1.getValue()); // 7
        System.out.println(" intProperty2 = " + intProperty2.getValue()); // 7

//        intProperty2.set(3);         // RuntimeException: A bound value cannot be set

    }
}
