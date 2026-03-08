package scratch.model;

import javafx.beans.property.IntegerProperty;
import javafx.beans.property.SimpleIntegerProperty;

public class Distance {
    private SimpleIntegerProperty value =  new SimpleIntegerProperty();
    private final String messageError;
    public Distance(int initialValue){
        this.value.setValue(initialValue);
        this.messageError = "La distance doit être compris entre 1 et 100 comprise !";
    }

    public IntegerProperty valueProperty(){
        return this.value;
    }

    public int getValue(){return this.value.get();}
    public void setValue(int value){this.value.setValue(value);}
    public String getMessageError(){return this.messageError;}

    public boolean isValid(){
        return this.value.get() >= 1 && this.value.get() <= 100;
    }
}
