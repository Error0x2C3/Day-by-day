package scratch.model;

import javafx.beans.property.DoubleProperty;
import javafx.beans.property.SimpleDoubleProperty;

public class Point {
    /*
    DoubleProperty est le type général (le "quoi").
    SimpleDoubleProperty est la mécanique précise (le "comment").
    En renvoyant le type général, On se laisse le droit de changer le "comment"
    plus tard à l'intérieur de notre classe,
    sans risquer de casser le reste de notre programme qui utilise cette méthode.
     */
    private DoubleProperty x = new SimpleDoubleProperty();
    private DoubleProperty y = new SimpleDoubleProperty();

    public Point(double x, double y){
        this.x.set(x);
        this.y.set(y);
    }

    public DoubleProperty xProperty(){
        return this.x;
    }
    public DoubleProperty yProperty(){
        return this.y;
    }

    public double getX(){return this.x.get();}
    public double getY(){return this.y.get();}

    public void setX(double x){this.x.set(x);}
    public void setY(double y){this.y.set(y);}
}
