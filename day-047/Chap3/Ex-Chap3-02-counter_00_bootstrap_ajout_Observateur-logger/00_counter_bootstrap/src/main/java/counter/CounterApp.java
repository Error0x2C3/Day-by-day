package counter;
import javafx.application.Application;
import javafx.geometry.Insets;
import javafx.scene.Scene;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.control.TextField;
import javafx.scene.layout.BorderPane;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;
import javafx.stage.Stage;
import model.CounterModel;
import view.MainView;

/*
CounterApp contient les méthodes standard main et start.
Cependant, on limitera au maximum le code dans cette classe en se
restreignant à instancier le modèle et la vue
(voir ci-dessous) et à configurer la Scene de JavaFX.
 */
public class CounterApp extends Application {
    Integer nbr = (int)(Math.random() * (3 - (-3) + 1)) + (-3); // Chiffre au hasard entre -3 et 3 compris.
    @Override
    // Stage (la fenêtre) > Scene (le contenu de la fenêtre) > Root (l'organisation des éléments).
    public void start(Stage stage) {
//        BorderPane root_border_panne = new BorderPane();
//        /*
//        setPadding définit la marge intérieure (l'espace vide)
//        entre la bordure de mon conteneur (BorderPane)
//        et les éléments que je vais mettre à l'intérieur.
//        new Insets(top, right, bottom, left).
//         */
//        root_border_panne.setPadding(new Insets(20,20,20,20));
//        MainView main_view = new MainView(root_border_panne,new CounterModel());
        MainView main_view = new MainView(new CounterModel());
        /*
        root_border_panne (le contenu) mon premier arguement.
        Je dis :
            Mon contenu principal sera ce BorderPane
            (que j'ai nommé root_border_panne).
            Tout ce que j'ai ajouté dans root_border_panne
            sera affiché dans cette scène.
         new Scene(root_border_panne,largeur de la fenêtre en pixels,longeur de la fenêtre en pixels);
         */
        Scene scene = new Scene( main_view.get_counter_view().get_border_panel(),300,150);
        stage.setTitle("Counter");
        // Je donne la scene à la fenêtre.
        stage.setScene(scene);
        // Je montre la fenêtre.
        stage.show();

    }

    /*
    STRUCTURE GENERALE :

    BorderPane :
        au Centre un Vbox :
            1 ère colonne : le label
            2 ème colonne : un Hbox range les éléments de façon verticale.
     */
    public static void main(String[] args) {
        launch();
    }

}