package constructor;

public class Demo1 {
    static class A {
        public A() {
            System.out.println("A");
        }
    }

    static class B extends A {
        //un constructeur sans appel explicite à un constructeur parent
        // va appeler le constructeur super();
        //équivalent à :
//    public B() {
//        super();
//        System.out.println("B");
//    }
        public B() {
            System.out.println("B");
        }
    }

    static class C extends A {
        //une class sans constructeur défini un constructeur sans paramètre
        //qui va appeler le constructeur parent super();
        //équivalent à :
//    public C() {
//        super();
//    }
        //qui est équivalent à :
//    public C() {
//    }
    }

    public static void main(String[] args) {
        //appel le constructeur de B qui appel le constructeur de A
        //affiche A puis B
        B b = new B();

        System.out.println("******");

        //appel le constructeur de C qui appel le constructeur de A
        //affiche A
        C c = new C();

        System.out.println("******");

        //appel le constructeur de B qui appel le constructeur de A
        //Ne change rien par rapport à B b = new B();
        //On "voit" le B comme un A mais il réagit comme un B
        //affiche A puis B
        A a = new B();
    }
}
