package labo8.ex1;

public enum StreamingQuality {
    LOW(10.99),
    HIGH(15.99);
    private final double price;
    StreamingQuality(double price) {
        this.price = price;
    }
    public double getPrice() {
        return price;
    }
}
