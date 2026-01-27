package Question2.knife;

import java.util.ArrayList;
import java.util.Comparator;
import java.util.List;

public class PizzeriaTypeB extends PizzariaType{
    public PizzeriaTypeB(String name, int... tableCapacities) {
        super(name,tableCapacities);
    }

    // Trouve le premier groupe de tables possible en commençant par les plus petites
    @Override
    Reservation reserve(Client client, int numberOfPeople) {
        List<Table> availableTables = this.getTables().stream()
                .filter(t -> !t.isReserved())
                .sorted(Comparator.comparingInt(Table::getCapacity))
                .toList();
        return groupTables(client, numberOfPeople, availableTables);
    }

    // Crée une réservation en regroupant les tables disponibles dans l'ordre de la liste fournie
    private Reservation groupTables(Client client, int numberOfPeople, List<Table> availableTables) {
        List<Table> reservedTables = new ArrayList<>();
        int totalCapacity = 0;
        for (Table table : availableTables) {
            reservedTables.add(table);
            totalCapacity += table.getCapacity();
            if (totalCapacity >= numberOfPeople) {
                reservedTables.forEach(t -> t.setReserved(true));
                return new Reservation(client, reservedTables, numberOfPeople, this);
            }
        }
        return null;
    }

    @Override
    public String toString() {
        return this.getName() + " (Type B)";
    }
}
