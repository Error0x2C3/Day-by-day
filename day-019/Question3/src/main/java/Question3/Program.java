package Question3;

import Question3.knife.Client;
import Question3.knife.Knife;
import Question3.knife.PizzeriaTypeA;
import Question3.knife.Reservation;

public class Program {
    public static void main(String[] args) {
        Client quentin = new Client("Quentin");
        Client bruno = new Client("Bruno");
        PizzeriaTypeA pizzeria1 = new PizzeriaTypeA("Giovanni", 4, 2, 6, 4, 2);
        PizzeriaTypeA pizzeria2 = new PizzeriaTypeA("Casa Mia", 2, 1, 4, 3, 2);

        Knife knife = new Knife();

        Reservation reservation1 = knife.reserve(pizzeria1, quentin, 3);
        System.out.println(reservation1); // ok

        Reservation reservation2 = knife.reserve(pizzeria2, bruno, 4);
        System.out.println(reservation2); // ok

        Reservation reservation3 = knife.reserve(pizzeria2, quentin, 1);
        System.out.println(reservation3); // ok

        Reservation reservation4 = knife.reserve(pizzeria1, bruno, 6);
        System.out.println(reservation4); // ok

        Reservation reservation5 = knife.reserve(pizzeria2, bruno, 2);
        System.out.println(reservation5); // ok

        Reservation reservation6 = knife.reserve(pizzeria1, quentin, 3);
        System.out.println(reservation6); // ok

        System.out.println(knife);
    }
}
