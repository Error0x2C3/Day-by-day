package Question2.knife;

import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;

public class Knife {
    private final List<Reservation> reservationList = new ArrayList<>();

    public Reservation reserve(PizzariaType pizzeria, Client client, int numberOfPeople) {
//        if (pizzeria instanceof PizzeriaTypeA) {
//            Reservation reservation = ((PizzeriaTypeA) pizzeria).reserve(client, numberOfPeople);
//            if (reservation != null) reservationList.add(reservation);
//            return reservation;
//        }
//        if (pizzeria instanceof PizzeriaTypeB) {
//            Reservation reservation = ((PizzeriaTypeB) pizzeria).reserve(client, numberOfPeople);
//            if (reservation != null) reservationList.add(reservation);
//            return reservation;
//        }
        Reservation reservation = pizzeria.reserve(client, numberOfPeople);
        if (reservation != null) {
            reservationList.add(reservation);
            return reservation;
        }
        return null;
    }

    public boolean cancel(Reservation reservation) {
        boolean cancelled = reservation.cancel();
        if (cancelled) reservationList.remove(reservation);
        return cancelled;
    }

    @Override
    public String toString() {
        String res = "===== KNIFE STATUS =====\n";
        Iterator<Reservation> iterator = reservationList.iterator();
        while (iterator.hasNext()) {
            res += iterator.next();
            if (iterator.hasNext()) res += "\n";
        }
        return res;
    }
}
