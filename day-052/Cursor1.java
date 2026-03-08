package scratch.model;

import javafx.beans.property.*;

public class Cursor { // C'est notre tortue.
    /*
    DoubleProperty et BooleanProperty sont des types généraux (le "quoi").
    SimpleDoubleProperty et SimpleBooleanProperty sont les mécaniques précises (le "comment").
    En renvoyant le type général, On se laisse le droit de changer le "comment"
    plus tard à l'intérieur de notre classe,
    sans risquer de casser le reste de notre programme qui utilise cette méthode.
     */
    private Point position;
    private DoubleProperty heading; // Représente la direction en degrés.
    private BooleanProperty penDown; // Dit si le stylo est baissé ou levé

    public Cursor(Point position, double heading, boolean penDown){
        this.position = position;
        this.heading = new SimpleDoubleProperty(heading);
        this.penDown = new SimpleBooleanProperty(penDown);
    }
    public  Cursor(){
        this(new Point(0,0),0.0,true);
    }

    public Point getPosition(){return this.position;}
    public DoubleProperty getHeading(){return this.heading;}
    public BooleanProperty getPenDown(){return this.penDown;}

    public void setPosition(double x, double y){
        this.position.setX(x);
        this.position.setY(y);
    }
    public void setHeading(double heading){
        this.heading.set(heading);
    }
    public void setPenDown(boolean penDown){
        this.penDown.set(penDown);
    }
}
