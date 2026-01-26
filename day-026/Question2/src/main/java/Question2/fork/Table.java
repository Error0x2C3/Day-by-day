package Question2.fork;

class Table {
    private final int id;
    private final int capacity;
    private boolean isBooked = false;
    private final SteakhouseType steakhouse;

    Table(int id, int capacity, SteakhouseType steakhouse) {
        this.id = id;
        this.capacity = capacity;
        this.steakhouse = steakhouse;
    }

    SteakhouseType getSteakhouse() {
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
