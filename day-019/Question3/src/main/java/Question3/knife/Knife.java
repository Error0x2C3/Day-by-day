package Question3.knife;

import java.util.*;

public class Knife {
    private final Set<Reservation> reservationList = new TreeSet<>((o1, o2) -> {
        int cmp = o1.getPizzeria().toString().compareTo(o2.getPizzeria().toString());
        if (cmp == 0) {
            cmp = Integer.compare(o1.getNumberOfPeople(), o2.getNumberOfPeople());
            if (cmp == 0) {
                cmp = Integer.compare(o1.getId(), o2.getId());
            }
        }
        return cmp;
    });

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
        /*reservationList.sort(new Comparator<Reservation>() {
            @Override
            public int compare(Reservation o1, Reservation o2) {
                int cmp = o1.getPizzeria().toString().compareTo(o2.getPizzeria().toString());
                if (cmp == 0) {
                    cmp = Integer.compare(o1.getNumberOfPeople(), o2.getNumberOfPeople());
                    if (cmp == 0) {
                        cmp = Integer.compare(o1.getId(), o2.getId());
                    }
                }
                return cmp;
            }
        });*/
        Iterator<Reservation> iterator = reservationList.iterator();
        while (iterator.hasNext()) {
            res += iterator.next();
            if (iterator.hasNext()) res += "\n";
        }
        return res;
    }
}