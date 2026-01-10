import java.io.IOException;

public class Exception2 {
    public static void b(int i) throws Exception {
        if(i % 2 == 0) {
            throw new IOException("b1");
        } else {
            throw new Exception("b2");
        }
    }

    public static void main(String[] args) {
        try {
            b(1);
        } catch (IOException e) {
            System.out.println(e.getMessage());
            System.out.println("a");
        } catch (Exception e) {
            System.out.println(e.getMessage());
            System.out.println("b");
        }
        // b2 b
        System.out.println("===================");
        try {
            b(2);
        } catch (IOException e) {
            System.out.println(e.getMessage());
            System.out.println("a");
        } catch (Exception e) {
            System.out.println(e.getMessage());
            System.out.println("b");
        }
        // b1 a
    }
}
