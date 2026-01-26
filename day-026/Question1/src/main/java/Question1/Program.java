package Question1;

import Question1.fork.Booking;
import Question1.fork.Fork;
import Question1.fork.SteakhouseTypeA;
import Question1.fork.User;

public class Program {
    public static void main(String[] args) {
        User boris = new User("Boris");
        User marc = new User("Marc");
        SteakhouseTypeA resto1 = new SteakhouseTypeA("Le Gourmet", 4, 2, 6, 4, 2);
        SteakhouseTypeA resto2 = new SteakhouseTypeA("Le Pickwick", 2, 1, 4, 3, 2);

        Fork fork = new Fork();

        Booking booking1 = fork.book(resto1, boris, 5);
        System.out.println(booking1); // ok

        Booking booking2 = fork.book(resto1, boris, 2);
        System.out.println(booking2); // ok

        fork.cancel(booking1);

        Booking booking3 = fork.book(resto2, boris, 2);
        System.out.println(booking3); // null car a déjà une reservation dans resto1

        Booking booking4 = fork.book(resto1, boris, 6);
        System.out.println(booking4); //ok

        Booking booking5 = fork.book(resto2, marc, 3);
        System.out.println(booking5); //ok

        Booking booking6 = fork.book(resto2, marc, 4);
        System.out.println(booking6); //ok

        Booking booking7 = fork.book(resto1, marc, 1);
        System.out.println(booking7); // null car a déjà une reservation dans resto2

        System.out.println(fork);

        System.out.println();

        fork.cancel(booking5);
        fork.cancel(booking6);

        Booking booking8 = fork.book(resto1, marc, 3);
        System.out.println(booking8); // ok

    }
}
