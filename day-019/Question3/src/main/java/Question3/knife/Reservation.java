package Question3.knife;

import java.util.Iterator;
import java.util.List;

public class Reservation {
    private static int sequence = 1;
    private final int id = sequence++;
    private final Client client;
    private final int numberOfPeople;
    private final PizzeriaTypeA pizzeria;
    private final List<Table> tables;


    Reservation(Client client, List<Table> tables, int numberOfPeople, PizzeriaTypeA pizzeria) {
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

    PizzeriaTypeA getPizzeria() {
        return pizzeria;
    }

    List<Table> getTables() {
        return tables;
    }

    boolean cancel() {
        return pizzeria.cancel(this);
    }

    public int getId() {
        return id;
    }

    public int getNumberOfPeople() {
        return numberOfPeople;
    }
}
