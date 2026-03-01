package _05_listview_binding.view;

import _05_listview_binding.model.Item;
import _05_listview_binding.model.ItemList;
import javafx.beans.binding.Bindings;
import javafx.beans.binding.IntegerBinding;
import javafx.geometry.Insets;
import javafx.scene.control.Button;
import javafx.scene.control.ListView;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;

public class MainView extends VBox {

    private static int id = 1;
    private final ItemList itemList;

    // Représente la liste des éléments du modèle (sous forme éditable)
    private final ItemListView itemListView;


    // Représente la liste des éléments du modèle (non éditable, les éléments sont affichés via la méthode toString de Item)
    private final ListView<Item> itemsRawList;
    private final Button addItem = new Button("add"), removeItem = new Button("remove");


    /* Représente la size de la liste d'items. Afin de ne perdre les "WeakReferences", on stocke
       la référence du binding dans cet attribut.
     */
    private IntegerBinding sizeBinding;

    public MainView(ItemList itemList) {
        this.itemList = itemList;
        this.itemListView = new ItemListView(itemList);

        // On associe l'ObservableList du modèle (via le constructeur)
        this.itemsRawList = new ListView<>(itemList.getItems());

        layoutControls();

        addListeners();


        /*
        Si on décommente la ligne "configBindingsWithoutStrongReference()" et commente la ligne "configBindingsWithStrongReference()",
        le programme plante au bout de quelques manipulations.
         */
//        configBindingsWithoutStrongReference();
        configBindingsWithStrongReference();
    }

    private void layoutControls() {
        HBox hbLists = new HBox(itemListView, itemsRawList),
             hbButtons = new HBox(addItem, removeItem);
        hbLists.setPadding(new Insets(5));
        hbLists.setSpacing(10);
        hbButtons.setPadding(new Insets(5));
        hbButtons.setSpacing(10);
        this.getChildren().addAll(hbLists, hbButtons);
    }

    private void addListeners() {
        addItem.setOnAction(e -> itemList.addItem("hello"+id++));
        removeItem.setOnAction(e -> {
            int index = this.itemListView.getSelectedIndex();
            if (index != -1)
                itemList.remove(index);
        });
    }


    /* La méthode size de Bindings crée un IntegerBinding lié à la taille de l'ObservableList passée en paramètres.
       On ajoute un Listener sur cet IntegerBinding.

       On garde la référence de ce binding dans un attribut => on en fait une référence "forte"
     */
    private void configBindingsWithStrongReference() {

        (sizeBinding = Bindings.size(itemList.getItems())).addListener((obs, o, n) -> {
            System.out.println("Binding size (with strong reference) : " + n);
            this.addItem.setDisable(n.intValue() >= 5);
            this.removeItem.setDisable(n.intValue() == 0);
        });
        removeItem.setDisable(sizeBinding.get() == 0);
    }


    /*
    Calcul du binding identique à la méthode précédente sauf qu'on ne garde pas de référence à celui-ci.

    Au bout de quelques actions (add / remove), le binding est perdu car comme JavaFX garde uniquement
    des références faibles à l'observabl et/ou aux listeners, celles-ci sont "garbage collectées"
    (=> le disabling des boutons n'est plus correct).
     */
    private void configBindingsWithoutStrongReference() {

        Bindings.size(itemList.getItems()).addListener((obs, o, n) -> {
            System.out.println("Binding size (without strong reference) : " + n);
            this.addItem.setDisable(n.intValue() >= 5);
            this.removeItem.setDisable(n.intValue() == 0);
        });
        removeItem.setDisable(itemList.getItems().isEmpty());
    }

}
