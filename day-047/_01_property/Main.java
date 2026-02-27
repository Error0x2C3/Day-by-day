package _01_property;

import javafx.beans.property.IntegerProperty;
import javafx.beans.property.ReadOnlyIntegerProperty;
import javafx.beans.value.ChangeListener;

public class Main {
    public static void main(String[] args) {
        //Création du modèle
        Model model = new Model();

        // Valeur de départ des propriétés
        System.out.println("model.getAnyValue() : " + model.getAnyValue());
        System.out.println("model.getPositiveValue() : " + model.getPositiveValue());
        System.out.println("model.getWrongPositiveValue() : " + model.getWrongPositiveValue() + "\n");

        // On référence les 3 propriétés du modèle dans des objets locaux
        IntegerProperty intProperty1 = model.anyValueProperty();

        // La ligne suivante ne compile pas car positiveValueProperty() retourne une ReadOnlyIntegerProperty
        // IntegerProperty intProperty2 = model.positiveValueProperty();

        // Par contre, on peut assigner model.positiveValProperty() à une ReadOnlyIntegerProperty
        ReadOnlyIntegerProperty intProperty2 = model.positiveValueProperty();

        IntegerProperty intProperty3 = model.wrongPositiveValueProperty();

        // Création d'un ChangeListener (interface fonctionnelle => on donne la lambda pour la méthode changed).
        /*
        Si la propriété est un "mégaphone" qui crie quand sa valeur change,
        le ChangeListener est "l'oreille" qui écoute ce mégaphone.
        L'action qui devra être exécutée à chaque fois qu'une valeur changera.
        Dans l'ancien système Java, c'est l'équivalent de la méthode update().

        ChangeListener<Number> : C'est le type de l'objet.
        On dit qu'on crée un "écouteur de changement" qui observe des nombres (Number).
        (obs, oldValue, newValue) -> ... : C'est ce qu'on appelle une expression lambda.

        C'est juste un raccourci d'écriture pour créer une méthode à la volée.
        Quand la propriété changera de valeur,
        elle fournira automatiquement ces 3 informations à ton écouteur :
            obs : La propriété elle-même (qui a déclenché l'alerte).
            oldValue : La valeur qu'il y avait avant la modification.
            newValue : La nouvelle valeur après la modification.
         */
        ChangeListener<Number> changeListener = (obs, oldValue, newValue) ->{
            /*
            Quand la variable Property crie dans le mégaphone, c'est bien elle-même qu'elle passe dans obs,
            Idem qu'avec la méthode udpate des Observable/Observers.
             */
            if(obs instanceof IntegerProperty && obs == intProperty1){
                System.out.println("C'est bon.");
            }else{
                System.out.println("Modification de : " + obs + " - oldval : " + oldValue + ", newval : " + newValue);
            }
            System.out.println("Modification de : " + obs + " - oldval : " + oldValue + ", newval : " + newValue);

        };

        // On associe le listener aux 3 propriétés :
        // -------------------------------------------------------------
            /*
            Créer l'écouteur ne suffit pas,
            il faut l'attacher à la propriété que tu veux surveiller. C'est l'abonnement.
                Je prends la propriété (intProperty1).
                Je lui dis "Ajoute cet écouteur à ta liste de contacts" (.addListener(...)).
                Je lui passes l'écouteur que tu viens de créer (changeListener).
            Plus tard dans mon code, quand la ligne intProperty1.setValue(7); est exécutée,
            intProperty1 change sa valeur de 0 à 7.
            Comme elle a mon changeListener dans sa liste de contacts,
            elle l'appelle automatiquement en lui donnant les infos (intProperty1, 0, 7).
            C'est là que mon message s'affiche dans la console !
             */
            intProperty1.addListener(changeListener);
            intProperty2.addListener(changeListener);
            intProperty3.addListener(changeListener);

            // Test associer le listener aux trois propriétés de la classe directement :
            model.anyValueProperty().addListener(changeListener);
            model.wrongPositiveValueProperty().addListener(changeListener);
        // -------------------------------------------------------------

        // Modification de intProperty1
        System.out.println("intProperty1.setValue(7); : ");
        System.out.println("--------------------------------");
        intProperty1.setValue(7);
        System.out.println("--------------------------------");
        System.out.println();

        // Test modification de anyValue directement (sans passer par sa variable locale : intProperty1)
        System.out.println("setAnyValue(8); : ");
        System.out.println("--------------------------------");
        model.setAnyValue(8);
        System.out.println("--------------------------------");
        System.out.println();

        // La propriété anyValue du modèle est modifiée
        System.out.println("model.getAnyValue() : "+ model.getAnyValue() + "\n");

        System.out.println("Modification directe : model.setAnyValue(-5); : ");
        System.out.println("--------------------------------");
        model.setAnyValue(-5);
        System.out.println("--------------------------------");
        System.out.println();
        System.out.println("model.getAnyValue() : "+ model.getAnyValue() + "\n");

        // La ligne suivante ne compile pas car intProperty2 est une ReadOnlyIntegerProperty
        // intProperty2.setValue(7);

        // Il faut utiliser le setter de model
        System.out.println("model.setPositiveValue(7); : ");
        System.out.println("--------------------------------");
        model.setPositiveValue(7);
        System.out.println("--------------------------------");
        System.out.println();
        System.out.println("model.getPositiveValue() : "+ model.getPositiveValue() + "\n");

        try {
            // On essaie avec une valeur négative pour positiveValue
            System.out.println("model.setPositiveValue(-4);");
            model.setPositiveValue(-4);
        } catch (RuntimeException e) {
            System.out.println("Exception déclenchée par model : " + e);
            System.out.println("model.getPositiveValue() : "+ model.getPositiveValue() + "\n");
        }

        try {
            // On essaie avec une valeur négative pour wrongPositiveValue
            System.out.println("model.setWrongPositiveValue(-4);");
            model.setWrongPositiveValue(-4);
        } catch (RuntimeException e) {
            System.out.println("Exception déclenchée par model : " + e);
            System.out.println("model.getWrongPositiveValue() : "+ model.getWrongPositiveValue() + "\n");
        }

        System.out.println("intProperty3.setValue(-4);");
        System.out.println("--------------------------------");
        intProperty3.setValue(-4);
        System.out.println("--------------------------------");
        System.out.println();
        System.out.println("model.getWrongPositiveValue() : "+ model.getWrongPositiveValue() + "\n");

        System.out.println("ERREUR : les specs du modèle ne sont plus respectées ! (wrongPositiveValue contient une valeur négative)");

    }
}
