package scratch.view;

import javafx.geometry.Insets;
import javafx.geometry.Pos;
import javafx.scene.control.*;
import javafx.scene.layout.*;

public class MainView extends VBox {


    public MainView() {
        configComponents();
    }

    // Assemblage de la structure des scènes JavaFx
    private void configComponents() {
        VBox root_vbox = new VBox();
        // --------------Enfants de la root_vbox--------------
        // 1 ère ligne de root_vbox.
        MenuBar menu_bar = create_menu_bar();
        // 2 ème ligne de root_vbox.
        HBox root_second_hbox_colonnes = new HBox(15);
        root_second_hbox_colonnes.setPadding(new Insets(10));
        // ------Enfants de root_second_hbox_colonnes------
        VBox colonne_gauche =  creer_colonne_gauche();
        VBox colonne_centre = creer_colonne_centre();
        VBox colonne_droite = creer_colonne_droite();

        // On donne plus d'espace à la scène de dessin et au programme.
        HBox.setHgrow(colonne_centre, Priority.ALWAYS);
        HBox.setHgrow(colonne_droite, Priority.ALWAYS);

        root_second_hbox_colonnes.getChildren().addAll(colonne_gauche,colonne_centre,colonne_droite);
        // ------Enfants de root_second_hbox_colonnes------

        // --------------Enfants de la root_vbox--------------
        // root_vbox.getChildren().addAll(menu_bar,root_second_hbox_colonnes);
        this.getChildren().addAll(menu_bar,root_second_hbox_colonnes);

    }

    public MenuBar create_menu_bar(){
        MenuBar root_second_menu_bar = new MenuBar(); // la barre de menu.
        // ----------------------------Enfants de root_second_menu_bar ---------------------------------------
        Menu file_menu = new Menu("File"); // Le menu "File".
        // -----Enfants de file_menu-----
        MenuItem openItem = new MenuItem("Open..."); // L'élément "Open".
        MenuItem menuSave = new MenuItem("Save As...");
        file_menu.getItems().addAll(openItem,menuSave);
        // -----Enfants de file_menu-----

        root_second_menu_bar.getMenus().addAll(file_menu);
        // ----------------------------Enfants de root_second_menu_bar ---------------------------------------
        return root_second_menu_bar;
    }
    public VBox creer_colonne_gauche(){
        VBox root_vbox_palette_action = new VBox(10);
        // ----------------------------Enfants de root_third_palette_action ---------------------------------------
        Label label_actions = new Label("Palette d'actions");
        root_vbox_palette_action.getChildren().add(label_actions);

        ListView<String> paletteList_actions = new ListView<>();
        // Données bidon pour tester le visuel :
        paletteList_actions.getItems().addAll("Avancer de", "Tourner à gauche de", "Lever stylo", "Abaisser stylo");
        root_vbox_palette_action.getChildren().add(paletteList_actions);

        Button btnAjouter = new Button("Ajouter au programme");
        root_vbox_palette_action.getChildren().add(btnAjouter);
        // ----------------------------Enfants de root_third_palette_action ---------------------------------------
        return root_vbox_palette_action;
    }

    public VBox creer_colonne_centre(){
        VBox root_vbox_programme = new VBox(10);
        // ----------------------------Enfants de root_third_palette_action ---------------------------------------
        Label label_programme = new Label("Programme");
        root_vbox_programme.getChildren().add(label_programme);

        ListView<String> listView_palette_programme = new ListView<>();
        // Données bidon pour tester le visuel :
        listView_palette_programme.getItems().addAll("Lever le Stylo", "Avancer de 30° ", "Tourner à gauche de 90 °");
        root_vbox_programme.getChildren().add(listView_palette_programme);

        HBox boutons_box = new HBox(5);
        boutons_box.setAlignment(Pos.CENTER);
        // -------Enfants de boutons_box-------
        Button btn_monter = new Button("Monter"), btn_descendre = new Button("Descendre"), btn_dupliquer = new Button("Dupliquer"),
        btn_supprimer = new Button("Supprimer"), btn_vider_tout = new Button("Vider tout");
        boutons_box.getChildren().addAll(btn_monter,btn_descendre,btn_dupliquer,btn_supprimer,btn_vider_tout);
        root_vbox_programme.getChildren().add(boutons_box);
        // -------Enfants de boutons_box-------


        TitledPane title_pane_detail_action = new TitledPane();
        title_pane_detail_action.setText("Detail de l'action");
        title_pane_detail_action.setStyle("-fx-border-color: lightgray; -fx-padding: 10;");
        root_vbox_programme.getChildren().add(title_pane_detail_action);
        // --------------------Enfants de title_pane_detail_action--------------------

        HBox hbox_for_title_pane_detail_action = new HBox(5);
        root_vbox_programme.getChildren().add(hbox_for_title_pane_detail_action);
        // -------------Enfants de hbox_for_title_pane_detail_action-------------
        Label label_avancer_de = new Label("Avancer de ");
        TextField text_avancer_de_number = new TextField();
        Label label_pixels = new Label("Pixels");
        hbox_for_title_pane_detail_action.getChildren().addAll(label_avancer_de,text_avancer_de_number,label_pixels);
        // -------------Enfants de hbox_for_title_pane_detail_action-------------

        title_pane_detail_action.setContent(hbox_for_title_pane_detail_action);
        // --------------------Enfants de title_pane_detail_action--------------------

        // ----------------------------Enfants de root_third_palette_action ---------------------------------------
        return  root_vbox_programme ;
    }

    public VBox creer_colonne_droite(){
        VBox root_third__vbox_scene = new VBox(10);
        // ----------------------------Enfants de root_third_palette_action ---------------------------------------
        Label label_scene = new Label("Scène");
        root_third__vbox_scene.getChildren().add(label_scene);
        Pane dessinPane = new Pane();
        dessinPane.setPrefSize(400, 400); // Taille par défaut
        dessinPane.setStyle("-fx-background-color: white; -fx-border-color: lightgray;");
        root_third__vbox_scene.getChildren().add(dessinPane);

        HBox boutons_box = new HBox(5);
        boutons_box.setAlignment(Pos.CENTER);
        root_third__vbox_scene.getChildren().add(boutons_box);
        // -----Enfants de boutons_box-----
        Button btn_reinit = new Button("Ré-initialiser"), btn_suivant = new Button("Suivant");
        boutons_box.getChildren().addAll(btn_reinit,btn_suivant);
        // -----Enfants de boutons_box-----
        // ----------------------------Enfants de root_third_palette_action ---------------------------------------
        return root_third__vbox_scene;
    }
}