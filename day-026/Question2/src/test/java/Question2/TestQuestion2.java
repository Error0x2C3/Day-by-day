package Question2;

import Question2.fork.*;
import org.junit.Test;

import static org.junit.Assert.*;

public class TestQuestion2 {
    @Test
    public void test() {
        User boris = new User("Boris");
        User marc = new User("Marc");
        SteakhouseTypeA resto1 = new SteakhouseTypeA("Le Gourmet", 4, 2, 6, 4, 2);
        SteakhouseTypeB resto2 = new SteakhouseTypeB("Le Petit Coq", 4, 3, 2, 1, 2);

        Fork fork = new Fork();

        Booking booking1 = fork.book(resto1, boris, 5);
        System.out.println(booking1); // ok
        assertNotNull(booking1);

        Booking booking2 = fork.book(resto2, marc, 6);
        System.out.println(booking2); // ok
        assertNotNull(booking2);

        Booking booking3 = fork.book(resto1, boris, 5);
        System.out.println(booking3); // null
        assertNull(booking3);

        fork.cancel(booking1);

        Booking booking4 = fork.book(resto1, boris, 5);
        System.out.println(booking4); // ok
        assertNotNull(booking4);

        Booking booking5 = fork.book(resto2, marc, 4);
        System.out.println(booking5); // ok
        assertNotNull(booking5);

        Booking booking6 = fork.book(resto2, boris, 1);
        System.out.println(booking6); // null
        assertNull(booking6);

        System.out.println(fork);
        assertEquals("===== FORK STATUS =====\n" +
                "Booking 2 for Marc (for 6 people) in Le Petit Coq (Type B):\n" +
                "  - Table 4 (Capacity: 1)\n" +
                "  - Table 3 (Capacity: 2)\n" +
                "  - Table 5 (Capacity: 2)\n" +
                "  - Table 2 (Capacity: 3)\n" +
                "Booking 3 for Boris (for 5 people) in Le Gourmet (Type A):\n" +
                "  - Table 3 (Capacity: 6)\n" +
                "Booking 4 for Marc (for 4 people) in Le Petit Coq (Type B):\n" +
                "  - Table 1 (Capacity: 4)", fork.toString());
    }
}
