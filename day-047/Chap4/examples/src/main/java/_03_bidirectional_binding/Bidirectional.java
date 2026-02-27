package _03_bidirectional_binding;

import javafx.beans.property.IntegerProperty;
import javafx.beans.property.SimpleIntegerProperty;

public class Bidirectional {
    public static void main(String[] args) {
        // Création de deux propriétés
        IntegerProperty intProperty1 = new SimpleIntegerProperty(5),
                intProperty2 = new SimpleIntegerProperty();

        System.out.println(" intProperty1 = " + intProperty1.getValue()); // 5
        System.out.println(" intProperty2 = " + intProperty2.getValue()); // 0 par défaut

        intProperty2.bindBidirectional(intProperty1); // “bind” intProperty2 à intProperty1 dans les deux sens
                                                      // valeur de intProperty2 == valeur de intProperty1

        System.out.println(" intProperty1 = " + intProperty1.getValue()); // 5
        System.out.println(" intProperty2 = " + intProperty2.getValue()); // 5

        intProperty1.set(7); // Donne une nouvelle valeur à intProperty1

        System.out.println(" intProperty1 = " + intProperty1.getValue()); // 7
        System.out.println(" intProperty2 = " + intProperty2.getValue()); // 7

        intProperty2.set(3); // Donne une nouvelle valeur à intProperty2
        System.out.println(" intProperty1 = " + intProperty1.getValue()); // 3
        System.out.println(" intProperty2 = " + intProperty2.getValue()); // 3
    }
}
