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
    private DoubleProperty heading = new SimpleDoubleProperty(); // Représente la direction en degrés.
    private BooleanProperty penDown = new SimpleBooleanProperty(); // Dit si le stylo est baissé ou levé

    public Cursor(Point position, double heading, boolean penDown){
        this.position = position;
        setHeading(heading); // On utilise le setter aussi déjà vérifier la règle métier.
        this.penDown.set(penDown);
    }
    public  Cursor(){
        this(new Point(0,0),0.0,true);
    }

    // --- Les getters properties ---
    public DoubleProperty headingProperty(){return this.heading;}
    public BooleanProperty penDownProperty(){return this.penDown;}
    // --- Les getters properties ---


    // --- Les getters classiques ---
    public Point getPosition() { return this.position; }
    public double getHeading() { return this.heading.get(); }
    public boolean isPenDown() { return this.penDown.get(); }
    // --- Les getters classiques ---

    public void setPosition(double x, double y){
        this.position.setX(x);
        this.position.setY(y);
    }
    public void setHeading(double heading){
        // Formule magique pour garder l'angle strictement entre 0 et 359 (gère aussi les nombres négatifs).
        double angleCorrige = (heading % 360 + 360) % 360;
        this.heading.set(angleCorrige);
    }
    public void setPenDown(boolean penDown){
        this.penDown.set(penDown);
    }
}
