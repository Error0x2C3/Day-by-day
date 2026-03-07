package scratch.view;

import javafx.geometry.Insets;
import javafx.scene.control.*;
import javafx.scene.layout.*;

public class MainView extends VBox {
    private final VBox root_vbox = new VBox();
    // 1 ère ligne de root_vbox.
    private final MenuBar root_second_menu_bar = new MenuBar(); // la barre de menu.
    // 2 ème  ligne de root_vbox.
    private final HBox  root_second_hbox= new HBox();
    private final VBox root_third_palette_action = new VBox(), root_third_programme = new VBox(),root_third_scene = new VBox();


    // Assemblage de la structure des scènes JavaFx
    private void configComponents() {
        // La 1 ère et 2 ème ligne de root_vbox.
        root_vbox.getChildren().addAll(root_second_menu_bar,root_second_hbox);

    }
    public MainView() {

    }

    public void create_menu_bar(){
        // ----------------------------Enfants de root_second_menu_bar ---------------------------------------
        Menu file_menu = new Menu("File"); // Le menu "File".
        MenuItem openItem = new MenuItem("Open..."); // L'élément "Open".
        MenuItem menuSave = new MenuItem("Save As...");
        // ----------------------------Enfants de root_second_menu_bar ---------------------------------------
        // Dans la barre de Menu root_second_menu_bar.
        root_second_menu_bar.getMenus().add(file_menu);
        file_menu.getItems().addAll(openItem,menuSave);
    }
    public void Create_first_colonne(){
        VBox root_third_palette_action = new VBox();
        // ---------------------------- root_third_palette_action ---------------------------------------
        // ---------------------------- root_third_palette_action ---------------------------------------
        root_second_hbox.getChildren().add(root_third_palette_action);
    }
}