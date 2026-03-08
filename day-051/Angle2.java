package scratch.model;

import javafx.beans.property.IntegerProperty;
import javafx.beans.property.SimpleIntegerProperty;

public class Angle {
    private SimpleIntegerProperty value = new SimpleIntegerProperty();
    private final String messageError;
    public Angle(int initialAngle){
        this.value.set(initialAngle);
        this.messageError = "L'angle doit être compris entre 1 et 180 degré(s) !";
    }
    public IntegerProperty valueProperty(){
        return this.value;
    }

    public int getValue(){return this.value.get();}
    public void setValue(int angle){this.value.setValue(angle);}
    public String getMessageError(){return this.messageError;}

    public boolean isValid(){
        return this.value.get() >= 1 && this.value.get() <= 180;
    }

}
