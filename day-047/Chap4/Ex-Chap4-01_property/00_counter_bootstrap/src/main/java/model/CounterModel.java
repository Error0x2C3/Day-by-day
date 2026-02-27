package model;

import javafx.beans.property.IntegerProperty;
import javafx.beans.property.ReadOnlyIntegerProperty;
import javafx.beans.property.SimpleIntegerProperty;

/*
Le modèle étant responsable du respect des règles-métier,
il doit toujours être dans un état acceptable.
Donc, une modification entraînant une violation de ces règles
devra déclencher une RuntimeException
(voir l'exemple de la classe Date dans le cours de Programmation Orientée Objet).
 */
public class CounterModel{

//    private int nbr ;
    private IntegerProperty nbr_property;
    private final CounterChangeType counter_change_type;
    public CounterModel(){
//      this.nbr = (int)(Math.random() * (3 - (-3) + 1)) + (-3); // Chiffre au hasard entre -3 et 3 compris.
        this.nbr_property = new SimpleIntegerProperty(); // Représente n'importe quel entier + Observable.
        this.counter_change_type = CounterChangeType.VALUE_CHANGED;

    }


//    public int get_nbr() {
//        return this.nbr;
//    }
//    public String get_string_nbr() {
//        return String.valueOf(nbr);
//    }

    public ReadOnlyIntegerProperty nbr_read_property() {
        return this.nbr_property;
    }
    // Gestion règles métiers pour le nombre.
    // ---------------------------------------------------
    public void set_nbr_property(int nbr){
        if( nbr < -3 || nbr > 3){
            throw new RuntimeException("Le nombre donné n'est pas compris entre -3 et 3 compris.");
        }
        this.nbr_property.set(nbr);
    }
    // ---------------------------------------------------

    public CounterChangeType get_counter_change_type(){return this.counter_change_type;}

//    // Gestion règles métiers pour le nombre.
//    // ---------------------------------------------------
//    public void set_nbr(int number) {
//        if(number < -3 || number > 3){
//            throw new RuntimeException("Le nombre donné n'est pas compris entre -3 et 3 compris.");
//        }
//        this.nbr = number;
//    }
    // ---------------------------------------------------

    // Gestion règles métiers pour les boutons.
    // ---------------------------------------------------
    public boolean btn_less_must_be_disabled(){
        if(this.nbr_property.get() <= -3){
            return true;
        }
        return false;
    }
    public boolean btn_add_must_disabled(){
        if(this.nbr_property.get() >= 3){
            return true;
        }
        return false;
    }
    // ---------------------------------------------------
}
