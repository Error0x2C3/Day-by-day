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
    Donc on a plus besoin d'ajouter ces deux dernières lignes :
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

    La différence entre Observable/Observer ET Property :

    1. Côté Modèle : La fin du code "poubelle" (Boilerplate)
       l'objet Property fusionne la variable (la donnée) et le mécanisme de notification.
    ----------------------------------------------------------
    Avant :
        Le développeur devait penser à prévenir
        tout le monde à la main à chaque modification
        (setChanged() + notifyObservers()).
        Si je l'oubliais, mon interface graphique ne se mettait pas à jour.
    Maintenant :
        La propriété s'occupe de sa propre tuyauterie interne.
        Moi, je me concentre uniquement sur mes règles métier.
        (le fameux if (nbr > 0) par exemple).
    ----------------------------------------------------------

    2. Côté "Main" (ou assemblage) : Ciblage plus précis
    ----------------------------------------------------------
    Avant :
        Je devais ajouter toute une classe comme observateur du modèle tout entier.
        Ex :
            counter_model.addObserver(counter_view);
       Lorsqu'une seule variable du "Modèle" change toute la "Vue" est au courant.

    Maintenant :
        J'ajoute directement changeListener (l'écouteur qu'on a créé dans la "Vue") seul comme observateur de la variable Property (côté "Model").
        Ex :
             model.anyValueProperty().addListener(changeListener);
        Lorsqu'une seule variable Property du "Modèle" change seule le changeListener de la "Vue" est au courant.
     ----------------------------------------------------------

    3. Côté Vue : De l'entonnoir géant à la précision chirurgicale
    ----------------------------------------------------------
    Avant (l'entonnoir) :
        La méthode @Override public void update(...) recevait
        absolument toutes les notifications de l'objet observé.
        Si mon modèle avait 15 variables (le score, la vie, le nom, le temps...), l'update() recevait les 15.
        Je devais alors écrire un if / else if / else if géant pour essayer de deviner qui avait changé et quoi mettre à jour.
        C'était lourd et difficile à lire.
    Maintenant (le sur-mesure) :
        C'est chirurgical. J'ai une variable (Ex: ChangeListener version lambda) = une proprerty.
        Et je peux coller un ChangeListener spécifique (via une petite lambda)
        directement sur la propriété qui m'intéresse.
        Ex :
            Un écouteur juste pour la vie : vieProperty.addListener(...)
            Un autre juste pour le score : scoreProperty.addListener(...)
        Plus besoin de trier les événements dans une sorte de méthode update !
    ----------------------------------------------------------
     */
    private final IntegerProperty anyValue = new SimpleIntegerProperty(), // Réprésente n'importe quel entier Observable.
        positiveValue = new SimpleIntegerProperty(), // Représente un entier positif Observable.
        // Wrong car il sera accessible hors de la classe via une méthode non ReadOnlyXXXProperty (C'est pas bien.)
        wrongPositiveValue = new SimpleIntegerProperty();  // Représente un entier positif Observable.

    public int getAnyValue() {
        return this.anyValue.get();
    }

    /*
    On fournit un accès à anyValue => le code client peut modifier la valeur comme il l'entend,
    Ce qui est mauvais.
     */
    public IntegerProperty anyValueProperty() {
        return this.anyValue;
    }

    public void setAnyValue(int anyValue) {
        this.anyValue.set(anyValue);
    }

    public int getPositiveValue() {
        return this.positiveValue.get();
    }

    /*
    On fournit une ReadOnlyIntegerProperty qui "emballe" positiveValue afin d'empêcher une modification de la valeur par le code client.
     */
    public ReadOnlyIntegerProperty positiveValueProperty() {
        return this.positiveValue;
    }

    // PRE : positiveValue >= 0
    public void setPositiveValue(int positiveValue) {
        if (positiveValue < 0) {
            throw new RuntimeException("Try to put negative value for positiveValue : " + positiveValue);
        }
        this.positiveValue.set(positiveValue);
    }

    public int getWrongPositiveValue() {
        return this.wrongPositiveValue.get();
    }

    /*
    Erreur de conception : en permettant un accès direct à la propriété, le code client peut modifier la valeur sans passer par le setter =>
    peut fournir une valeur négative (contrairement aux specs/règles métier).
    C'est pour cette raison qu'il faut fournir une ReadOnlyIntegerProperty et pas une IntegerProperty.
     */
    public IntegerProperty wrongPositiveValueProperty() {
        return this.wrongPositiveValue;
    }

    // PRE : positiveVal >= 0
    public void setWrongPositiveValue(int positiveVal) {
        if (positiveVal < 0) {
            throw new RuntimeException("Try to put negative value for wrongPositiveValue : " + positiveVal);
        }
        this.wrongPositiveValue.set(positiveVal);
    }

}
