package pratique;

import java.time.LocalDate;

public class Television extends Product implements Exchangeable{

    private final double size;

    public Television(String name, double price, LocalDate saleDate, double size) {
        super(name,price,saleDate);
        this.size = size;
    }

    public double getSize() {
        return size;
    }

    @Override
    public LocalDate exchangeable(){
        /*
        Plus propre de mettre this.getSaleDate().plusDays(7);
        car si j'override ma classe getSaleDate(), elle appellera
        la fonction mère.
         */
        return this.getSaleDate().plusMonths(3);
    }

    @Override
    public String toString() {
        return super.toString()+" taille : "+this.getSize();
    }


}
