package Question3.knife;

import java.util.ArrayList;
import java.util.Comparator;
import java.util.List;

public class PizzeriaTypeA {
    private final String name;
    private final List<Table> tables = new ArrayList<>();

    public PizzeriaTypeA(String name, int... tableCapacities) {
        this.name = name;
        for (int i = 0; i < tableCapacities.length; i++) {
            tables.add(new Table(i + 1, tableCapacities[i], this));
        }
    }

    Reservation reserve(Client client, int numberOfPeople) {
        // Trouve la table non réservée avec la plus petite capacité suffisante
        Table table = tables.stream()
                .filter(t -> !t.isReserved() && t.getCapacity() >= numberOfPeople)
                .min(Comparator.comparingInt(Table::getCapacity))
                .orElse(null);
        if (table != null) {
            table.setReserved(true);
            return new Reservation(client, List.of(table), numberOfPeople, this);
        }
        return null;
    }

    boolean cancel(Reservation reservation) {
        if (!reservation.getPizzeria().equals(this)) {
            return false;
        }
        for (Table table : reservation.getTables()) {
            table.setReserved(false);
        }
        return true;
    }

    public String toString() {
        return name + " (Type A)";
    }
}
