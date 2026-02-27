package model;

import java.util.Observable;

/*
Le modèle étant responsable du respect des règles-métier,
il doit toujours être dans un état acceptable.
Donc, une modification entraînant une violation de ces règles
devra déclencher une RuntimeException
(voir l'exemple de la classe Date dans le cours de Programmation Orientée Objet).
 */
public class CounterModel extends Observable {

    private int nbr ;
    public CounterModel(){
        this.nbr = (int)(Math.random() * (3 - (-3) + 1)) + (-3); // Chiffre au hasard entre -3 et 3 compris.
    }


    public int get_nbr() {
        return this.nbr;
    }
    public String get_string_nbr() {
        return String.valueOf(nbr);
    }
    // Gestion règles métiers pour le nombre.
    // ---------------------------------------------------
    public void set_nbr(int number) {
        if(number < -3 || number > 3){
            throw new RuntimeException("Le nombre donné n'est pas compris entre -3 et 3 compris.");
        }
        this.nbr = number;
        this.setChanged();
        this.notifyObservers();
        /*
        La méthode notifyObservers() appelle la méthode update contenue dans chaque classe observateur.
        Il se passe lui-même en paramètre dans la méthode update,
        Observable o dans argument de la méthode update, c'est l'instance de la classe Modèle qui vient de changer.
        + peut mettre un paramètre en plus dans update ex :
        this.notifyObservers(arg: this) correspond à l'argument Object arg de la méthode update.
         */
    }
    // ---------------------------------------------------

    // Gestion règles métiers pour les boutons.
    // ---------------------------------------------------
    public boolean btn_less_must_be_disabled(){
        if(this.get_nbr() <= -3){
            return true;
        }
        return false;
    }
    public boolean btn_add_must_disabled(){
        if(this.get_nbr() >= 3){
            return true;
        }
        return false;
    }
    // ---------------------------------------------------
}
