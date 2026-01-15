package pratique;

import java.time.LocalDate;

public class Meat  extends Product implements Eatable {

    public Meat(String name, double price, LocalDate saleDate) {
        super(name,price,saleDate);
    }


    @Override
    public LocalDate datePeremption(){
        /*
        Plus propre de mettre this.getSaleDate().plusDays(7);
        car si j'override ma classe getSaleDate(), elle appellera
        la fonction mère.
         */
        return this.getSaleDate().plusDays(3);
    }

    @Override
    public String toString() {
        return super.toString() + " - date de péremption : " +this.datePeremption();
    }

    @Override
    public int compareTo(Eatable o) {
        return this.datePeremption().compareTo(o.datePeremption());
    }
}
