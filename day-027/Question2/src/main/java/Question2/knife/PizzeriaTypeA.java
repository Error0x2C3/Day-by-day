package Question2.knife;

import java.util.ArrayList;
import java.util.Comparator;
import java.util.List;

public class PizzeriaTypeA extends PizzariaType{
    public PizzeriaTypeA(String name, int... tableCapacities) {
        super(name,tableCapacities);
    }

    @Override
    Reservation reserve(Client client, int numberOfPeople) {
        // Trouve la table non réservée avec la plus petite capacité suffisante
        Table table = this.getTables().stream()
                .filter(t -> !t.isReserved() && t.getCapacity() >= numberOfPeople)
                .min(Comparator.comparingInt(Table::getCapacity))
                .orElse(null);
        if (table != null) {
            table.setReserved(true);
            return new Reservation(client, List.of(table), numberOfPeople, this);
        }
        return null;
    }

    @Override
    public String toString() {
        return this.getName() + " (Type A)";
    }
}
