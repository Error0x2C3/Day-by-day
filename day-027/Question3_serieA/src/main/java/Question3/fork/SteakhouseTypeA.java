package Question3.fork;

import java.util.ArrayList;
import java.util.Comparator;
import java.util.List;

public class SteakhouseTypeA {
    private final String name;
    private final List<Table> tables = new ArrayList<>();

    public SteakhouseTypeA(String name, int... tableCapacities) {
        this.name = name;
        for (int i = 0; i < tableCapacities.length; i++) {
            tables.add(new Table(i + 1, tableCapacities[i], this));
        }
    }

    Booking book(User user, int numberOfPeople) {
        // Trouve la table non réservée avec la plus petite capacité suffisante
        Table table = tables.stream()
                .filter(t -> !t.isBooked() && t.getCapacity() >= numberOfPeople)
                .min(Comparator.comparingInt(Table::getCapacity))
                .orElse(null);
        if (table != null) {
            table.setBooked(true);
            return new Booking(user, List.of(table), numberOfPeople, this);
        }
        return null;
    }

    boolean cancel(Booking booking) {
        if (!booking.getSteakhouse().equals(this)) {
            return false;
        }
        for (Table table : booking.getTables()) {
            table.setBooked(false);
        }
        return true;
    }

    public String toString() {
        return name + " (Type A)";
    }
}
