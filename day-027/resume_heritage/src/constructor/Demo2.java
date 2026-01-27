package constructor;

public class Demo2 {
    static class A {
        public A(int i) {
            System.out.println("A");
            System.out.println(i);
        }
    }

    static class B extends A {
        //le constructeur doit appeler un constructeur parent explicitement
        //car il n'existe plus de constructeur sans paramètres
        //le compilateur ne sait donc pas ajouter un super();
        public B(int i) {
            super(i);
            System.out.println("B");
        }

        //On peut appeler un constructeur de la même classe
        //qui va un moment donné devoir appeler un constructeur parent
        public B() {
            this(3);
        }
    }

    public static void main(String[] args) {
        //affiche A, 1 puis B
        B b1 = new B(1);

        System.out.println("******");

        //affiche A, 3 puis B
        B b2 = new B();

        System.out.println("******");

        //même chose que B b2 = new B();
        A a = new B();
    }
}
