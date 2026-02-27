package _04_simple_binding_with_javafx;

import javafx.application.Application;
import javafx.geometry.Insets;
import javafx.geometry.Pos;
import javafx.scene.Scene;
import javafx.scene.control.Button;
import javafx.scene.control.CheckBox;
import javafx.scene.control.Label;
import javafx.scene.control.TextField;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;
import javafx.stage.Stage;

public class Main extends Application {
    public static void main(String[] args) {
        launch(args);
    }

    // Création du modèle
    private final Person person = new Person("toto", "Toto");

    private final VBox root = new VBox();
    private final HBox hbName = new HBox(), hbLength = new HBox(), hbActions  = new HBox();
    private final TextField tfFirstName = new TextField(), tfLastName = new TextField(), tfLength = new TextField();
    private final Label lbFirstName = new Label("Prénom"), lbLastName = new Label("Nom"), lbLength = new Label("Longueur du nom");
    private final CheckBox chbxSameNames = new CheckBox("Nom et prénom égaux");
    private final Button btcNamesOk = new Button("Rendre noms identiques (si différents)");

    @Override
    public void start(Stage stage) throws Exception {
        configComponents();
        beautify();
        configBindings();
        Scene scene = new Scene(root, 400, 200);
        stage.setScene(scene);
        stage.show();
    }

    private void configComponents() {
        hbName.getChildren().addAll(lbFirstName, tfFirstName, lbLastName, tfLastName);
        hbLength.getChildren().addAll(lbLength, tfLength);
        hbActions.getChildren().addAll(chbxSameNames, btcNamesOk);
        root.getChildren().addAll(hbName, hbLength, hbActions);

        /*
        On disable la checkbox pour que l'utilisateur ne puisse modifier sa valeur via l'UI (sinon, une exception se déclenche
        car la propriété selectedProperty de la checkbox est liée unidirectionnellement à un BooleanBinding du modèle).
         */
        chbxSameNames.setDisable(true);

        /*
        Bouton disabled si les 2 zones de textes sont les mêmes
         */
        btcNamesOk.setDisable(person.hasSameNames());
    }

    private void beautify() {
        tfFirstName.setPrefColumnCount(5);
        tfFirstName.setPrefColumnCount(10);
        tfLength.setPrefColumnCount(3);
        hbName.setSpacing(10);
        hbName.setAlignment(Pos.CENTER_LEFT);
        hbLength.setSpacing(10);
        hbLength.setAlignment(Pos.CENTER_LEFT);
        hbActions.setSpacing(10);
        hbActions.setAlignment(Pos.CENTER_LEFT);
        root.setSpacing(10);
        root.setPadding(new Insets(10));
    }

    private void configBindings() {

        // On lie de manière bidirectionnelle les propriétés text du composant graphique avec les propriétés correspondantes du modèle
        tfFirstName.textProperty().bindBidirectional(person.firstNameProperty());
        tfLastName.textProperty().bindBidirectional(person.lastNameProperty());

        /* On lie de manière unidirectionnelle la propriété text du composant graphique length avec un StringBinding,
           lui-même lié à la propriété nameLengthProperty du modèle
         */
        tfLength.textProperty().bind(person.nameLengthProperty().asString());

        // On lie la propriété selected de la checkbox avec le BooleanBinding du modèle
        chbxSameNames.selectedProperty().bind(person.sameNamesProperty());

        // La ligne suivante ne compile pas car disabledProperty est readonly => pas de binding possible
        // btcNamesOk.disabledProperty().bind(person.sameNamesProperty());

        // On doit "binder à la main" c'est à dire utiliser un listener sur la propriété du modèle
        person.sameNamesProperty().addListener((obs, oldValue, newValue) -> btcNamesOk.setDisable(newValue));

        // Le bouton permet de forcer les 2 noms à des valeurs identiques (hors casse) dans le modèle ;
        // le changement est automatiquement répercuté dans la vue grâce aux bindings
        btcNamesOk.setOnAction(e -> person.forceSameNames());
    }

}
