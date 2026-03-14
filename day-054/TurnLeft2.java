package scratch.model;

public class TurnLeft extends Action{
    private Angle angle;
    public TurnLeft(int initialAngle){
        this.angle = new Angle(initialAngle);
    }

    // Par défaut, on bouge de 90°.
    public TurnLeft(){
        this(90);
    }
    public Angle getAngle(){
        return  this.angle;
    }
    public void setAngle(int angle){
        this.angle.setValue(angle);
    }

    @Override
    public void execute(ExecutionContext ctx) {
        // Soustraire la valeur de l'angle à la direction (heading) actuelle de la tortue.
        double angleTurtle = ctx.getCursor().getHeading();
        ctx.getCursor().setHeading(angleTurtle - this.getAngle().getValue());
    }

    @Override
    public ValidationResult validate() {
        ValidationResult result = new ValidationResult();
        if(!this.getAngle().isValid()){
            result.addMessage(this.getAngle().getMessageError());
            return result;
        }
        return result;
    }

    @Override
    public String defaultLabel() {
        return "Tourner à gauche de ";
    }
}
