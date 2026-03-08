package scratch.model;

public class MoveForward extends Action{
    private Distance distance;
    public MoveForward(int initialDistance){
        this.distance = new Distance(initialDistance);
    }

    // Par défaut, on avance de 30.
    public MoveForward(){
        this(30);
    }


    public Distance getDistance() {
        return this.distance;
    }
    public void setDistance(int distance){
        this.distance.setValue(distance);
    }

    public Point Forward_movement_of_the_turtle(double angleTurtle,int distanceMoveForward){
        // Angle étant exprimé en degré, on le transforme en radians et on ajoute 90° (Math.PI / 2).
        double radians = Math.toRadians(angleTurtle) + Math.PI / 2;
        // Par rapport à la position actuelle de la tortue, on calcule alors les différences en X et en Y de la manière suivante :
        double diffX = distanceMoveForward * Math.cos(radians);
        double diffY = distanceMoveForward * Math.sin(radians);
        /*
        Une fois ces différences (diffX et diffY) calculées,
        il suffira de les additionner aux coordonnées X et Y
        de la position actuelle de la tortue (l'objet Point dans ton diagramme)
        pour obtenir sa nouvelle position d'arrivée !
         */
        return new Point(diffX,diffY);
    }

    @Override
    public void execute(ExecutionContext ctx) {
        // Récupèration la position actuelle de ma tortue ainsi que son degré.
        double angleTurtle = ctx.getCursor().getHeading();
        Point  currentPosTurtle = new Point(ctx.getCursor().getPosition().getX(),ctx.getCursor().getPosition().getY());
        // On calcule la différence de position en X et Y entre la distance que je veux et la position actuelle de la tortue.
        Point pointDiff = this.Forward_movement_of_the_turtle(angleTurtle,this.getDistance().getValue());
        // Calcule de la nouvelle position de la tortue.
        double newX = currentPosTurtle.getX() + pointDiff.getX();
        double newY = currentPosTurtle.getY() + pointDiff.getY();
        Point newPosTurtle = new Point(newX,newY);
        // On met à jour la position de la tortue dans le contexte.
        ctx.getCursor().setPosition(newX,newY);
        // Et si le stylo est posé, on trace le segment sur le Canvas.
        if(ctx.getCursor().isPenDown()){
            Segment s = new Segment(currentPosTurtle,newPosTurtle);
            ctx.getCanvas().addSegment(s);
        }
    }

    @Override
    public ValidationResult validate() {
        ValidationResult result = new ValidationResult();
        if(!this.distance.isValid()){
            result.addMessage(this.distance.getMessageError());
            return result;
        }
        return result;
    }

    @Override
    public String defaultLabel() {
        return "Avancer de ";
    }
}
