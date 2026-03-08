package scratch.model;

import javafx.beans.property.SimpleDoubleProperty;

public class Point {
    private SimpleDoubleProperty x = new SimpleDoubleProperty();
    private SimpleDoubleProperty y = new SimpleDoubleProperty();

    public Point(Double x, Double y){
        this.x.set(x);
        this.y.set(y);
    }

    public SimpleDoubleProperty xProperty(){
        return this.x;
    }
    public SimpleDoubleProperty yProperty(){
        return this.y;
    }

    public Double getX(){return this.x.get();}
    public Double getY(){return this.y.get();}

    public void setX(Double x){this.x.set(x);}
    public void setY(Double y){this.y.set(y);}
}
