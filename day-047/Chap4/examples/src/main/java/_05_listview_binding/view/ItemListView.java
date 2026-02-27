package _05_listview_binding.view;

import _05_listview_binding.model.Item;
import _05_listview_binding.model.ItemList;
import javafx.scene.control.ListCell;
import javafx.scene.control.ListView;
import javafx.util.Callback;


/*
Classe qui étend ListView permettant l'édition des éléments de la liste sur place
 */
public class ItemListView extends ListView<Item> {

    /*
    Représente l'index sélectionné du ListView. Initialisé à -1 (qui représente le fait qu'aucun élément n'est sélectionné)
     */
    private int selectedIndex = -1;

    public int getSelectedIndex() {
        return selectedIndex;
    }

    public ItemListView(ItemList itemList) {

        // On associe l'ObservableList du modèle au ListView (via la méthode setItems)
        this.setItems(itemList.getItems());

        /*
        Si on décommente la ligne suivante, on rend les éléments du listView non sélectionnables
         */
//        setFocusModel(null);


        /*
        On ajoute un listener sur la propriété qui garde l'index sélectionné du ListView
         */
        this.getSelectionModel().selectedIndexProperty().addListener((src, oldVal, newVal) -> selectedIndex = newVal.intValue());

        /*
        Méthode pour construire les éléments de la liste. Ici, pour chaque Item, on construit un objet de vue ItemView
        Cette méthode est appelée à chaque notification de la ListView.
         */
        this.setCellFactory(new Callback<>() {
            @Override
            public ListCell<Item> call(ListView<Item> param) {
                return new ListCell<>() {
                    @Override
                    protected void updateItem(Item item, boolean empty) {
                        super.updateItem(item, empty); // Appel nécessaire pour que le comportement soit correct
                        if (!empty && item != null) {
                            setGraphic(new ItemView(item)); // "Dessine" la cellule (ici, crée un ItemView qui est une HBox)
                        } else { // Nécessaire pour que l'affichage de la liste soit correct
                            setText(null);
                            setGraphic(null);
                        }
                    }
                };
            }
        });


    }
}
