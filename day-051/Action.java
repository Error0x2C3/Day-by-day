package scratch.model;

public abstract class Action {

    public abstract void execute(ExecutionContext ctx);
    public abstract ValidationResult validate();
    public abstract String defaultLabel();
}
