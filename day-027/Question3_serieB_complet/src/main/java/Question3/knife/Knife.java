package Question3.knife;

import java.util.*;

public class Knife {
    // private final List<Reservation> reservationList = new ArrayList<>();
    private final Set<Reservation> reservationList = new TreeSet<>((o1,o2)->
    {
        // Ordre alphabétique croissant du nom de la pizzeria.
        int cmp = o1.getPizzeria().getName().compareTo(o2.getPizzeria().getName());
        // Pour une même pizzeria, ordre croissant du nombre de couverts de la réservation.
        if(cmp == 0 && o1.getPizzeria().equals(o2.getPizzeria())){
            cmp = Integer.compare(o1.getNumberOfPeople(),o2.getNumberOfPeople());
            if(cmp == 0 ){
                // Pour une même pizzeria et un même nombre de couverts, ordre croissant de l’identifiant de la réservation.
                cmp = Integer.compare(o1.getId(),o2.getId());
            }
        }
        return cmp;
    }

    );
    public Reservation reserve(PizzeriaTypeA pizzeria, Client client, int numberOfPeople) {
        Reservation reservation = pizzeria.reserve(client, numberOfPeople);
        if (reservation != null) reservationList.add(reservation);
        return reservation;
    }

    public boolean cancel(Reservation reservation) {
        boolean cancelled = reservation.cancel();
        if (cancelled) reservationList.remove(reservation);
        return cancelled;
    }

    @Override
    public String toString() {
        String res = "===== KNIFE STATUS =====\n";
//        reservationList.sort(new Comparator<Reservation>() {
//            @Override
//            public int compare(Reservation o1, Reservation o2) {
//                // Ordre alphabétique croissant du nom de la pizzeria.
//                int cmp = o1.getPizzeria().getName().compareTo(o2.getPizzeria().getName());
//                // Pour une même pizzeria, ordre croissant du nombre de couverts de la réservation.
//                if(cmp == 0 && o1.getPizzeria().equals(o2.getPizzeria())){
//                    cmp = Integer.compare(o1.getNumberOfPeople(),o2.getNumberOfPeople());
//                    if(cmp == 0 ){
//                        // Pour une même pizzeria et un même nombre de couverts, ordre croissant de l’identifiant de la réservation.
//                        cmp = Integer.compare(o1.getId(),o2.getId());
//                    }
//                }
//                return cmp;
//            }
//        });
        Iterator<Reservation> iterator = reservationList.iterator();
        while (iterator.hasNext()) {
            res += iterator.next();
            if (iterator.hasNext()) res += "\n";
        }
        return res;
    }
}