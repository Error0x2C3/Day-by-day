package Question3;

import Question3.fork.Booking;
import Question3.fork.Fork;
import Question3.fork.SteakhouseTypeA;
import Question3.fork.User;

public class Program {
    public static void main(String[] args) {
        User boris = new User("Boris");
        User marc = new User("Marc");
        SteakhouseTypeA resto1 = new SteakhouseTypeA("Le Gourmet", 4, 2, 6, 4, 2);
        SteakhouseTypeA resto2 = new SteakhouseTypeA("Le Pickwick", 2, 1, 4, 3, 2);

        Fork fork = new Fork();

        Booking booking1 = fork.book(resto1, boris, 5);
        System.out.println(booking1); // ok

        Booking booking2 = fork.book(resto2, marc, 4);
        System.out.println(booking2); // ok

        Booking booking3 = fork.book(resto1, boris, 3);
        System.out.println(booking3); // ok

        fork.cancel(booking1);

        Booking booking4 = fork.book(resto1, boris, 6);
        System.out.println(booking4); // ok

        Booking booking5 = fork.book(resto2, marc, 2);
        System.out.println(booking5); // ok

        Booking booking6 = fork.book(resto2, marc, 1);
        System.out.println(booking6); // ok

        System.out.println(fork);
    }
}
