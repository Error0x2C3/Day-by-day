package Question1;

import Question1.knife.Client;
import Question1.knife.Knife;
import Question1.knife.PizzeriaTypeA;
import Question1.knife.Reservation;
import org.junit.Test;

import static org.junit.Assert.*;

public class TestQuestion1 {
    @Test
    public void test() {
        Client quentin = new Client("Quentin");
        Client bruno = new Client("Bruno");
        PizzeriaTypeA pizzeria1 = new PizzeriaTypeA("Giovanni", 4, 2, 6, 4, 2);
        PizzeriaTypeA pizzeria2 = new PizzeriaTypeA("Casa Mia", 2, 1, 4, 3, 2);

        Knife knife = new Knife();

        Reservation reservation1 = knife.reserve(pizzeria1, quentin, 5);
        System.out.println(reservation1); // ok
        assertNotNull(reservation1);

        Reservation reservation2 = knife.reserve(pizzeria2, quentin, 2);
        System.out.println(reservation2); // ok
        assertNotNull(reservation2);

        Reservation reservation3 = knife.reserve(pizzeria2, quentin, 2);
        System.out.println(reservation3); // null car a déjà deux réservations
        assertNull(reservation3);

        Reservation reservation4 = knife.reserve(pizzeria1, quentin, 1);
        System.out.println(reservation4); // null car a déjà deux réservations
        assertNull(reservation4);

        Reservation reservation5 = knife.reserve(pizzeria2, bruno, 3);
        System.out.println(reservation5); //ok
        assertNotNull(reservation5);

        Reservation reservation6 = knife.reserve(pizzeria2, bruno, 4);
        System.out.println(reservation6); //ok
        assertNotNull(reservation6);

        Reservation reservation7 = knife.reserve(pizzeria1, bruno, 1);
        System.out.println(reservation7); // null car a déjà deux réservations
        assertNull(reservation7);

        knife.cancel(reservation6);

        Reservation reservation8 = knife.reserve(pizzeria1, bruno, 3);
        System.out.println(reservation8); // ok
        assertNotNull(reservation8);
        assertEquals(("===== KNIFE STATUS =====\n" +
                "Reservation 1 for Quentin (for 5 people) in Giovanni (Type A):\n" +
                "  - Table 3 (Capacity: 6)\n" +
                "Reservation 2 for Quentin (for 2 people) in Casa Mia (Type A):\n" +
                "  - Table 1 (Capacity: 2)\n" +
                "Reservation 3 for Bruno (for 3 people) in Casa Mia (Type A):\n" +
                "  - Table 4 (Capacity: 3)\n" +
                "Reservation 5 for Bruno (for 3 people) in Giovanni (Type A):\n" +
                "  - Table 1 (Capacity: 4)").length(), knife.toString().length());

        System.out.println(knife);
    }
}

