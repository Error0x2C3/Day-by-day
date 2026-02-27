package _01_property;

import javafx.beans.property.IntegerProperty;
import javafx.beans.property.ReadOnlyIntegerProperty;
import javafx.beans.property.SimpleIntegerProperty;

class Model {
    /*
    Les property c'est bien une variable qui peut être
    observable et dont on a plus besoin de faire
    par exemple :

    public int set_value(int nbr){
        if(nbr > 0 ){
            this.value = nbr;
            this.setChanged();
            this.notifyObservers();
        }
    Donc on a plus besoin d'ajouter les deux dernières lignes :
    this.setChanged();
    this.notifyObservers();

    La notification automatique (Adieu notifyObservers)
    Les classes comme SimpleIntegerProperty encapsulent
    la valeur et le mécanisme d'observation.
    Quand j'appelle this.anyValue.set(anyValue);,
    la propriété se charge elle-même, en interne,
    de prévenir tous ceux qui l'observent que la valeur a changé.
    Le code "passe-partout" (boilerplate) disparaît,
    ce qui rend tes classes beaucoup plus propres.
     */
    private final IntegerProperty anyValue = new SimpleIntegerProperty(), // Réprésente n'importe quel entier Observable.
        positiveValue = new SimpleIntegerProperty(), // Représente un entier positif Observable.
        wrongPositiveValue = new SimpleIntegerProperty();  // Représente un entier positif Observable.

    public int getAnyValue() {
        return anyValue.get();
    }

    /*
    On fournit un accès à anyValue => le code client peut modifier la valeur comme il l'entend,
    Ce qui est mauvais.
     */
    public IntegerProperty anyValueProperty() {
        return anyValue;
    }

    public void setAnyValue(int anyValue) {
        this.anyValue.set(anyValue);
    }

    public int getPositiveValue() {
        return positiveValue.get();
    }

    /*
    On fournit une ReadOnlyIntegerProperty qui "emballe" positiveValue afin d'empêcher une modification de la valeur par le code client.
     */
    public ReadOnlyIntegerProperty positiveValueProperty() {
        return positiveValue;
    }

    // PRE : positiveValue >= 0
    public void setPositiveValue(int positiveValue) {
        if (positiveValue < 0) {
            throw new RuntimeException("Try to put negative value for positiveValue : " + positiveValue);
        }
        this.positiveValue.set(positiveValue);
    }

    public int getWrongPositiveValue() {
        return wrongPositiveValue.get();
    }

    /*
    Erreur de conception : en permettant un accès direct à la propriété, le code client peut modifier la valeur sans passer par le setter =>
    peut fournir une valeur négative (contrairement aux specs)
    C'est pour cette raison qu'il faut fournir une ReadOnlyIntegerProperty et pas une IntegerProperty
     */
    public IntegerProperty wrongPositiveValueProperty() {
        return wrongPositiveValue;
    }

    // PRE : positiveVal >= 0
    public void setWrongPositiveValue(int positiveVal) {
        if (positiveVal < 0) {
            throw new RuntimeException("Try to put negative value for wrongPositiveValue : " + positiveVal);
        }
        this.wrongPositiveValue.set(positiveVal);
    }

    public String test(){
        return "test";
    }
}
