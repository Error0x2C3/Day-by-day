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
    @Override
    public void execute(ExecutionContext ctx) {
        //TODO
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
        return "Avancer de";
    }
}
