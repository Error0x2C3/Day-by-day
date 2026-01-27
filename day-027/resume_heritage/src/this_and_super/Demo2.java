package this_and_super;

public class Demo2 {
    public static class A {
        public int foo() {
            return 1;
        }

        public int zork() {
            return this.bar();
        }

        public int bar() {
            return 3;
        }
    }

    public static class B extends A {
        @Override
        public int foo() {
            return super.zork();
        }
    }

    public static class C extends B {
        @Override
        public int zork() {
            return this.foo() + 5;
        }

        @Override
        public int bar() {
            return 7;
        }

    }

    public static void main(String[] args) {
        A a = new A();
        System.out.println(a.zork());

        B b = new B();
        System.out.println(b.zork());

        C c = new C();
        System.out.println(c.zork());

        B b = new C();
        System.out.println(b.zork());

        A a = new C();
        System.out.println(a.zork());

        A a = new B();
        System.out.println(a.zork());
    }
}
