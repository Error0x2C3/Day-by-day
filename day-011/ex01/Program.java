package labo16.ex01;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.Scanner;

public class Program {
    public static void main(String args[]){
        boolean good = true;
        do {
            try {
                Date date = input();
                System.out.println(date);
            } catch (RuntimeException e) {
                good = false;
                System.out.println("Date invalide. Rééssayez.");
            }
        }while (!good);
    }
    public static Date input(){
        System.out.print("Entrez 3 entiers séparés par des espaces (JJ MM YYYY) : ");
        Scanner scan = new Scanner(System.in);
        // On lit exactement 3 entiers
        int jour = scan.nextInt();
        int mois = scan.nextInt();
        int annee = scan.nextInt();
        // String a = scan.nextLine(); sinon le programme ne vas pas prendre les mots tapés.
//        int i = 0;
//        /*
//        Problème de la boucle while
//        La condition scan.hasNext() attendra indéfiniment une nouvelle entrée.
//        Pour lire exactement 3 entiers, utilisez une boucle for ou lisez les valeurs une par une.
//         */
//        while (scan.hasNext()) {
//            /*
//            hasNext() ne regarde pas si vous avez fini de taper votre ligne,
//            il continuera de demander des données tant que l'utilisateur en fournit
//             */
//            System.out.println(Integer.valueOf(scan.next()));
//            list.add(Integer.valueOf(scan.next()));
//            System.out.println(list.get(i));
//            i += 1;
//        }
        return new Date(jour,mois,annee);
    }
}
