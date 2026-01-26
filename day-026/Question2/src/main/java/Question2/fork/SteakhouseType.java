package Question2.fork;

import java.util.ArrayList;
import java.util.List;

public abstract class SteakhouseType {
    private final String name;
    private final List<Table> tables = new ArrayList<>();

    public SteakhouseType(String name, int... tableCapacities) {
        this.name = name;
        for (int i = 0; i < tableCapacities.length; i++) {
            tables.add(new Table(i + 1, tableCapacities[i], this));
        }
    }
    public List<Table> getTables() {
        return tables;
    }

    public String getName() {
        return name;
    }

    abstract Booking book(User user, int numberOfPeople);

    boolean cancel(Booking booking) {
        if (!booking.getSteakhouse().equals(this)) {
            return false;
        }
        for (Table table : booking.getTables()) {
            table.setBooked(false);
        }
        return true;
    }

    public abstract String toString();
}
