package model;
/*
Le modèle étant responsable du respect des règles-métier,
il doit toujours être dans un état acceptable.
Donc, une modification entraînant une violation de ces règles
devra déclencher une RuntimeException
(voir l'exemple de la classe Date dans le cours de Programmation Orientée Objet).
 */
public class CounterModel {

    private int nbr ;
    private String user_name;
    private String message_error_name_user;
    public CounterModel(){
        this.nbr = (int)(Math.random() * (3 - (-3) + 1)) + (-3); // Chiffre au hasard entre -3 et 3 compris.
        this.message_error_name_user = "Trimmed length must be >= 3.";
        this.user_name ="";
    }

    public void set_user_name(String user_name){
        this.user_name = user_name;
    }
    public String get_user_name(){
        return this.user_name;
    }
    public String get_message_error_name_user(){
        return this.message_error_name_user;
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
    }
    // ---------------------------------------------------

    // Gestion règles métiers pour le message d'erreur.
    // ---------------------------------------------------
    public boolean message_error_must_be_display(){
        if(this.get_user_name().trim().length() < 3){
            return true;
        }
        return false;
    }
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
