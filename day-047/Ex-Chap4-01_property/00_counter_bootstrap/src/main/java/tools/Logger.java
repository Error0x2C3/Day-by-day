package tools;

import javafx.scene.control.Label;
import javafx.scene.layout.VBox;
import model.CounterChangeType;
import model.CounterModel;
import model.User;

import java.util.Observable;
import java.util.Observer;

/*
Logger écrira un message représentant
l'état du compteur (valeur et nom) dans
la console lors de chaque notification reçue.
 */
public class Logger  implements Observer {
    @Override
    public void update(Observable o, Object arg) {
        if(o instanceof CounterModel){
            System.out.println(((CounterChangeType) arg).name()+" :");
            System.out.println("------------------------------------------------");
            System.out.println("La valeur actuel est : " +((CounterModel) o).get_nbr());
            System.out.println("------------------------------------------------");
        }
        if(o instanceof User){
            if(!((User) o).get_user_name().isEmpty()) {
                System.out.println(((CounterChangeType) arg).name()+" :");
                System.out.println("------------------------------------------------");
                System.out.println("Le nom de l'utilisateur actuel est : " + ((User) o).get_user_name());
                System.out.println("------------------------------------------------");
            }
        }
    }
}
