package Question2;

import Question2.knife.*;
import org.junit.Test;

import static org.junit.Assert.*;

public class TestQuestion2 {
    @Test
    public void test() {
        Client quentin = new Client("Quentin");
        Client bruno = new Client("Bruno");
        PizzeriaTypeA pizzeria1 = new PizzeriaTypeA("Giovanni", 4, 2, 6, 4, 2);
        PizzeriaTypeB pizzeria2 = new PizzeriaTypeB("La Pizza è Bella", 4, 3, 2, 1, 2);

        Knife knife = new Knife();

        Reservation reservation1 = knife.reserve(pizzeria1, quentin, 5);
        System.out.println(reservation1); // ok
        assertNotNull(reservation1);

        Reservation reservation2 = knife.reserve(pizzeria2, bruno, 6);
        System.out.println(reservation2); // ok
        assertNotNull(reservation2);

        Reservation reservation3 = knife.reserve(pizzeria1, quentin, 5);
        System.out.println(reservation3); // null
        assertNull(reservation3);

        knife.cancel(reservation1);

        Reservation reservation4 = knife.reserve(pizzeria1, quentin, 5);
        System.out.println(reservation4); // ok
        assertNotNull(reservation4);

        Reservation reservation5 = knife.reserve(pizzeria2, bruno, 4);
        System.out.println(reservation5); // ok
        assertNotNull(reservation5);

        Reservation reservation6 = knife.reserve(pizzeria2, quentin, 1);
        System.out.println(reservation6); // null
        assertNull(reservation6);

        System.out.println(knife);
        assertEquals("===== KNIFE STATUS =====\n" +
                "Reservation 2 for Bruno (for 6 people) in La Pizza è Bella (Type B):\n" +
                "  - Table 4 (Capacity: 1)\n" +
                "  - Table 3 (Capacity: 2)\n" +
                "  - Table 5 (Capacity: 2)\n" +
                "  - Table 2 (Capacity: 3)\n" +
                "Reservation 3 for Quentin (for 5 people) in Giovanni (Type A):\n" +
                "  - Table 3 (Capacity: 6)\n" +
                "Reservation 4 for Bruno (for 4 people) in La Pizza è Bella (Type B):\n" +
                "  - Table 1 (Capacity: 4)", knife.toString());
    }
}
