package scratch.model;

import javafx.beans.binding.BooleanBinding;

import java.util.ArrayList;
import java.util.List;

public class ValidationResult { // C'est un messager.
    private Boolean valid;
    private List<String> messages;
    public ValidationResult(Boolean valid,List<String> messages){
        this.valid = valid;
        this.messages = messages != null ? messages : new ArrayList<>();
    }

    // Par défaut, c'est valid = true et il n'y a pas de messages.
    public ValidationResult() {
        this.valid = true;
        this.messages = new ArrayList<>();
    }
    public Boolean getValid(){return this.valid;}
    public void setValid(Boolean valid){this.valid = valid;}

    public List<String> getMessages() { return messages; }

    public void addMessage(String message) {
        this.messages.add(message);
        this.valid = false; // S'il y a un message d'erreur, ce n'est plus valide.
    }

    /*
    Exemples d'utilisation :
    Si je crée l'action => MoveForward move_forward = new MoveForward,
    J'aurais quelque chose comme :
        ValidationResult res = new ValidationResult();
        res.addMessage(move_forward.getMessageError());
     */
}
