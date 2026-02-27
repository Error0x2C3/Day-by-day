package model;

import javafx.beans.property.ReadOnlyStringProperty;
import javafx.beans.property.SimpleStringProperty;
import javafx.beans.property.StringProperty;

import java.util.Observable;

public class User {
//    private String user_name;
    private StringProperty user_name_property;
    private String message_error_name_user;
    private final CounterChangeType counter_change_type;
    public User(String name, String message){
//      this.user_name = name;
        this.user_name_property = new SimpleStringProperty();
        this.message_error_name_user = message;
        this.counter_change_type = CounterChangeType.NAME_CHANGED;
    }
    public User(String name){
        this(name,"Trimmed length must be >= 3.");
    }
    public User(){
        this("");
    }

//    public String get_user_name(){
//        return this.user_name;
//    }
//    public void set_user_name(String user_name){
//        this.user_name = user_name;
//    }

    public ReadOnlyStringProperty user_name_read_property(){return this.user_name_property;}
    public void set_user_name_property(String name){
        this.user_name_property.set(name);
    }
    public String get_message_error_name_user(){
        return this.message_error_name_user;
    }
    public CounterChangeType get_counter_change_type(){return this.counter_change_type;}

    // Gestion règles métiers pour le message d'erreur.
    // ---------------------------------------------------
    public boolean message_error_must_be_display(){
        if(this.user_name_property.get().trim().length() < 3 && !this.user_name_property.get().trim().isEmpty()){
            return true;
        }
        return false;
    }
    // ---------------------------------------------------
}
