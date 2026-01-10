import java.io.IOException;

public class Exception1 {
    public static void b() throws Exception {
        throw new IOException("b1");
        /*
         L'instruction throw new IOException("b1"); crée et lance un objet d'exception de type IOException.
         */
    }

    public static void main(String[] args) {
        try {
            b();
        } catch (IOException e) {
            /*
            Java vérifie si l'objet lancé est une instance de IOException. C'est le cas ici.
            L'affichage d'un programme Java ne dépend pas de ce que l'exception
            contient, mais uniquement de ce que tu demandes d'imprimer avec System.out.println().
             */
            System.out.println("a");
        } catch (Exception e) {
            System.out.println("b");
        }
        // =>
        /*
        1)
        public static void b() throws IOException {
            throw new IOException("b1");
             L'instruction throw new IOException("b1"); crée et lance un objet d'exception de type IOException.
        }
        try {
            b();
        } catch (IOException e) {
            System.out.println("a");
        } catch (Exception e) {
            System.out.println("b");
        }
        =>a
         */
    }
}
