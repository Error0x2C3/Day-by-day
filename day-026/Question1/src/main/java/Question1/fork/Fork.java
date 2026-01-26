package Question1.fork;

import java.util.*;

public class Fork {
    // private final List<Booking> bookingList = new ArrayList<>();
    private final Map<User,List<Booking>> map_booking_list = new HashMap<>();
//    public Booking book(SteakhouseTypeA steakhouse, User user, int numberOfPeople) {
//        Booking booking = steakhouse.book(user, numberOfPeople);
//        if (booking != null) {
//            bookingList.add(booking);
//        }
//        return booking;
//    }

    public Booking book(SteakhouseTypeA steakhouse, User user, int numberOfPeople) {
        Booking booking = steakhouse.book(user, numberOfPeople);
        if(map_booking_list.containsKey(user)){
            if(!map_booking_list.get(user).isEmpty()){
                for(Booking elem : map_booking_list.get(user)){
                    if(!elem.getSteakhouse().equals(steakhouse)){
                        return null;
                    }
                }
            }
        }else{
            map_booking_list.put(user,new ArrayList<Booking>());
        }
        if (booking != null) {
            map_booking_list.get(user).add(booking);
        }
        return booking;
    }

//    public boolean cancel(Booking booking) {
//        boolean cancelled = booking.cancel();
//        if (cancelled) bookingList.remove(booking);
//        return cancelled;
//    }

    public boolean cancel(Booking booking) {
        boolean cancelled = booking.cancel();
        if (cancelled){
            List list_booking_user = this.map_booking_list.get(booking.getUser());
            list_booking_user.remove(booking);
        }
        return cancelled;
    }

    @Override
    public String toString() {
        String res = "";
        for (Map.Entry<User, List<Booking>> entry : this.map_booking_list.entrySet()) {
            User user = entry.getKey();
            List<Booking> list = entry.getValue();
            res += "===== FORK STATUS =====\n";
            Iterator<Booking> iterator = list.iterator();
            while (iterator.hasNext()) {
                res += iterator.next();
                if (iterator.hasNext()) res += "\n";
            }
            res += "\n";
        }
        return res;
    }
}