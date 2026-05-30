import 'dart:async';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:prbd_2526_f02/models/reservation.dart';
import 'package:prbd_2526_f02/models/restaurant.dart';
import '../models/services.dart';

class ClientStateData {
  final List<Reservation> reservationsList;
  final List<Restaurant> restaurantsList;
  final List<Services> servicesList;

  const ClientStateData({
    required this.reservationsList,
    required this.restaurantsList,
    required this.servicesList,
  });

  ClientStateData copyWith({
    List<Reservation>? reservationsList,
    List<Restaurant>? restaurantsList,
    List<Services>? servicesList,
  }) {
    return ClientStateData(
      reservationsList: reservationsList ?? this.reservationsList,
      restaurantsList: restaurantsList ?? this.restaurantsList,
      servicesList: servicesList ?? this.servicesList,
    );
  }
}
class SearchQueryNotifier extends Notifier<String>{
  @override
  String build() {
    return '';
  }

  void setQuery(String newQuery){
    state = newQuery;
  }
}

final searchQueryProvider =
  NotifierProvider<SearchQueryNotifier, String>(
          () => SearchQueryNotifier(),
  );

final searchResultProvider = FutureProvider<List<Restaurant>>((ref) async{
  final query = ref.watch(searchQueryProvider);
  return await Restaurant.getSearchRestaurants(query);
});
final clientStateProvider =
  AsyncNotifierProvider<ClientStateNotifier, ClientStateData>(
        () => ClientStateNotifier(),
  );

class ClientStateNotifier extends AsyncNotifier<ClientStateData> {
  @override
  FutureOr<ClientStateData> build() async {
    final result = await Future.wait([
      Reservation.getReservations(),
      Restaurant.getRestaurants(),
      Services.getServices(),
    ]);

    final listeReservations = result[0] as List<Reservation>;
    final listeRestaurants = result[1] as List<Restaurant>;
    final listeServices = result[2] as List<Services>;

    return ClientStateData(
      reservationsList: listeReservations,
      restaurantsList: listeRestaurants,
      servicesList: listeServices,
    );
  }

  /*
   param : liste de services d'un restaurant pour un jour donnée et l'heure dela réservation.
   Return  : Liste d'heures de réservation disponibles pour une réservation donnée à une date donnée.
   */
  List<TimeOfDay> getAvailableBookingtTimes(List<Services> services){
    // Ex de Dateime en Dart  => 1970-01-01 20:30:00.000.
    // Ex de TimeOfDay => 20:30.
    List<TimeOfDay> bookingTimes = []; // Liste des heures disponible.
    for(Services service in services){
      // tmp et fin sont aux format Datime
      DateTime tmp = service.startTime; // Ex: 14h00.
      DateTime fin = service.endTime;
      int slot_duration = service.restaurant.slotDuration;
      while(tmp.isBefore(fin)){ // Tant que tmp est inf à l'heure de fin.

        TimeOfDay time = TimeOfDay.fromDateTime(tmp);
        bookingTimes.add(time);
        // Ajoute 30 minute par exemple.
        tmp = tmp.add(Duration(minutes: slot_duration));// Ex : 14h30.
        // print(tmp);
        // print(time);
      }
    }
    return  bookingTimes;
  }

  /*
   param : liste de services d'un restaurant pour un jour donnée et l'heure dela réservation.
   Return  : Liste d'heures de réservation disponibles pour une réservation donnée à une date donnée.
   */
  void getAvailableBookingtTimes2(int reservationId, int dayOfWeekReservation){
    // Ex de Dateime en Dart  => 1970-01-01 20:30:00.000.
    // Ex de TimeOfDay => 20:30.
    if(!state.hasValue || state.value == null){return;} //  Si l'état n'est pas bien chargé et possède une donnéess.
    final data = state.value!;
    List<Reservation> reservations = data.reservationsList; //
    var reservationCurrent = reservations.firstWhere( // la réservation pour un id réservation donnée.
            (s) => s.id == reservationId
    );
    var restaurant = data.restaurantsList.firstWhere(
            (r) => r.id == reservationCurrent.restaurant.id
    );
    // Liste des services du restaurant pour un jour donné.
    List<Services> servicesDuJour = data.servicesList.where(
            // reservationCurrent.timestamp.weekday weekday => obetenir le jour de la semaine.
            (s) => s.restaurant.id == restaurant.id && s.dayOfWeek == dayOfWeekReservation  // Filtre pour avoir les services pour le restaurant de la réservation.
    ).toList();
    servicesDuJour.sort((a,b) => a.startTime.compareTo(b.startTime)); // On trie pour avoir les services dans loredre chronologique.

    print(servicesDuJour[0].endTime.runtimeType);
  //   if( state.hasValue){ // Si l'état est bien chargé et possède une donnéess.
  //     print(restaurant);
  //   //   // ------------------------------------------------------------------------
  //   //   List<TimeOfDay> bookingTimes = []; // Liste des heures disponible.
  //   //   for(Services service in servicesDuJour){
  //   //     // tmp et fin sont aux format Datime
  //   //     DateTime tmp = service.startTime; // Ex: 14h00.
  //   //     DateTime fin = service.endTime;
  //   //     int slot_duration = service.restaurant.slotDuration;
  //   //     while(tmp.isBefore(fin)){ // Tant que tmp est inf à l'heure de fin.
  //   //
  //   //       TimeOfDay time = TimeOfDay.fromDateTime(tmp);
  //   //       bookingTimes.add(time);
  //   //       // Ajoute 30 minute par exemple.
  //   //       tmp = tmp.add(Duration(minutes: slot_duration));// Ex : 14h30.
  //   //       // print(tmp);
  //   //       // print(time);
  //   //     }
  //   //   }
  //   //   return  bookingTimes;
  //   // }
  }
}

