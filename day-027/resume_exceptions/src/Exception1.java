import java.io.IOException;

public class Exception1 {
    public static void b() throws Exception {
        throw new IOException("b1");
    }

    public static void main(String[] args) {
        try {
            b();
        } catch (IOException e) {
            System.out.println("a");
        } catch (Exception e) {
            System.out.println("b");
        }
    }
}
