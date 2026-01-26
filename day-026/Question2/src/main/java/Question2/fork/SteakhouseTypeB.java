package Question2.fork;

import java.util.ArrayList;
import java.util.Comparator;
import java.util.List;

public class SteakhouseTypeB extends SteakhouseType{

    public SteakhouseTypeB(String name, int... tableCapacities) {
        super(name,tableCapacities);
    }

    // Trouve le premier groupe de tables possible en commençant par les plus petites
    @Override
    Booking book(User user, int numberOfPeople) {
        List<Table> availableTables = this.getTables().stream()
                .filter(t -> !t.isBooked())
                .sorted(Comparator.comparingInt(Table::getCapacity))
                .toList();
        return groupTables(user, numberOfPeople, availableTables);
    }

    // Crée une réservation en regroupant les tables disponibles dans l'ordre de la liste fournie
    private Booking groupTables(User user, int numberOfPeople, List<Table> availableTables) {
        List<Table> bookedTables = new ArrayList<>();
        int totalCapacity = 0;
        for (Table table : availableTables) {
            bookedTables.add(table);
            totalCapacity += table.getCapacity();
            if (totalCapacity >= numberOfPeople) {
                bookedTables.forEach(t -> t.setBooked(true));
                return new Booking(user, bookedTables, numberOfPeople, this);
            }
        }
        return null;
    }

    @Override
    public String toString() {
        return this.getName() + " (Type B)";
    }
}
