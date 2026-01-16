package Ex2;

public class Test {
    public static void main ( String [] args ) {
        // Devrait afficher...
        // Samedi 30 Décembre 2017
        // Dimanche 31 Décembre 2017
        // Lundi 1 Janvier 2018
        // Mardi 2 Janvier 2018

        // mais affiche...
        // Dimanche 31 Décembre 2017
        // Lundi 1 Janvier 2018
        // Mardi 2 Janvier 2018
        // Mercredi 3 Janvier 2018
        Date deb = new Date (30 , 12 , 2017);
        Date fin = new Date (2 , 1 , 2018);
        for ( Date d : new DateRange ( deb , fin ))
            System . out . println ( d );
    }
}
