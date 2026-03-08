package scratch.model;

import javafx.collections.FXCollections;
import javafx.collections.ObservableList;

public class Canvas { // Liste de droites tracée par la tortue.

    // L'ObservableList va prévenir l'interface graphique à chaque nouveau trait.
    private final ObservableList<Segment> segments;

    public Canvas() {
        // Le constructeur n'a rien de spécial à faire.
        this.segments = FXCollections.observableArrayList();
    }

    /*
    On utilise la méthode statique unmodifiableObservableList pour "emballer" segments dans une liste non modifiable.
    Remarque :
    Comme le type de retour est ObservableList (il n'existe pas de ReadOnlyObservableList dans la librairie),
    si le client essaie de modifier la liste (ajout, suppression, ...), il y aura une Exception à l'exécution.
   */
    public ObservableList<Segment> getSegments() {
        return FXCollections.unmodifiableObservableList( segments);
    }


    public void addSegment(Segment s){
        this.segments.add(s);
    }

    public void removeLast() {
        if(!segments.isEmpty()){
            segments.remove(segments.size() - 1);
        }
    }

    public void removeOne(int index) {
        segments.remove(index);
    }

    public void clear(){
        segments.clear();
    }

}