package Question1.fork;

class Table {
    private final int id;
    private final int capacity;
    private boolean isBooked = false;
    private final SteakhouseTypeA steakhouse;

    Table(int id, int capacity, SteakhouseTypeA steakhouse) {
        this.id = id;
        this.capacity = capacity;
        this.steakhouse = steakhouse;
    }

    SteakhouseTypeA getSteakhouse() {
        return steakhouse;
    }

    int getCapacity() {
        return capacity;
    }

    boolean isBooked() {
        return isBooked;
    }

    void setBooked(boolean booked) {
        isBooked = booked;
    }

    @Override
    public String toString() {
        return "Table " + id + " (Capacity: " + capacity + ")";
    }
}
