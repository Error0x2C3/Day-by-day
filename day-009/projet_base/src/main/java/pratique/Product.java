package pratique;

import java.time.LocalDate;

public class Product implements Comparable<Product>{
    private final String name;
    private final double price;
    private final LocalDate saleDate;

    public Product(String name, double price, LocalDate saleDate) {
        // vu que Porduct n'a pas de constructeur par défaut sans paramètre,
        // je dois ajouter super(..) dans chaque constructeur fille.
        this.name = name;
        this.price = price;
        this.saleDate = saleDate;
    }

    public double getPrice() {
        return price;
    }

    public String getName() {
        return name;
    }

    public LocalDate getSaleDate() {
        return saleDate;
    }

    @Override
    public String toString() {
        return getName() + " - prix : " + getPrice() + " - date mise en vente : " + getSaleDate();
    }

    public int compareTo(Product o) {
        return Double.compare(this.price, o.getPrice());
    }
}
