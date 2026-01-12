package constructor;

public class Demo3 {
    static class A {
        public A() {
            System.out.println("A1");
        }

        public A(int i) {
            System.out.println("A2");
            System.out.println(i);
        }
    }

    static class B extends A {
        //appel super(); implicitement
        public B() {
            System.out.println("B");
        }

        public B(int i) {
            this(i, 2);
            System.out.println("B1");
        }

        public B(int i, int j) {
            this(i, j, 3);
            System.out.println("B2");
        }

        public B(int i, int j, int k) {
            super(i);
            System.out.println("B3");
            System.out.println(j);
            System.out.println(k);
        }
    }

    //ce code ne compile pas (appel récursif de constructeur)
    //On ne peut pas faire une boucle dans les appels vers des constructeurs
    //On va donc nécessairement un moment donné appeler un constructeur
    //qui va appeler un constructeur parent de manière explicite ou implicite
//    static class C extends A {
//        public C() {
//            this(3);
//        }
//
//        public C(int i) {
//            this(1, 2);
//        }
//
//        public C(int i, int j) {
//            this();
//        }
//    }

    public static void main(String[] args) {
        //affiche A1 puis B
        B b1 = new B();

        System.out.println("******");

        //affiche A2, 4, B3, 5, 6
        B b2 = new B(4, 5, 6);

        System.out.println("******");

        //affiche A2, 4, B3, 5, 3, B2
        B b3 = new B(4, 5);

        System.out.println("******");

        //affiche A2, 4, B3, 2, 3, B2, B1
        B b4 = new B(4);
    }
}
