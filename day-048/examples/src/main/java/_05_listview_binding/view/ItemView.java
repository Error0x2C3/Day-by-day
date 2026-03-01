package _05_listview_binding.view;

import _05_listview_binding.model.Item;
import javafx.geometry.Pos;
import javafx.scene.control.Label;
import javafx.scene.control.TextField;
import javafx.scene.input.KeyCode;
import javafx.scene.layout.HBox;

public class ItemView extends HBox {
    public ItemView(Item item) {
        Label label = new Label(""+item.getIdItem());
        TextField name = new TextField();
        name.setText(item.getValue());

        /*
        action quand on presse sur ENTER => item mis à jour
         */
        name.setOnAction(e -> {
            item.setValue(name.getText());
        });

        /*
        listener sur le focus : quand on le perd => item mis à jour
         */
        name.focusedProperty().addListener((obs, oldVal, newVal) -> {
            if (!newVal)
                item.setValue(name.getText());
        });

        this.getChildren().addAll(label, name);
        this.setSpacing(10);
        this.setAlignment(Pos.CENTER_LEFT);
    }
}
