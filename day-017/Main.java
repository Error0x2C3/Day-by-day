//TIP To <b>Run</b> code, press <shortcut actionId="Run"/> or
// click the <icon src="AllIcons.Actions.Execute"/> icon in the gutter.

void main() {
    A a1 = new A();
    System.out.println(a1.zork());

    B b1 = new B();
    System.out.println(b1.zork());

    C c1 = new C();
    System.out.println(c1.zork());

    B b2 = new C();
    System.out.println(b2.zork());

    A a2 = new C();
    System.out.println(a2.zork());

    A a3 = new B();
    System.out.println(a3.zork());
}
