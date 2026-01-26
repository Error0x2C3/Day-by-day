package Question1;

import Question1.fork.Booking;
import Question1.fork.Fork;
import Question1.fork.SteakhouseTypeA;
import Question1.fork.User;
import org.junit.Test;

import static org.junit.Assert.*;

public class TestQuestion1 {
    @Test
    public void test() {
        User boris = new User("Boris");
        User marc = new User("Marc");
        SteakhouseTypeA resto1 = new SteakhouseTypeA("Le Gourmet", 4, 2, 6, 4, 2);
        SteakhouseTypeA resto2 = new SteakhouseTypeA("Le Pickwick", 2, 1, 4, 3, 2);

        Fork fork = new Fork();

        Booking booking1 = fork.book(resto1, boris, 5);
        System.out.println(booking1); // ok
        assertNotNull(booking1);

        Booking booking2 = fork.book(resto1, boris, 2);
        System.out.println(booking2); // ok
        assertNotNull(booking2);

        fork.cancel(booking1);

        Booking booking3 = fork.book(resto2, boris, 2);
        System.out.println(booking3); // null car a déjà une reservation dans resto1
        assertNull(booking3);

        Booking booking4 = fork.book(resto1, boris, 6);
        System.out.println(booking4); //ok
        assertNotNull(booking4);

        Booking booking5 = fork.book(resto2, marc, 3);
        System.out.println(booking5); //ok
        assertNotNull(booking5);

        Booking booking6 = fork.book(resto2, marc, 4);
        System.out.println(booking6); //ok
        assertNotNull(booking6);

        Booking booking7 = fork.book(resto1, marc, 1);
        System.out.println(booking7); // null car a déjà une reservation dans resto2
        assertNull(booking7);

        System.out.println(fork);
        assertEquals(("===== FORK STATUS =====\n" +
                "Booking 4 for Marc (for 3 people) in Le Pickwick (Type A):\n" +
                "  - Table 4 (Capacity: 3)\n" +
                "Booking 5 for Marc (for 4 people) in Le Pickwick (Type A):\n" +
                "  - Table 3 (Capacity: 4)\n" +
                "Booking 2 for Boris (for 2 people) in Le Gourmet (Type A):\n" +
                "  - Table 2 (Capacity: 2)\n" +
                "Booking 3 for Boris (for 6 people) in Le Gourmet (Type A):\n" +
                "  - Table 3 (Capacity: 6)").length(), fork.toString().length());

        System.out.println();

        fork.cancel(booking5);
        fork.cancel(booking6);
        Booking booking8 = fork.book(resto1, marc, 3);
        System.out.println(booking8);
        assertNotNull(booking8);
    }
}
