import 'dart:async';
import 'package:flutter/cupertino.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:prbd_2526_f02/models/reservation.dart';
import 'package:prbd_2526_f02/models/restaurant.dart';
import 'package:prbd_2526_f02/models/table.dart' as model; // L'alias c'est pour éviter les conflits avec Table de Flutter.
import 'package:prbd_2526_f02/providers/current_user_provider.dart';
import 'package:prbd_2526_f02/providers/simulated_time_provider.dart';

class ManagerStateData {
  final List<Restaurant> restaurantsList;
  final List<Reservation>? reservationsList;
  final Reservation? selectedReservation;
  final List<model.Table>?  availableTables;
  const ManagerStateData({
    required this.restaurantsList,
    this.reservationsList,
    this.selectedReservation,
    this.availableTables = const [], // Est vide par défaut.
  });

  ManagerStateData copyWith({
    List<Restaurant>? restaurantsList,
    List<Reservation>? reservationsList,
    Reservation? selectedReservation,
    List<model.Table>? availableTables
  }) {
    return ManagerStateData(
      restaurantsList: restaurantsList ?? this.restaurantsList,
      reservationsList: reservationsList ?? this.reservationsList,
      selectedReservation: selectedReservation ?? this.selectedReservation,
      availableTables:availableTables?? this.availableTables
    );
  }
}

final managerStateProvider =
AsyncNotifierProvider<ManagerStateNotifier, ManagerStateData>(
      () => ManagerStateNotifier(),
);

class ManagerStateNotifier extends AsyncNotifier<ManagerStateData> {
  @override
  Future<ManagerStateData> build() async {
    final previousSelected = state.value?.selectedReservation;
    final previousReservationList = state.value?.reservationsList;
    ref.watch(currentUserProvider);
    ref.watch(simulatedTimeProvider);
    final newRestaurantsList = await Restaurant.getRestaurantsManager();
    return ManagerStateData(
        restaurantsList: newRestaurantsList,
        selectedReservation: previousSelected,
        reservationsList: previousReservationList,
    );
  }

  Future<void> refreshRestaurantsList() async {
    try {
      final newsRestaurantsList = await Restaurant.getRestaurantsManager();

      if (state.hasValue) {
        state = AsyncData(
          state.value!.copyWith(
            restaurantsList: newsRestaurantsList,
          ),
        );
      }
    } catch (err, stack) {
      print('Erreur lors du rafraîchissement des restaurants : $err, $stack');
    }
  }


  Future<void> getReservationsRestaurant(int restaurantId) async {
    try {
      final newsReservationList = await Reservation.getReservationsByIdManager(restaurantId);

      if (state.hasValue) {
        state = AsyncData(
          state.value!.copyWith(
            reservationsList: newsReservationList,
          ),
        );
      }
    } catch (err, stack) {
      print('Erreur lors du rafraîchissement des réservations : $err, $stack');
    }
  }

  Future<void> getSelectedReservationByIdManager(int reservationId) async {
    try {
      final newselectedReservation = await Reservation.getReservationByIdManager(reservationId);

      if (state.hasValue) {
        state = AsyncData(
          state.value!.copyWith(
            selectedReservation: newselectedReservation,
          ),
        );
      }
    } catch (err, stack) {
      print('Erreur lors du rafraîchissement des réservations : $err, $stack');
    }
  }

  Future<void> confirmReservationManager(int reservationId) async {
    final currentState = state.value!;
    print(currentState.selectedReservation);
    state = AsyncLoading();
    try{
      await Reservation.completedReservation(reservationId);
      final updatedReservationsList = currentState.reservationsList
          ?.map((reservation) => reservation.id == reservationId ?
      reservation.copyWith(status: 'completed'): reservation,).toList();
      print(updatedReservationsList);
      Reservation selectedReservation = currentState.selectedReservation!;
      if (selectedReservation.id == reservationId) {
        selectedReservation = selectedReservation.copyWith(status: 'completed');
      }
      print(selectedReservation);

      state = AsyncData(
          currentState.copyWith(
            reservationsList: updatedReservationsList,
            selectedReservation: selectedReservation,
          )
      );

    }catch(error, stackTrace) {
      state = AsyncError(error, stackTrace);
    }
  }

  Future<void> cancelReservationsManager(int reservationId) async {
    final currentState = state.value!;
    state = AsyncLoading();
    try{
      await Reservation.cancelReservation(reservationId);

      final updatedReservationsList = currentState.reservationsList
          ?.map((reservation) => reservation.id == reservationId ?
      reservation.copyWith(status: 'cancelled') : reservation,
      ).toList();

      Reservation selectedReservation = currentState.selectedReservation!;
      if (selectedReservation.id == reservationId) {
        selectedReservation = selectedReservation.copyWith(status: 'cancelled');
      }

      final updatedRestaurants = currentState.restaurantsList.map((restaurant) {
        // on cible le bon restaurant (idealement par id, sinon par nom)
        if (restaurant.name == currentState.selectedReservation?.restaurantName) {

          // On ne décrémente que si la réservation annulée était pending
          final bool wasPending = currentState.selectedReservation?.status == 'pending';
          final currentPending = restaurant.countPendingReservation ?? 0;
          final int newPending = wasPending ? (currentPending > 0 ? currentPending - 1 : 0) : currentPending;

          // on cherche toutes les reservations actives restantes de ce restaurant
          final restaurantReservations = updatedReservationsList
              ?.where((r) => r.restaurantName == restaurant.name && r.status != 'cancelled')
              .toList();

          DateTime? newLastDatetime;
          if (restaurantReservations!.isNotEmpty) {
            // on trie ou on cherche la date la plus recente parmi celles qui restent
            newLastDatetime = restaurantReservations
                .map((r) => r.timestamp)
                .reduce((value, element) => value.isAfter(element) ? value : element);
          } else {
            // s'il n'y a plus aucune réservation active, là on peut mettre null
            newLastDatetime = null;
          }

          return restaurant.copyWith(
            countPendingReservation: newPending,
            lastReservationDatetime: newLastDatetime,
          );
        }

        return restaurant;
      }).toList();

      state = AsyncData(
          currentState.copyWith(
            reservationsList: updatedReservationsList,
            selectedReservation: selectedReservation,
            restaurantsList: updatedRestaurants,
          )
      );
    }catch(error, stackTrace){
      state = AsyncError(error, stackTrace);
    }
  }


  Future<void> loadTablesForAssignment(int reservationId) async {
    try {
      // On récupère d'abord les détails de la réservation (pour avoir le nb de convives).
      final reservation = await Reservation.getReservationByIdManager(reservationId);
      // On récupère les tables disponibles via l'API (SQL).
      final tables = await Reservation.getAvailableTablesForAssignment(reservationId);
      print(tables);
      if (state.hasValue) {
        state = AsyncData(
            state.value!.copyWith(
                selectedReservation: reservation,
                availableTables: tables,
            ),
        );
      }
    } catch (err, stack) {
      print('Erreur lors du chargement des tables : $err');
    }
  }
  void confirmWithTables(int reservationId, List<int> tableIds){}
}