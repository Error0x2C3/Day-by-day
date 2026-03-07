package scratch.view;

import javafx.geometry.Insets;
import javafx.scene.control.*;
import javafx.scene.layout.*;

public class MainView extends VBox {
    private final VBox root_first_vbox = new VBox();
    // 1 ère ligne de root_vbox.
    private final MenuBar root_second_menu_bar = new MenuBar(); // la barre de menu.
    // 2 ème  ligne de root_vbox.
    private final HBox  root_second_hbox= new HBox();
    private final VBox root_third_palette_action = new VBox(), root_third_programme = new VBox(),root_third_scene = new VBox();


    // Assemblage de la structure des scènes JavaFx
    private void configComponents() {
        // La 1 ère et 2 ème ligne de root_vbox.
        root_first_vbox.getChildren().addAll(root_second_menu_bar,root_second_hbox);

    }
    public MainView() {

    }

    public void create_menu_bar(){
        // ----------------------------Enfants de root_second_menu_bar ---------------------------------------
        Menu file_menu = new Menu("File"); // Le menu "File".
        MenuItem openItem = new MenuItem("Open..."); // L'élément "Open".
        MenuItem menuSave = new MenuItem("Save As...");
        // Dans la barre de Menu root_second_menu_bar.
        root_second_menu_bar.getMenus().add(file_menu);
        file_menu.getItems().addAll(openItem,menuSave);
        // ----------------------------Enfants de root_second_menu_bar ---------------------------------------
    }
    public void Create_first_colonne(){
        VBox root_third_palette_action = new VBox();
        root_second_hbox.getChildren().add(root_third_palette_action);
        // ----------------------------Enfants de root_third_palette_action ---------------------------------------
        Label label_actions = new Label("Palette d'actions");
        ListView<String> paletteList_actions = new ListView<>();
        // Données bidon pour tester le visuel :
        paletteList_actions.getItems().addAll("Avancer de", "Tourner à gauche de", "Lever stylo", "Abaisser stylo");
        Button btnAjouter = new Button("Ajouter au programme");
        root_third_palette_action.getChildren().addAll(label_actions,paletteList_actions,btnAjouter);
        // ----------------------------Enfants de root_third_palette_action ---------------------------------------
    }

    public void Create_second_colonne(){
        VBox root_third_programme = new VBox();
        root_second_hbox.getChildren().add(root_third_programme);
        // ----------------------------Enfants de root_third_palette_action ---------------------------------------
        Label label_programme = new Label("Programme");
        ListView<String> listView_palette_programme = new ListView<>();
        // Données bidon pour tester le visuel :
        listView_palette_programme.getItems().addAll("Lever le Stylo", "Avancer de 30° ", "Tourner à gauche de 90 °");
        Button btn_monter = new Button("Monter"), btn_descendre = new Button("Descendre"), btn_dupliquer = new Button("Dupliquer"),
        btn_supprimer = new Button("Supprimer"), btn_vider_tout = new Button("Vider tout");

        TitledPane title_pane_detail_action = new TitledPane();
        title_pane_detail_action.setText("Detail de l'action");
        // --------------------Enfants de title_pane_detail_action--------------------
        HBox hbox_title_pane_detail_action = new HBox();
        // -------------Enfants de hbox_title_pane_detail_action-------------
        Label label_avancer_de = new Label("Avancer de ");
        TextField text_avancer_de_number = new TextField();
        Label label_pixels = new Label("Pixels");
        hbox_title_pane_detail_action.getChildren().addAll(label_avancer_de,text_avancer_de_number,label_pixels);
        // -------------Enfants de hbox_title_pane_detail_action-------------
        title_pane_detail_action.setContent(hbox_title_pane_detail_action);
        // --------------------Enfants de title_pane_detail_action--------------------
        root_third_palette_action.getChildren().addAll(
                label_programme,listView_palette_programme,
                btn_monter,btn_descendre,btn_dupliquer,btn_supprimer,btn_vider_tout,
                title_pane_detail_action
            );
        // ----------------------------Enfants de root_third_palette_action ---------------------------------------
    }

    public void Create_third_colonne(){
        VBox root_third_programme = new VBox();
        root_second_hbox.getChildren().add(root_third_programme);
        // ----------------------------Enfants de root_third_palette_action ---------------------------------------
        Label label_programme = new Label("Programme");
        ListView<String> listView_palette_programme = new ListView<>();
        // Données bidon pour tester le visuel :
        listView_palette_programme.getItems().addAll("Lever le Stylo", "Avancer de 30° ", "Tourner à gauche de 90 °");
        Button btn_monter = new Button("Monter"), btn_descendre = new Button("Descendre"), btn_dupliquer = new Button("Dupliquer"),
                btn_supprimer = new Button("Supprimer"), btn_vider_tout = new Button("Vider tout");

        TitledPane title_pane_detail_action = new TitledPane();
        title_pane_detail_action.setText("Detail de l'action");
        // --------------------Enfants de title_pane_detail_action--------------------
        HBox hbox_title_pane_detail_action = new HBox();
        // -------------Enfants de hbox_title_pane_detail_action-------------
        Label label_avancer_de = new Label("Avancer de ");
        TextField text_avancer_de_number = new TextField();
        Label label_pixels = new Label("Pixels");
        hbox_title_pane_detail_action.getChildren().addAll(label_avancer_de,text_avancer_de_number,label_pixels);
        // -------------Enfants de hbox_title_pane_detail_action-------------
        title_pane_detail_action.setContent(hbox_title_pane_detail_action);
        // --------------------Enfants de title_pane_detail_action--------------------
        root_third_palette_action.getChildren().addAll(
                label_programme,listView_palette_programme,
                btn_monter,btn_descendre,btn_dupliquer,btn_supprimer,btn_vider_tout,
                title_pane_detail_action
        );
        // ----------------------------Enfants de root_third_palette_action ---------------------------------------
    }
}