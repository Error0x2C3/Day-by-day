package view;

import javafx.geometry.Insets;
import javafx.scene.layout.BorderPane;
import javafx.scene.layout.VBox;
import model.CounterModel;

/*
MainView Créer cette classe dans le même package counter.view.
Elle hérite de la classe VBox de JavaFX.
Elle représente l'élément raçine de la scène
et ne doit contenir (à ce stade) qu'une instance de CounterView.
 */

/*
   STRUCTURE GENERALE :

   BorderPane :
       au Centre un Vbox :
           1 ère colonne : le label
           2 ème colonne : un Hbox range les éléments de façon verticale.
    */
public class MainView extends VBox {
    CounterView counter_view;
    public MainView(BorderPane root_border_panne, CounterModel counter_model){
        this.counter_view =  new CounterView( root_border_panne, counter_model);
    }

    public MainView(CounterModel counterModel){
        BorderPane root_border_panne = new BorderPane();
        /*
        setPadding définit la marge intérieure (l'espace vide)
        entre la bordure de mon conteneur (BorderPane)
        et les éléments que je vais mettre à l'intérieur.
        new Insets(top, right, bottom, left).
         */
        root_border_panne.setPadding(new Insets(20,20,20,20));
        this(root_border_panne, new CounterModel());
    }


    public CounterView get_counter_view(){
        return this.counter_view;
    }
}
