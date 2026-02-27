package _05_listview_binding.model;

import javafx.beans.property.ReadOnlyStringProperty;
import javafx.beans.property.SimpleStringProperty;
import javafx.beans.property.StringProperty;

public class Item {

    private static int id = 0;
    private final StringProperty value = new SimpleStringProperty();
    private final int idItem;

    public Item(String value) {
        this.value.setValue(value);
        this.idItem = ++id;
    }

    public StringProperty valueProperty() {
        return value;
    }

    public void setValue(String value) {
        this.value.set(value);
    }

    public String getValue() {
        return value.getValue();
    }

    public int getIdItem() {
        return idItem;
    }

    @Override
    public String toString() {
        return idItem + " " + value.getValue();
    }
}
