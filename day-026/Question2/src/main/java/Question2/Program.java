package Question2;


import Question2.fork.*;

public class Program {
    public static void main(String[] args) {
        User boris = new User("Boris");
        User marc = new User("Marc");
        SteakhouseTypeA resto1 = new SteakhouseTypeA("Le Gourmet", 4, 2, 6, 4, 2);
        SteakhouseTypeB resto2 = new SteakhouseTypeB("Le Petit Coq", 4, 3, 2, 1, 2);

        Fork fork = new Fork();

        Booking booking1 = fork.book(resto1, boris, 5);
        System.out.println(booking1); // ok

        Booking booking2 = fork.book(resto2, marc, 6);
        System.out.println(booking2); // ok

        Booking booking3 = fork.book(resto1, boris, 5);
        System.out.println(booking3); // null

        fork.cancel(booking1);

        Booking booking4 = fork.book(resto1, boris, 5);
        System.out.println(booking4); // ok

        Booking booking5 = fork.book(resto2, marc, 4);
        System.out.println(booking5); // ok

        Booking booking6 = fork.book(resto2, boris, 1);
        System.out.println(booking6); // null

        System.out.println(fork);
    }
}
