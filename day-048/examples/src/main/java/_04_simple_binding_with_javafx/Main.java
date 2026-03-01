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

    // Assemblage de la structure des scènes JavaFx
    private void configComponents() {
        // On met chaque Label relatif ensuite son TextField relatifs au name dans le HBox Name.
        hbName.getChildren().addAll(lbFirstName, tfFirstName, lbLastName, tfLastName);
        // On met le label ensuite son TextField relatif à la longueur dans le HBox Length.
        hbLength.getChildren().addAll(lbLength, tfLength);
        // On met chaque le CheckBox et le Btn dans le HbBox action.
        hbActions.getChildren().addAll(chbxSameNames, btcNamesOk);
        // On met tous les HBox dans la VBox.
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

    // Styliser les différents composants des scènes.
    private void beautify() {
        /*
        setPrefColumnCount(int value) est une petite
        astuce de mise en page spécifique aux champs de texte (TextField).

        Cette méthode définit la largeur souhaitée du champ,
        non pas en pixels (ce qui est difficile à évaluer selon la police),
        mais en nombre de caractères.
         */
        tfFirstName.setPrefColumnCount(10);
        tfLength.setPrefColumnCount(3);
        /*
        setSpacing(value) sert à définir l'espace vide (la marge interne)
        entre les enfants d'un conteneur de type HBox (ou VBox).
         */
        hbName.setSpacing(10);
        /*
        setAlignment(Pos.CENTER_LEFT)
        Elle définit l'alignement des composants à l'intérieur de ma HBox.
        Pos.CENTER_LEFT :
            CENTER (Vertical) : Les éléments sont centrés de haut en bas.
            LEFT (Horizontal) : Les éléments sont poussés vers la gauche de la boîte.
        C'est indispensable car :
        (Dans une HBox, les composants n'ont pas tous la même hauteur par défaut.)
            Un Label est souvent assez "fin".
            Un TextField est un peu plus "épais" à cause de sa bordure.
        Sans CENTER_LEFT :
            Tes textes (Label) seraient collés tout en haut de la ligne,
            alors que tes champs de saisie (TextField) seraient un peu plus bas.
            Visuellement, c'est décalé et désagréable à l'œil.
        Avec CENTER_LEFT :
            Le texte de ton Label et ton champ de saisie
            seront parfaitement alignés sur la même ligne imaginaire horizontale.
        Ex le cadre de ma HBox (le rectangle pointillé) :
        -------------------------------------------
        |                                         |
        |  [Label] [TextField]                    |  <-- Alignés au milieu verticalement
        |                                         |      et collés à gauche.
        -------------------------------------------
         */
        hbName.setAlignment(Pos.CENTER_LEFT);
        hbLength.setSpacing(10);
        hbLength.setAlignment(Pos.CENTER_LEFT);
        hbActions.setSpacing(10);
        hbActions.setAlignment(Pos.CENTER_LEFT);
        // SetSpacing(value): Définit l'espace entre les enfants à l'intérieur de la boîte root.
        root.setSpacing(10);
        /*
        root.setPadding(new Insets(10)); (L'espace autour) :
        Le Padding (marge intérieure), c'est l'espace entre le bord
        de ta fenêtre (le container root) et les éléments qui sont dedans.
            Sans padding, tes boutons toucheraient littéralement
            le bord gauche ou le haut de la fenêtre Windows/Mac.

            Avec Insets(10), tu crées une zone de sécurité de 10 pixels tout autour.
            Alternatif : root.setPadding(top,right,bottom,left).
        Ex un cadre de tableau :
        _________________________________  <-- Bord de la fenêtre (root)
        |   _________________________   |
        |  |  (P)  PADDING (10)      |  |
        |  |   ___________________   |  |
        |  |  |    Bouton 1       |  |  |
        |  |  |___________________|  |  |
        |  |           ^             |  |
        |  |      SPACING (10)       |  |
        |  |           v             |  |
        |  |   ___________________   |  |
        |  |  |    Bouton 2       |  |  |
        |  |  |___________________|  |  |
        |  |_________________________|  |
        |_______________________________|
         */
        root.setPadding(new Insets(10));
    }

    private void configBindings() {

        // On lie de manière bidirectionnelle les propriétés text du composant graphique avec les propriétés correspondantes du modèle.
        /*
        Avant :
            Il fallait faire setText(value).
            Qui est une action unique et ponctuelle.
            On donne une valeur à un instant T, et c'est fini.
        Maintenant :
            bindBidirectional est un contrat de mariage permanent/un tunnel à double sens entre le TextField et mon objet person.

        Côté Vue → Modèle :
            Si l'utilisateur tape "Jean" dans le TextField,
            person.firstName devient "Jean" automatiquement.
        Côté Modèle → Vue :
            Si, dans ton code Java, tu fais person.setFirstName("Paul"),
            le texte dans le TextField change tout seul pour afficher "Paul".
         */
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
