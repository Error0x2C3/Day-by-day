package scratch.model;

public class TurnRight extends Action{
    private Angle angle;
    public TurnRight(int initialAngle){
        this.angle = new Angle(initialAngle);
    }

    // Par défaut, on bouge de 90°.
    public TurnRight(){
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
        // Additionner la valeur de l'angle à la direction actuelle.
        double angleTurtle = ctx.getCursor().getHeading();
        ctx.getCursor().setHeading( angleTurtle + this.getAngle().getValue());
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
        return "Tourner à droite de ";
    }
}
