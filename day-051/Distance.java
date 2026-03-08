package scratch.model;

import javafx.beans.property.IntegerProperty;
import javafx.beans.property.SimpleIntegerProperty;

public class Distance {
    private SimpleIntegerProperty value =  new SimpleIntegerProperty();

    public Distance(int initialValue){
        this.value.setValue(initialValue);
    }

    public IntegerProperty valueProperty(){
        return this.value;
    }

    public int getValue(){return this.value.get();}
    public void setValue(int value){this.value.setValue(value);}

    public boolean isValid(){
        return this.value.get() >= 1 && this.value.get() <= 100;
    }
}
