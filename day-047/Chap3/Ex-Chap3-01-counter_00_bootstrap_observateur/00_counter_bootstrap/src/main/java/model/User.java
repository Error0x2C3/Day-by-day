package model;

import java.util.Observable;

public class User extends Observable {
    private String user_name;
    private String message_error_name_user;
    public User(String name, String message){
        this.user_name = name;
        this.message_error_name_user = message;
    }
    public User(String name){
        this(name,"Trimmed length must be >= 3.");
    }
    public User(){
        this("");
    }

    public void set_user_name(String user_name){
        this.user_name = user_name;
        this.setChanged();
        this.notifyObservers();
        // La méthode notifyObservers() appelle les méthode udpate contenu dans les classes observateurs.
    }
    public String get_user_name(){
        return this.user_name;
    }
    public String get_message_error_name_user(){
        return this.message_error_name_user;
    }

    // Gestion règles métiers pour le message d'erreur.
    // ---------------------------------------------------
    public boolean message_error_must_be_display(){
        if(this.get_user_name().trim().length() < 3 && !this.get_user_name().trim().isEmpty()){
            return true;
        }
        return false;
    }
    // ---------------------------------------------------
}
