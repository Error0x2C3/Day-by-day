package Question2;


import Question2.knife.*;

public class Program {
    public static void main(String[] args) {
        Client quentin = new Client("Quentin");
        Client bruno = new Client("Bruno");
        PizzeriaTypeA pizzeria1 = new PizzeriaTypeA("Giovanni", 4, 2, 6, 4, 2);
        PizzeriaTypeB pizzeria2 = new PizzeriaTypeB("La Pizza è Bella", 4, 3, 2, 1, 2);

        Knife knife = new Knife();

        Reservation reservation1 = knife.reserve(pizzeria1, quentin, 5);
        System.out.println(reservation1); // ok

        Reservation reservation2 = knife.reserve(pizzeria2, bruno, 6);
        System.out.println(reservation2); // ok

        Reservation reservation3 = knife.reserve(pizzeria1, quentin, 5);
        System.out.println(reservation3); // null

        knife.cancel(reservation1);

        Reservation reservation4 = knife.reserve(pizzeria1, quentin, 5);
        System.out.println(reservation4); // ok

        Reservation reservation5 = knife.reserve(pizzeria2, bruno, 4);
        System.out.println(reservation5); // ok

        Reservation reservation6 = knife.reserve(pizzeria2, quentin, 1);
        System.out.println(reservation6); // null

        System.out.println(knife);
    }
}
