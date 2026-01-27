package Question2.knife;

import java.util.ArrayList;
import java.util.List;

public abstract class PizzariaType {
    private final String name;
    private final List<Table> tables = new ArrayList<>();

    public PizzariaType(String name, int[] tableCapacities) {
        this.name = name;
        for (int i = 0; i < tableCapacities.length; i++) {
            tables.add(new Table(i + 1, tableCapacities[i], this));
        }
    }

//    public void PizzeriaType(String name, int... tableCapacities) {
//        this.name = name;
//        for (int i = 0; i < tableCapacities.length; i++) {
//            tables.add(new Table(i + 1, tableCapacities[i], this));
//        }
//    }

    public List<Table> getTables() {
        return tables;
    }
    public String getName() {
        return name;
    }
    abstract  Reservation reserve(Client client, int numberOfPeople);
    boolean cancel(Reservation reservation) {
        if (!reservation.getPizzeria().equals(this)) {
            return false;
        }
        for (Table table : reservation.getTables()) {
            table.setReserved(false);
        }
        return true;
    }

    
    abstract public String toString();
}
