package Question2.knife;

import java.util.Iterator;
import java.util.List;

public class Reservation {
    private static int sequence = 1;
    private final int id = sequence++;
    private final Client client;
    private final int numberOfPeople;
    private final PizzariaType pizzeria;
    private final List<Table> tables;

    Reservation(Client client, List<Table> tables, int numberOfPeople, PizzariaType pizzeria) {
//        if (pizzeria instanceof PizzeriaTypeA || pizzeria instanceof PizzeriaTypeB) {
//            this.client = client;
//            this.tables = tables;
//            this.numberOfPeople = numberOfPeople;
//            this.pizzeria = pizzeria;
//        } else {
//            throw new IllegalArgumentException("Invalid pizzeria type");
//        }
        this.client = client;
        this.tables = tables;
        this.numberOfPeople = numberOfPeople;
        this.pizzeria = pizzeria;
    }

    @Override
    public String toString() {
        String res = "Reservation " + id + " for " + client + " (for " + numberOfPeople + " people) in " + pizzeria + ":\n";
        Iterator<Table> iterator = tables.iterator();
        while (iterator.hasNext()) {
            res += "  - " + iterator.next();
            if (iterator.hasNext()) {
                res += "\n";
            }
        }
        return res;
    }

    Client getClient() {
        return client;
    }

    PizzariaType getPizzeria() {
        return pizzeria;
    }

    List<Table> getTables() {
        return tables;
    }

    boolean cancel() {
//        if (pizzeria instanceof PizzeriaTypeA) {
//            return ((PizzeriaTypeA) pizzeria).cancel(this);
//        }
//        if (pizzeria instanceof PizzeriaTypeB) {
//            return ((PizzeriaTypeB) pizzeria).cancel(this);
//        }
//        return false;
        return pizzeria.cancel(this);
    }
}
