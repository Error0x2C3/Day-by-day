package _05_listview_binding;

import _05_listview_binding.model.ItemList;
import _05_listview_binding.view.MainView;
import javafx.application.Application;
import javafx.scene.Scene;
import javafx.stage.Stage;

public class Program extends Application  {
    public static void main(String[] args) {
        launch(args);
    }

    @Override
    public void start(Stage stage) throws Exception {
        ItemList itemList = new ItemList();
        Scene scene = new Scene(new MainView(itemList), 500, 300);
        stage.setScene(scene);
        stage.show();
    }
}
