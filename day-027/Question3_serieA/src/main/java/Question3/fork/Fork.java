package Question3.fork;

import java.util.ArrayList;
import java.util.Comparator;
import java.util.Iterator;
import java.util.List;

public class Fork {
    private final List<Booking> bookingList = new ArrayList<>();

    public Booking book(SteakhouseTypeA steakhouse, User user, int numberOfPeople) {
        Booking booking = steakhouse.book(user, numberOfPeople);
        if (booking != null) bookingList.add(booking);
        return booking;
    }

    public boolean cancel(Booking booking) {
        boolean cancelled = booking.cancel();
        if (cancelled) bookingList.remove(booking);
        return cancelled;
    }

    @Override
    public String toString() {
        String res = "===== FORK STATUS =====\n";
        bookingList.sort(new Comparator<Booking>() {
                 @Override
                 public int compare(Booking o1, Booking o2) {
                     // Ordre alphabétique croissant du nom de l’utilisateur.
                     int cmp = o1.getUser().toString().compareTo(o2.getUser().toString());
                     if(cmp == 0 && o1.getUser().equals(o2.getUser())){
                         // Pour un même utilisateur, ordre croissant du nombre de couverts de la réservation.
                         cmp= Integer.compare(o1.getNumberOfPeople(),o2.getNumberOfPeople());
                         if(cmp == 0){
                             // Pour un même utilisateur et un même nombre de couverts, ordre croissant de l’identifiant de la réservation.
                             cmp = Integer.compare(o1.getId(),o2.getId());
                         }
                     }
                     return cmp;
                 }
             }

        );
        Iterator<Booking> iterator = bookingList.iterator();
        while (iterator.hasNext()) {
            res += iterator.next();
            if (iterator.hasNext()) res += "\n";
        }
        return res;
    }
}