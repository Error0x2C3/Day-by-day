package _04_simple_binding_with_javafx;

import javafx.beans.binding.BooleanBinding;
import javafx.beans.binding.IntegerBinding;
import javafx.beans.property.SimpleStringProperty;
import javafx.beans.property.StringProperty;

public class Person {
    private final StringProperty firstName = new SimpleStringProperty(),
            lastName = new SimpleStringProperty();

    // lastName.length() retourne un IntegerBinding lié à la taille de la StringProperty.
    /*
    IntegerBinding est une variable "intelligente"
    qui contient un nombre entier dont la valeur se
    met à jour automatiquement en fonction d'une autre property.
    Contrairement à un simple int qui reste figé tant que je ne le modifie pas manuellement,
    IntegerBinding est observateur de la property lastName.length().
     */
    private final IntegerBinding nameLength = lastName.length();


    // sameNames est un BooleanBinding indiquant si les 2 StringProperties ont la même valeur (sans tenir compte de la casse).
    private final BooleanBinding sameNames = firstName.isEqualToIgnoreCase(lastName);

    public Person(String firstName, String lastName) {
        this.firstName.setValue(firstName);
        this.lastName.setValue(lastName);
    }

    // Retourne une StringProperty, ce qui permet un binding bidirectionnel.
    public StringProperty firstNameProperty() {
        return firstName;
    }

    // Retourne une StringProperty, ce qui permet un binding bidirectionnel.
    public StringProperty lastNameProperty() {
        return lastName;
    }

    // Retourne un IntegerBinding, ce qui ne permet qu'un binding en lecture seule.
    public IntegerBinding nameLengthProperty() {
        return nameLength;
    }

    // Retourne un BooleanBinding, ce qui ne permet qu'un binding en lecture seule.
    public BooleanBinding sameNamesProperty() {
        return sameNames;
    }

    public boolean hasSameNames() {
        return sameNames.get();
    }

    public void forceSameNames() {
        firstName.set(lastName.get().toLowerCase());
        lastName.set(lastName.get().toUpperCase());
    }
}
