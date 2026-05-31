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
}

