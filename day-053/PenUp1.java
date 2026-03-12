package scratch.model;

public class PenUp extends Action{

    public PenUp(){}
    @Override
    public void execute(ExecutionContext ctx) {
        //TODO
    }

    @Override
    public ValidationResult validate() {
        // Toujours vrai car aucune validation à faire.
        return  new ValidationResult();
    }

    @Override
    public String defaultLabel() {
        return "Lever stylo";
    }
}
