package Question2.knife;

class Table {
    private final int id;
    private final int capacity;
    private boolean isReserved = false;
    private final PizzariaType pizzeria;

    Table(int id, int capacity, PizzariaType pizzeria) {
        this.id = id;
        this.capacity = capacity;
        this.pizzeria = pizzeria;
    }

    PizzariaType getPizzeria() {
        return pizzeria;
    }

    int getCapacity() {
        return capacity;
    }

    boolean isReserved() {
        return isReserved;
    }

    void setReserved(boolean reserved) {
        isReserved = reserved;
    }

    @Override
    public String toString() {
        return "Table " + id + " (Capacity: " + capacity + ")";
    }
}
