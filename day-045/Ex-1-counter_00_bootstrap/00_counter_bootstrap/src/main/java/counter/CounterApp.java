package counter;

import javafx.application.Application;
import javafx.geometry.Insets;
import javafx.scene.Scene;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.control.TextField;
import javafx.scene.layout.BorderPane;
import javafx.scene.layout.GridPane;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;
import javafx.stage.Stage;

public class CounterApp extends Application {
    Integer nbr = (int)(Math.random() * (3 - (-3) + 1)) + (-3); // Chiffre au hasard entre -3 et 3 compris.
    @Override
    // Stage (la fenêtre) > Scene (le contenu de la fenêtre) > Root (l'organisation des éléments).
    public void start(Stage stage) {
        BorderPane root_border_panne = new BorderPane();
        /*
        setPadding définit la marge intérieure (l'espace vide)
        entre la bordure de ton conteneur (BorderPane)
        et les éléments que tu vas mettre à l'intérieur.
        new Insets(top, right, bottom, left).
         */
        root_border_panne.setPadding(new Insets(20,20,20,20));

        // Début de la VBox.
        // ------------------------------------------------
        VBox vbox_panel = new VBox(5); // Impose un espace de 5 px entre chaque étage.
        vbox_panel.setPadding(new Insets(15,15,15,15)); // Un espace de 15 px entre la bordure de la VBox et les éléments à l'intérieur.
        // Définit une bordure noire de 1 pixel et une couleur de fond (optionnel) sur la VBox.
        vbox_panel.setStyle("-fx-border-color: black; -fx-border-width: 1; -fx-border-style: solid;");
        // Je mets la VBox  au centre de la scene BorderPane.
        root_border_panne.setCenter(vbox_panel);
        /*
        Dans une VBox, les éléments s'empilent
        dans l'ordre où je les ajoutes au code.
        Le premier ajouté est en haut, le dernier est en bas.
         */
        // Au 1 er étage de la VBox :
            TextField textField_name_user = new TextField();
            /*
            setAlignment(Pos.CENTER)
            pour que le texte ne reste pas collé à gauche
            à l'intérieur du champ.
             */
            textField_name_user.setAlignment(javafx.geometry.Pos.CENTER);
            // J'ajoute le texte dans la VBox.
            vbox_panel.getChildren().add(textField_name_user);

            // Ajout du message d'erreur.
            // --------------------------------------------------------
            textField_name_user.setOnAction(e ->{
                // Tout ce qui est ici est "mis en attente"
                // et ne s'exécute QUE si :
                //  setOnAction se déclenche quand on clique sur le bouton (ou qu'on appuie sur Entrée quand le champ a le focus).
                // 1. On nettoie les anciens messages d'erreur pour ne pas les cumuler.
                //    Parmi tous les éléments présents dans ma colonne (VBox),
                //    supprime ceux qui sont des étiquettes de texte de type (Label) ET qui contiennent un message spécifique "Trimmed".
                vbox_panel.getChildren().removeIf(node -> node instanceof Label && ((Label)node).getText().contains("Trimmed") );
                // 2. On récupère le texte et on vérifie la condition.
                if(textField_name_user.getText().trim().length() < 3){
                    // On ajoute le message d'erreur.
                    // Le message d'erreur sera ajouté ET affiché après l'élément qui se trouve à l'index X-1.
                    vbox_panel.getChildren().add(2,label_text_error_name_user()); // L'index dans ce cas = numéro de l'étage dans la VBox.
                }
            });
            // --------------------------------------------------------

        // Fin de la 1 ère étage de la VBox.
        // Au 2 ème étage de la VBox :
            // Début de la HBox :
            // ------------------------------------------------
                HBox hbox_panel = new HBox(27); // Imopose un espace de 27 px entre chaque élément de la rangée.
                // Ajoute la HBox à la deuxième étage de la VBox.
                vbox_panel.getChildren().add(hbox_panel);
                // Je le metsd ici en prévison de l'utiliser dans les event de btn_less et btn_add.
                Label label_text_number = new Label();
                Button btn_less = new Button("-");
                Button btn_add = new Button("+");

                // Bouton pour le "-" ET gestion.
                // ----------------------------------------
                // btn_less = new Button("-");
                // Ajout du btn dans le HBox.
                hbox_panel.getChildren().add(btn_less);
                // Se désactive si nbr est sup à 3 ou inf à -3 et fait -1 à nbr.
                btn_less.setOnAction(e->{

                    nbr--;
                    String txt = String.valueOf(nbr);
                    label_text_number.setText(txt);
                    System.out.println(nbr);
                    if(nbr <= -3){
                        btn_less.setDisable(true);
                        btn_add.setDisable(false);
                    }
                });
                // ----------------------------------------

                // Affichage du chiffre au milieu du HBox.
                // ----------------------------------------
                label_text_number.setText(String.valueOf(nbr));
                label_text_number.setStyle("-fx-text-fill: black; -fx-font-weight: bold;");

                /*
                On va dire à l'élément du milieu (mon Label) qu'il doit prendre
                tout l'espace disponible dans la HBox.
                Comme il pousse les murs,
                il va coincer le bouton "-" à gauche et le bouton "+" à droite.
                 */
                // setHgrow (Priority ALWAYS): Si la HBox a de l'espace vide en trop, c'est toi qui dois le récupérer."
                /*
                Dans mon code, si je ne mets pas de setHgrow :
                Le bouton - prend 20px.
                Le Label prend 10px.
                Le bouton + prend 20px.
                Résultat : Tout le reste de la HBox (disons 200px) reste vide à droite.
                En mettant HBox.setHgrow(label_text_number, Priority.ALWAYS) :
                Le Label devient un "aspirateur à espace".
                Il va prendre les 200px vides pour lui tout seul.
                Comme il est situé entre les deux boutons,
                il va s'étirer comme un élastique, poussant les boutons vers les extrémités.
                 */
                HBox.setHgrow(label_text_number, javafx.scene.layout.Priority.ALWAYS);// Priority.ALWAYS, on lui donnes un "super-pouvoir". Il devient prioritaire pour dévorer tout le vide restant dans la HBox.
                /*
                Par défaut, un Label est "timide" :
                il ne prend que la largeur nécessaire pour
                afficher son texte (par exemple, juste la place pour le chiffre "2").
                Même si on utilise setHgrow sur la HBox,
                le Label pourrait décider de rester
                petit au milieu de l'espace qu'on lui a donné.
                Avec label_text_number.setMaxWidth(Double.MAX_VALUE);
                On lui donne l'ordre de s'étirer au max pour rempli
                tout l'espase que Hbox lui autorise à prendre.
                */
                label_text_number.setMaxWidth(Double.MAX_VALUE);
                /*
                Puisque le Label occupe maintenant tout le centre,
                on lui dit setAlignment(Pos.CENTER)
                pour que le chiffre "2" ne reste pas collé à gauche
                à l'intérieur de sa nouvelle grande zone mais soit au centre.
                 */
                label_text_number.setAlignment(javafx.geometry.Pos.CENTER);
                // Ajout du texte dans le Hbox.
                hbox_panel.getChildren().add(label_text_number);
                // ----------------------------------------

                // Bouton pour le "+" ET gestion.
                // ----------------------------------------
                // btn_add = new Button("+");
                // Ajout du btn dans le HBox.
                hbox_panel.getChildren().add(btn_add);
                // Se désactive si nbr est sup à 3 ou inf à -3 et fais +1 à nbr.
                btn_add.setOnAction(e->{
                    nbr++;
                    label_text_number.setText(String.valueOf(nbr));
                    System.out.println(nbr);
                    if(nbr >= 3){
                        btn_add.setDisable(true);
                        btn_less.setDisable(false);
                    }
                });
                // ----------------------------------------

            // Fin de la HBox.
            // ------------------------------------------------
        // Fin de la 2 ème étage de la VBox.



        // ------------------------------------------------
        // Fin de la VBox.

        /*
        root_border_panne (le contenu) mon premier arguement.
        Je dis :
            Mon contenu principal sera ce BorderPane
            (que j'ai nommé root_border_panne).
            Tout ce que j'ai ajouté dans root_border_panne
            sera affiché dans cette scène.
         new Scene(root_border_panne,largeur de la fenêtre en pixels,longeur de la fenêtre en pixels);
         */
        Scene scene = new Scene(root_border_panne,300,150);
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

    public Label label_text_error_name_user(){

        Label label_text_error = new Label("Trimmed length must be >= 3.");
        // Texte en rouge.
        label_text_error.setStyle("-fx-text-fill: red; -fx-font-weight: bold;");
        label_text_error.setAlignment(javafx.geometry.Pos.CENTER);
        label_text_error.setMaxWidth(Double.MAX_VALUE);
        return  label_text_error;
    }
}