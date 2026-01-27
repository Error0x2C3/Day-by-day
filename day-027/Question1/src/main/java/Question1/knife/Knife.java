package Question1.knife;

import java.util.*;

public class Knife {
    private int limite = 2;
    private final List<Reservation> reservationList = new ArrayList<>();
    private final Map<Client,List<Reservation>> map_reservationList = new HashMap<>();

//    public Reservation reserve(PizzeriaTypeA pizzeria, Client client, int numberOfPeople) {
//        Reservation reservation = pizzeria.reserve(client, numberOfPeople);
//        if (reservation != null) reservationList.add(reservation);
//        return reservation;
//    }

    public Reservation reserve(PizzeriaTypeA pizzeria, Client client, int numberOfPeople) {
        Reservation reservation = pizzeria.reserve(client, numberOfPeople);
        if(map_reservationList.containsKey(client)){
            if(!map_reservationList.get(client).isEmpty()){
                if(map_reservationList.get(client).size() == this.limite){
                    return null;
                }
            }
        }else{
            map_reservationList.put(client,new ArrayList<Reservation>());
        }
        if (reservation != null){
            map_reservationList.get(client).add(reservation);
        }
        return reservation;
    }

//    public boolean cancel(Reservation reservation) {
//        boolean cancelled = reservation.cancel();
//        if (cancelled) reservationList.remove(reservation);
//        return cancelled;
//    }

    public boolean cancel(Reservation reservation) {
        boolean cancelled = reservation.cancel();
        if (cancelled){
            map_reservationList.get(reservation.getClient()).remove(reservation);
        }
        return cancelled;
    }
    @Override
    public String toString() {
        String res ="";
        for (Map.Entry<Client, List<Reservation>> entry : map_reservationList.entrySet()) {
            Client client = entry.getKey();
            List<Reservation> list = entry.getValue();
            res += "===== KNIFE STATUS =====\n";
            Iterator<Reservation> iterator = list.iterator();
            while (iterator.hasNext()) {
                res += iterator.next();
                if (iterator.hasNext()) res += "\n";
            }
        }
        return res;
    }
}