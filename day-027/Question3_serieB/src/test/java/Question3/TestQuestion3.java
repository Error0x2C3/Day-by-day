package Question3;

import Question3.knife.Client;
import Question3.knife.Knife;
import Question3.knife.PizzeriaTypeA;
import Question3.knife.Reservation;
import org.junit.Test;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertNotNull;

public class TestQuestion3 {
    @Test
    public void test() {
        Client quentin = new Client("Quentin");
        Client bruno = new Client("Bruno");
        PizzeriaTypeA pizzeria1 = new PizzeriaTypeA("Giovanni", 4, 2, 6, 4, 2);
        PizzeriaTypeA pizzeria2 = new PizzeriaTypeA("Casa Mia", 2, 1, 4, 3, 2);

        Knife knife = new Knife();

        Reservation reservation1 = knife.reserve(pizzeria1, quentin, 3);
        System.out.println(reservation1); // ok
        assertNotNull(reservation1);

        Reservation reservation2 = knife.reserve(pizzeria2, bruno, 4);
        System.out.println(reservation2); // ok
        assertNotNull(reservation2);

        Reservation reservation3 = knife.reserve(pizzeria2, quentin, 1);
        System.out.println(reservation3); // ok
        assertNotNull(reservation3);

        Reservation reservation4 = knife.reserve(pizzeria1, bruno, 6);
        System.out.println(reservation4); // ok
        assertNotNull(reservation4);

        Reservation reservation5 = knife.reserve(pizzeria2, bruno, 2);
        System.out.println(reservation5); // ok
        assertNotNull(reservation5);

        Reservation reservation6 = knife.reserve(pizzeria1, quentin, 3);
        System.out.println(reservation6); // ok
        assertNotNull(reservation6);

        System.out.println(knife);
        assertEquals("===== KNIFE STATUS =====\n" +
                "Reservation 3 for Quentin (for 1 people) in Casa Mia (Type A):\n" +
                "  - Table 2 (Capacity: 1)\n" +
                "Reservation 5 for Bruno (for 2 people) in Casa Mia (Type A):\n" +
                "  - Table 1 (Capacity: 2)\n" +
                "Reservation 2 for Bruno (for 4 people) in Casa Mia (Type A):\n" +
                "  - Table 3 (Capacity: 4)\n" +
                "Reservation 1 for Quentin (for 3 people) in Giovanni (Type A):\n" +
                "  - Table 1 (Capacity: 4)\n" +
                "Reservation 6 for Quentin (for 3 people) in Giovanni (Type A):\n" +
                "  - Table 4 (Capacity: 4)\n" +
                "Reservation 4 for Bruno (for 6 people) in Giovanni (Type A):\n" +
                "  - Table 3 (Capacity: 6)", knife.toString());
    }
}
