package Question1.fork;

import java.util.Iterator;
import java.util.List;

public class Booking {
    private static int sequence = 1;
    private final int id = sequence++;
    private final User user;
    private final int numberOfPeople;
    private final SteakhouseTypeA steakhouse;
    private final List<Table> tables;


    Booking(User user, List<Table> tables, int numberOfPeople, SteakhouseTypeA steakhouse) {
        this.user = user;
        this.tables = tables;
        this.numberOfPeople = numberOfPeople;
        this.steakhouse = steakhouse;
    }

    @Override
    public String toString() {
        String res = "Booking " + id + " for " + user + " (for " + numberOfPeople + " people) in " + steakhouse + ":\n";
        Iterator<Table> iterator = tables.iterator();
        while (iterator.hasNext()) {
            res += "  - " + iterator.next();
            if (iterator.hasNext()) {
                res += "\n";
            }
        }
        return res;
    }

    User getUser() {
        return user;
    }

    SteakhouseTypeA getSteakhouse() {
        return steakhouse;
    }

    List<Table> getTables() {
        return tables;
    }

    boolean cancel() {
        return steakhouse.cancel(this);
    }
}
