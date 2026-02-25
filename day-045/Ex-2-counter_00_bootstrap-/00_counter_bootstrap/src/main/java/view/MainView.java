package view;

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


    public CounterView get_counter_view(){
        return this.counter_view;
    }
}
