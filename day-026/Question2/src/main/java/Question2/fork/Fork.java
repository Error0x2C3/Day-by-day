package Question2.fork;

import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;

public class Fork {
    private final List<Booking> bookingList = new ArrayList<>();

    public Booking book(SteakhouseType steakhouse, User user, int numberOfPeople) {
//        if (steakhouse instanceof SteakhouseTypeA) {
//            Booking booking = ((SteakhouseTypeA) steakhouse).book(user, numberOfPeople);
//            if (booking != null) bookingList.add(booking);
//            return booking;
//        }
//        if (steakhouse instanceof SteakhouseTypeB) {
//            Booking booking = ((SteakhouseTypeB) steakhouse).book(user, numberOfPeople);
//            if (booking != null) bookingList.add(booking);
//            return booking;
//        }
        Booking booking = steakhouse.book(user, numberOfPeople);
        if (booking != null) {
            bookingList.add(booking);
            return booking;
        }
        return null;
    }

    public boolean cancel(Booking booking) {
        boolean cancelled = booking.cancel();
        if (cancelled) bookingList.remove(booking);
        return cancelled;
    }

    @Override
    public String toString() {
        String res = "===== FORK STATUS =====\n";
        Iterator<Booking> iterator = bookingList.iterator();
        while (iterator.hasNext()) {
            res += iterator.next();
            if (iterator.hasNext()) res += "\n";
        }
        return res;
    }
}
