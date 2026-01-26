package Question2.fork;

import java.util.ArrayList;
import java.util.Comparator;
import java.util.List;

public class SteakhouseTypeA extends SteakhouseType {

    public SteakhouseTypeA(String name, int... tableCapacities) {
        super(name,tableCapacities);
    }

    @Override
    Booking book(User user, int numberOfPeople) {
        // Trouve la table non réservée avec la plus petite capacité suffisante
        Table table = this.getTables().stream()
                .filter(t -> !t.isBooked() && t.getCapacity() >= numberOfPeople)
                .min(Comparator.comparingInt(Table::getCapacity))
                .orElse(null);
        if (table != null) {
            table.setBooked(true);
            return new Booking(user, List.of(table), numberOfPeople, this);
        }
        return null;
    }


    @Override
    public String toString() {
        return this.getName() + " (Type A)";
    }
}
