package labo16.ex01;

public class Test {
    public static void a() {
        try {
            System.out.println("a1");
            b();
            System.out.println("a2");
        } catch (Exception exception) {
            System.out.println(exception.getMessage());
        } finally {
            System.out.println("a3");
        }
        System.out.println("a4");
    }
    public static void b() throws Exception {
        throw new Exception("b1");
    }
    public static void main(String[] args) {
        a();
        /*
        a1
        b1
        a2
        a3
        a4
         */
    }
}
