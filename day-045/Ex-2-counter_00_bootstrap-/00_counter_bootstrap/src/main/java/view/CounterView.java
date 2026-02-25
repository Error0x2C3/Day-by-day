package view;

import javafx.geometry.Insets;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.control.TextField;
import javafx.scene.layout.BorderPane;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;
import model.CounterModel;

/*
CounterView hérite de la classe VBox de JavaFX.
Elle correspond à la partie de la vue qui
implémente le compteur et ses différents
éléments graphiques (bordure, champ texte, libellés et boutons).
 */
public class CounterView extends VBox{
    private BorderPane root_border_panne;
    private CounterModel counter_model;
    public CounterView(BorderPane root_border_panne, CounterModel counter_model){
        this.root_border_panne = root_border_panne; // le BoderPane qui contiendra toutes les autres scènes.
        this.counter_model = counter_model;
    }
    public BorderPane get_root_border_panne(){
        return this.root_border_panne; // border_panne vierge par défaut.
    }
    public CounterModel get_counter_model(){
        return this.counter_model;
    }

     /*
    STRUCTURE GENERALE :

    BorderPane :
        au Centre un Vbox :
            1 ère colonne : le label
            2 ème colonne : un Hbox range les éléments de façon verticale.
     */

    // On ajoute des scènes dans le BoderPanel vierge.
    public BorderPane get_border_panel(){
        // BorderPane root_border_panne = new BorderPane();
        /*
        setPadding définit la marge intérieure (l'espace vide)
        entre la bordure de mon conteneur (BorderPane)
        et les éléments que je vais mettre à l'intérieur.
        new Insets(top, right, bottom, left).
         */
        // this.get_root_border_panne().setPadding(new Insets(20,20,20,20));
        // ajout de La VBox dans le BorderPane.
        // --------------
            this.get_vbox_panel();
        // --------------
        return  this.get_root_border_panne();
    }
    public VBox get_vbox_panel(){
        // Début de la VBox.
        // ------------------------------------------------
            VBox vbox_panel = new VBox(5); // Impose un espace de 5 px entre chaque étage.
            vbox_panel.setPadding(new Insets(15,15,15,15)); // Un espace de 15 px entre la bordure de la VBox et les éléments à l'intérieur.
            // Définit une bordure noire de 1 pixel et une couleur de fond (optionnel) sur la VBox.
            vbox_panel.setStyle("-fx-border-color: black; -fx-border-width: 1; -fx-border-style: solid;");
            // Je mets la VBox  au centre de la scene BorderPane vierge.
            this.get_root_border_panne().setCenter(vbox_panel);
            /*
            Dans une VBox, les éléments s'empilent
            dans l'ordre où je les ajoute au code.
            Le premier ajouté est en haut, le dernier est en bas.
             */
            // Au 1 er étage de la VBox :
            // ------------------------------------------------------
                TextField textField_name_user = new TextField();
                counter_model.set_user_name(textField_name_user.getText());
                /*
                setAlignment(Pos.CENTER)
                pour que le texte ne reste pas collé à gauche
                à l'intérieur du champ.
                Utile pour les label, pas pour TextField.
                */
                textField_name_user.setAlignment(javafx.geometry.Pos.CENTER);
                // J'ajoute le texte dans la VBox.
                vbox_panel.getChildren().add(textField_name_user);
                // Ajout du message d'erreur:
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
                        // Mets à jour la variable user_name du model.
                        counter_model.set_user_name(textField_name_user.getText());
                        if( counter_model.message_error_must_be_display()){
                            // On ajoute le message d'erreur.
                            // Le message d'erreur sera ajouté ET affiché après l'élément qui se trouve à l'index X-1.
                            vbox_panel.getChildren().add(1,label_text_error_name_user()); // L'index dans ce cas = numéro de l'étage dans la VBox.
                        }
                    });
                // --------------------------------------------------------
            // ------------------------------------------------------
            // Fin du 1 er étage de la VBox.
            // Au 2 ème étage de la VBox :
            // ---------------------------------------------------------------------------------
                // Début de la HBox :
                // -----------------------------------------------------------
                    HBox hbox_panel = new HBox(27); // Imopose un espace de 27 px entre chaque élément de la rangée.
                    // Ajoute de la HBox à la deuxième étage de la VBox.
                    vbox_panel.getChildren().add(hbox_panel);
                    // Je le metsd ici en prévison de les utiliser dans les event de btn_less et btn_add.
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
                            try{
                                counter_model.set_nbr( counter_model.get_nbr()-1 );
                                String txt = String.valueOf(counter_model.get_nbr());
                                /*
                                Chaque appel à setText efface
                                entièrement le texte précédent du composant.
                                Dès que la ligne de code est exécutée,
                                l'interface graphique se rafraîchit pour l'utilisateur.
                                */
                                label_text_number.setText(txt);
                                System.out.println(counter_model.get_nbr());
                            } catch (RuntimeException ex) {
                                String txt = String.valueOf(counter_model.get_nbr());
                                label_text_number.setText(txt);
                                System.out.println(counter_model.get_nbr());
                                if(counter_model.btn_less_must_be_disabled()){
                                    btn_less.setDisable(true);
                                    btn_add.setDisable(false);
                                }
                            }
                        });
                    // ----------------------------------------

                    // Affichage du chiffre au milieu du HBox.
                    // ----------------------------------------
                        label_text_number.setText(String.valueOf(counter_model.get_nbr()));
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
                            try {
                                counter_model.set_nbr( counter_model.get_nbr()+1);
                                label_text_number.setText(String.valueOf(counter_model.get_nbr()));
                                /*
                                Chaque appel à setText efface
                                entièrement le texte précédent du composant.
                                Dès que la ligne de code est exécutée,
                                l'interface graphique se rafraîchit pour l'utilisateur.
                                */
                                System.out.println(counter_model.get_nbr());
                            } catch (RuntimeException ex) {
                                System.out.println(counter_model.get_nbr());
                                if(counter_model.btn_add_must_disabled()){
                                    btn_add.setDisable(true);
                                    btn_less.setDisable(false);
                                }

                            }
                        });
                    // ----------------------------------------
                // -----------------------------------------------------------
                // Fin de la HBox.
            // ---------------------------------------------------------------------------------
            // Fin de la 2 ème étage de la VBox.
        // ------------------------------------------------
        // Fin de la VBox.
        return vbox_panel;
    }
    // Gestion du message d'erreur et de son style CSS.
    public Label label_text_error_name_user(){

        Label label_text_error = new Label(this.get_counter_model().get_message_error_name_user());
        // Texte en rouge.
        label_text_error.setStyle("-fx-text-fill: red; -fx-font-weight: bold;");
        label_text_error.setAlignment(javafx.geometry.Pos.CENTER);
        label_text_error.setMaxWidth(Double.MAX_VALUE);
        return  label_text_error;
    }
}

