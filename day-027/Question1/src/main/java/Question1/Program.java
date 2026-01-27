package Question1;

import Question1.knife.Client;
import Question1.knife.Knife;
import Question1.knife.PizzeriaTypeA;
import Question1.knife.Reservation;

public class Program {
    public static void main(String[] args) {
        Client quentin = new Client("Quentin");
        Client bruno = new Client("Bruno");
        PizzeriaTypeA pizzeria1 = new PizzeriaTypeA("Giovanni", 4, 2, 6, 4, 2);
        PizzeriaTypeA pizzeria2 = new PizzeriaTypeA("Casa Mia", 2, 1, 4, 3, 2);

        Knife knife = new Knife();

        Reservation reservation1 = knife.reserve(pizzeria1, quentin, 5);
        System.out.println(reservation1); // ok

        Reservation reservation2 = knife.reserve(pizzeria2, quentin, 2);
        System.out.println(reservation2); // ok

        Reservation reservation3 = knife.reserve(pizzeria2, quentin, 2);
        System.out.println(reservation3); // null car a déjà deux réservations

        Reservation reservation4 = knife.reserve(pizzeria1, quentin, 6);
        System.out.println(reservation4); // null car a déjà deux réservations

        Reservation reservation5 = knife.reserve(pizzeria2, bruno, 3);
        System.out.println(reservation5); //ok

        Reservation reservation6 = knife.reserve(pizzeria2, bruno, 4);
        System.out.println(reservation6); //ok

        Reservation reservation7 = knife.reserve(pizzeria1, bruno, 1);
        System.out.println(reservation7); // null car a déjà deux réservations

        knife.cancel(reservation6);

        Reservation reservation8 = knife.reserve(pizzeria1, bruno, 3);
        System.out.println(reservation8); // ok

        System.out.println(knife);
    }
}
