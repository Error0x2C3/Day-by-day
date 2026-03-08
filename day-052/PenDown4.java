package scratch.model;

public class PenDown extends Action{
    public PenDown(){}
    @Override
    public void execute(ExecutionContext ctx) {
        ctx.getCursor().setPenDown(true);
    }

    @Override
    public ValidationResult validate() {
        // Toujours vrai car aucune validation à faire.
        return  new ValidationResult();
    }

    @Override
    public String defaultLabel() {
        return "Abaisser le stylo";
    }
}
