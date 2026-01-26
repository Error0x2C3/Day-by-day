package Question3;

import Question3.fork.Booking;
import Question3.fork.Fork;
import Question3.fork.SteakhouseTypeA;
import Question3.fork.User;
import org.junit.Test;

import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertNotNull;

public class TestQuestion3 {
    @Test
    public void test() {
        User boris = new User("Boris");
        User marc = new User("Marc");
        SteakhouseTypeA resto1 = new SteakhouseTypeA("Le Gourmet", 4, 2, 6, 4, 2);
        SteakhouseTypeA resto2 = new SteakhouseTypeA("Le Pickwick", 2, 1, 4, 3, 2);

        Fork fork = new Fork();

        Booking booking1 = fork.book(resto1, boris, 3);
        System.out.println(booking1); // ok
        assertNotNull(booking1);

        Booking booking2 = fork.book(resto2, marc, 4);
        System.out.println(booking2); // ok
        assertNotNull(booking2);

        Booking booking3 = fork.book(resto2, marc, 1);
        System.out.println(booking3); // ok
        assertNotNull(booking3);

        Booking booking4 = fork.book(resto1, boris, 6);
        System.out.println(booking4); // ok
        assertNotNull(booking4);

        Booking booking5 = fork.book(resto2, marc, 2);
        System.out.println(booking5); // ok
        assertNotNull(booking5);

        Booking booking6 = fork.book(resto1, boris, 3);
        System.out.println(booking6); // ok
        assertNotNull(booking6);

        System.out.println(fork);
        assertEquals("===== FORK STATUS =====\n" +
                "Booking 1 for Boris (for 3 people) in Le Gourmet (Type A):\n" +
                "  - Table 1 (Capacity: 4)\n" +
                "Booking 6 for Boris (for 3 people) in Le Gourmet (Type A):\n" +
                "  - Table 4 (Capacity: 4)\n" +
                "Booking 4 for Boris (for 6 people) in Le Gourmet (Type A):\n" +
                "  - Table 3 (Capacity: 6)\n" +
                "Booking 3 for Marc (for 1 people) in Le Pickwick (Type A):\n" +
                "  - Table 2 (Capacity: 1)\n" +
                "Booking 5 for Marc (for 2 people) in Le Pickwick (Type A):\n" +
                "  - Table 1 (Capacity: 2)\n" +
                "Booking 2 for Marc (for 4 people) in Le Pickwick (Type A):\n" +
                "  - Table 3 (Capacity: 4)", fork.toString());
    }
}
