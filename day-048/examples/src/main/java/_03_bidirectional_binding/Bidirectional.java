package _03_bidirectional_binding;

import javafx.beans.property.IntegerProperty;
import javafx.beans.property.SimpleIntegerProperty;

public class Bidirectional {
    public static void main(String[] args) {
        // Création de deux propriétés
        IntegerProperty intProperty1 = new SimpleIntegerProperty(5),
                intProperty2 = new SimpleIntegerProperty();

        System.out.println("Valeurs au Début (sans Binding bidirectionnel) : ");
        System.out.println("---------------------------");
        System.out.println(" intProperty1 = " + intProperty1.getValue()); // 5
        System.out.println(" intProperty2 = " + intProperty2.getValue()); // 0 par défaut
        System.out.println("---------------------------");
        System.out.println();

        // “bind” intProperty2 à intProperty1 dans les deux sens.
        //  intProperty2 prend la valeur de intProperty1 de tel sorte que :
        //    valeur de intProperty2 == valeur de intProperty1 == 5
        intProperty2.bindBidirectional(intProperty1);

        // Dans ce cas ci-dessous, c'est intProperty1 qui va prendre la valeur de intProperty2,
        // de tel sorte que :
        //    valeur de intPorperty1 == valeur de intProperty2 == 0
//        intProperty1.bindBidirectional(intProperty2);

        System.out.println("Après le Binding bidirectionnel : ");
        System.out.println("---------------------------");
        System.out.println(" intProperty1 = " + intProperty1.getValue()); // 5
        System.out.println(" intProperty2 = " + intProperty2.getValue()); // 5
        System.out.println("---------------------------");
        System.out.println();

        intProperty1.set(7); // Donne une nouvelle valeur à intProperty1

        System.out.println("Après le changement de valeur de intProperty1 + Binding bidirectionnel : ");
        System.out.println("---------------------------");
        System.out.println(" intProperty1 = " + intProperty1.getValue()); // 7
        System.out.println(" intProperty2 = " + intProperty2.getValue()); // 7
        System.out.println("---------------------------");
        System.out.println();

        System.out.println("Après le changement de valeur de intProperty2 + Binding bidirectionnel : ");
        System.out.println("---------------------------");
        intProperty2.set(3); // Donne une nouvelle valeur à intProperty2
        System.out.println(" intProperty1 = " + intProperty1.getValue()); // 3
        System.out.println(" intProperty2 = " + intProperty2.getValue()); // 3
        System.out.println("---------------------------");
    }
}
