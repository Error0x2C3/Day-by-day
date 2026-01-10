package labo15.ex04;


import java.util.HashSet;
import java.util.Random;
import java.util.Set;

public class ChronoHash {
    public static void main(String[] args) {
        final int MAX = 1000000;

        long startTime = System.currentTimeMillis();
        Set<Date> set= new HashSet<>();
        Random obj = new Random();
        int day = obj.nextInt(31);    // Entre 0 (inclus) et 31 (exclu)
        int month = obj.nextInt(12);
        int year = obj.nextInt(2100);
        for(int i = 0; i<= MAX;i++){
            set.add(new Date(day,month,year));
        }
        // Duree: 0.073 sec avec un pour hashCode.
        System.out.println("Duree: " + (double) (System.currentTimeMillis() - startTime) / 1000 + " sec.");
    }
}
