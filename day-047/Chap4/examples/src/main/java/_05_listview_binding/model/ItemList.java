package _05_listview_binding.model;

import javafx.beans.Observable;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;

public class ItemList {

    /*
    On passe un lambda à la méthode de fabrique de FXCollections. Il reçoit un élément (ici un Item)
    et retourne un tableau de propriétés observables de l'élément (ici on observe uniquement valueProperty).
    Tout changement de la propriété déclenchera une notification aux composants qui observent l'ObservableList,
    ce qui permettra une mise à jour visuelle lors d'un changement de la valeur de l'item.

    Si on commente cette ligne et décommente la suivante, on s'aperçoit que les changements d'un item ne sont plus répercutés.
     */
    private final ObservableList<Item> items = FXCollections.observableArrayList(item -> new Observable[] {item.valueProperty()});
//    private final ObservableList<Item> items = FXCollections.observableArrayList();

    public ItemList() {
        items.add(new Item("FirstItem"));
    }

    /* On utilise la méthode statique unmodifiableObservableList pour "emballer" items dans une liste non modifiable
       Remarque : comme le type de retour est ObservableList (il n'existe pas de ReadOnlyObservableList dans la librairie),
       si le client essaie de modifier la liste (ajout, suppression, ...), il y aura une Exception à l'exécution
     */
    public ObservableList<Item> getItems() {
        return FXCollections.unmodifiableObservableList(items);
    }

    public void addItem(String s) {
        items.add(new Item(s));
    }

    public void removeLast() {
        items.remove(items.size() - 1);
    }

    public void remove(int index) {
        items.remove(index);
    }
}
