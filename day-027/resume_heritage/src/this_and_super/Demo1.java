package this_and_super;

public class Demo1 {
    static class A {
        public A() {
            this.test();
        }

        public void test() {
            System.out.println("A");
        }
    }

    static class B extends A {
        @Override
        public void test() {
            System.out.println("B");
        }
    }

    public static void main(String[] args) {
        //affiche A
        A a1 = new A();

        System.out.println("******");

        //le constructeur par défaut appel super(); qui va appeler this.test();
        //this référence l'objet courant (ici un objet de type B)
        //c'est donc la méthode test() de la classe B qui est appelée
        //affiche B
        B b = new B();

        System.out.println("******");

        //J'ai une référence A mais sur un objet B,
        //c'est donc un B qui va me répondre
        //même résultat que B b = new B();
        A a2 = new B();

        System.out.println("******");

        //derière la référence a1, il y a un objet A
        //affiche donc A
        a1.test();

        System.out.println("******");

        //derière la référence b, il y a un objet B
        //affiche donc B
        b.test();

        System.out.println("******");

        //derière la référence a2, il y a un objet B
        //affiche donc B
        a2.test();
    }
}
