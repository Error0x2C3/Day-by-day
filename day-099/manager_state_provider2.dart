import 'dart:async';
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
  final List<int>? selectedTableIds;
  final List<model.Table>?  availableTables;
  const ManagerStateData({
    required this.restaurantsList,
    this.reservationsList,
    this.selectedReservation,
    this.availableTables = const [], // Est vide par défaut.
    this.selectedTableIds,
  });

  ManagerStateData copyWith({
    List<Restaurant>? restaurantsList,
    List<Reservation>? reservationsList,
    Reservation? selectedReservation,
    List<model.Table>? availableTables,
    List<int>? selectedTableIds,
  }) {
    return ManagerStateData(
      restaurantsList: restaurantsList ?? this.restaurantsList,
      reservationsList: reservationsList ?? this.reservationsList,
      selectedReservation: selectedReservation ?? this.selectedReservation,
      availableTables:availableTables?? this.availableTables,
      selectedTableIds: selectedTableIds ?? this.selectedTableIds,
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

  Future<void> refreshReservationsManager(int restaurantId) async {
    try {
      final newReservationsList = await Reservation.getReservationsByIdManager(restaurantId);

      if (state.hasValue) {
        state = AsyncData(
          state.value!.copyWith(
            reservationsList: newReservationsList,
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
  Future<void> refreshSelectedReservationManager(int reservationId) async {
    try {
      final  newselectedReservation = await Reservation.getReservationByIdManager(reservationId);
      if (state.hasValue) {
        state = AsyncData(
          state.value!.copyWith(
            selectedReservation: newselectedReservation,
          ),
        );
      }

    } catch (err, stack) {
      print('Erreur lors du refresh de la réservation selectionnée: $err, $stack');
      state = AsyncError(err, stack);
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
      // La réservation avant la modification de ses donées.
      Reservation oldReservation = currentState.selectedReservation!;

      await Reservation.cancelReservation(reservationId);

      final updatedReservationsList = currentState.reservationsList
          ?.map((reservation) => reservation.id == reservationId ?
      reservation.copyWith(status: 'cancelled') : reservation,
      ).toList();

      Reservation selectedReservation = currentState.selectedReservation!;
      if (selectedReservation.id == reservationId) {
        selectedReservation = selectedReservation.copyWith(status: 'cancelled', tables: []);
      }
      // Gestion de la phrase  : x demande(s) en attente sur la page: Mes restaurant (côté: Manager).
      final updatedRestaurants = UpdateSentencePendingRequest( currentState.restaurantsList,selectedReservation,oldReservation, updatedReservationsList!);
      // On parcourt la liste des restaurants.
      // final updatedRestaurants = currentState.restaurantsList.map((restaurant) {
      //   // on cible le bon restaurant (idealement par id, sinon par nom).
      //   if (restaurant.name == currentState.selectedReservation?.restaurantName) {
      //
      //     // On ne décrémente que si la réservation actuellement modifiée (ex: est passée de pending à cancel/confirmed) était pending à la base.
      //     final bool wasPending = currentState.selectedReservation?.status == 'pending';
      //     // On récupére le nombre de réservations pending liées au restaurant de notre réservation modifiée.
      //     final currentPending = restaurant.countPendingReservation ?? 0;
      //     // Si la rservation modifiée était pending à la base alors on décrémente.
      //     final int newPending = wasPending ? (currentPending > 0 ? currentPending - 1 : 0) : currentPending;
      //
      //     // on cherche toutes les reservations actives restantes de ce restaurant (pour le calcul du newLastDatetime).
      //     final restaurantReservations = updatedReservationsList
      //         ?.where((r) => r.restaurantName == restaurant.name && r.status != 'cancelled')
      //         .toList();
      //
      //     DateTime? newLastDatetime;
      //     if (restaurantReservations!.isNotEmpty) {
      //       // on trie ou on cherche la date la plus recente parmi celles qui restent.
      //       newLastDatetime = restaurantReservations
      //           .map((r) => r.timestamp)
      //           .reduce((value, element) => value.isAfter(element) ? value : element);
      //     } else {
      //       // s'il n'y a plus aucune réservation active, là on peut mettre null
      //       newLastDatetime = null;
      //     }
      //
      //     return restaurant.copyWith( // En faisant ce return, le Riverpond détecte le changement et rafrapichit l'écran.
      //       countPendingReservation: newPending,
      //       lastReservationDatetime: newLastDatetime,
      //     );
      //   }
      //   // On fait return, pourque toutes les autres restaurants qui ne sont pas concernées,
      //   // Sont renvoyé/ajouté tel quel dans la variable List<Restaurant> updatedRestaurants.
      //   // Sans le return  on aurait que le restaurant concerné dans updateRestaurants.
      //   return restaurant;
      // }).toList();

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

  // Gestion de la phrase : x demande(s) en attente sur la page: Mes restaurant (côté: Manager).
  //  -restaurantList => liste des restaurants du provider actuel ex:  currentState.restaurantsList.
  //  -selectedReservation => la réservation qu'on a manipulée/modifiée ex: currentState.selectedReservation (la réservation après modif) de cancelReservationsManager(int reservationId).
  //  -oldReservation => la réservation avant qu'on ne l'est manipulé/modifiée. ex: avant qu'on est modifié son status de pending à cancel de cancelReservationsManager(int reservationId).
  //  -updatedReservationsList => la liste des réservations CONTENANT selectedReservation et non oldReservation
  //    ex:  final updatedReservationsList = currentState.reservationsList?.map(...); de cancelReservationsManager(int reservationId).
  List<Restaurant> UpdateSentencePendingRequest(List<Restaurant> restaurantsList, Reservation selectedReservation, Reservation oldReservation, List<Reservation> updatedReservationsList){
    // On parcourt la liste des restaurants.
    final updatedRestaurants = restaurantsList.map((restaurant) {
      // on cible le bon restaurant (idealement par id, sinon par nom).
      if (restaurant.name == selectedReservation.restaurantName) {

        // On ne décrémente que si la réservation actuellement modifiée ( ex: est passée de pending à cancel/confirmed) était pending à la base.
        final bool wasPending = oldReservation.status == 'pending';
        // On récupére le nombre de réservations pending liées au restaurant de notre réservation modifiée.
        final currentPending = restaurant.countPendingReservation ?? 0;
        // Si la rservation modifiée était pending à la base alors on décrémente.
        final int newPending = wasPending ? (currentPending > 0 ? currentPending - 1 : 0) : currentPending;

        // on cherche toutes les reservations actives restantes de ce restaurant (pour le calcul du newLastDatetime).
        final restaurantReservations = updatedReservationsList.where((r) => r.restaurantName == restaurant.name && r.status != 'cancelled')
            .toList();

        DateTime? newLastDatetime;
        if (restaurantReservations.isNotEmpty) {
          // on trie ou on cherche la date la plus recente parmi celles qui restent.
          newLastDatetime = restaurantReservations
              .map((r) => r.timestamp)
              .reduce((value, element) => value.isAfter(element) ? value : element);
        } else {
          // s'il n'y a plus aucune réservation active, là on peut mettre null
          newLastDatetime = null;
        }

        return restaurant.copyWith( // En faisant ce return, le Riverpond détecte le changement et rafrapichit l'écran.
          countPendingReservation: newPending,
          lastReservationDatetime: newLastDatetime,
        );
      }
      // On fait return, pourque toutes les autres restaurants qui ne sont pas concernées,
      // Sont renvoyé/ajouté tel quel dans la variable List<Restaurant> updatedRestaurants.
      // Sans le return  on aurait que le restaurant concerné dans updateRestaurants.
      return restaurant;
    }).toList();
    return updatedRestaurants;
  }
  // Récupére les tables pour la page assign tables.
  Future<void> loadTablesForAssignment(int reservationId) async {
    try {
      // On récupère d'abord les détails de la réservation (pour avoir le nbr de convives).
      final reservation = await Reservation.getReservationByIdManager(reservationId);
      // On récupère les tables disponibles via l'API (SQL).
      final listTable = await model.Table.getAvailableTablesForAssignment(reservationId);
      // Si je peux lire les anciennes données (state.value).
      // pour créer les nouvelles sans tout perdre.
      if (state.hasValue) {
        //  AsyncData(...) dit à Flutter que mon provider a changé, redessine avec les nouvelles datas.
        state = AsyncData(
          state.value!.copyWith(
            selectedReservation: reservation,
            availableTables: listTable,
          ),
        );
      }
    } catch (err, stack) {
      print('Erreur lors du chargement des tables : $err, $stack');
    }
  }
  // confirme la réservation et assigne une table à celle-ci + est appelé à la page assign table.
  Future<void> confirmWithTables(int reservationId, List<int> tableIds) async {
    final currentState = state.value!;
    state = const AsyncLoading(); //  Le ListView disparaît et un CircularProgressIndicator apparaît au milieu.
    try {

      // Enregistre les tables pour cette résertation + change status pendind en confirmed.
      await Reservation.confirmAndAssignTables(reservationId, tableIds);
      // Ancienne méthode pour tout mettre à jour :
      // await refreshSelectedReservationManager(reservationId); // On met à jour la réservation sélectionné avec les nouvelles donées.
      // final restaurant = await Reservation.getRestaurantByReservation(reservationId);
      // await refreshReservationsManager(restaurant.id); // rafraîchir la liste globale des réservations.

      // Nouvelle méthode pour tout mettre à jour :

      // La réservation avant la modification de ses donées.
      Reservation oldReservation = currentState.selectedReservation!;
      // Je mets à jour la nouvelle donnée de la réservation,
      // qui est dans la liste des réserations du provider.
      final updatedReservationsList = currentState.reservationsList
          // je parcours ma liste pour avoir la réservation dont l'id = reservationId.
          ?.map((reservation) => reservation.id == reservationId ?
          // Si elle existe alors je change le status de cette réservation en confirmed,
          // Sinon je laisse les autres réservations tranquilles.
          reservation.copyWith(status:'confirmed') : reservation,
      ).toList();
      // Je mets à jour la nouvelle donnée de la réservation,
      // qui se trouve dans la variable selectedReservation su provider.
      Reservation selectedReservation = currentState.selectedReservation!;
      if (selectedReservation.id == reservationId) {
        selectedReservation = selectedReservation.copyWith(status:'confirmed');
      }
      final updatedRestaurants = UpdateSentencePendingRequest( currentState.restaurantsList,selectedReservation,oldReservation, updatedReservationsList!);
      state = AsyncData(
          currentState.copyWith(
            reservationsList: updatedReservationsList,
            selectedReservation: selectedReservation,
            restaurantsList: updatedRestaurants,
          )
      );
    } catch (err, stack) {
      state = AsyncError(err, stack);
    }
  }

  Future<void> loadTablesForReservation(int reservationId) async {
    try{
      final listTable = await model.Table.getAvailableTablesForAssignment(reservationId);

      if(state.hasValue){
        List<int> initialSelection = [];
        for(model.Table table in listTable) {
          if(table.isCurrentlyAssigned == true) {
            initialSelection.add(table.id);
          }
        }
        state = AsyncData(state.value!.copyWith(
          availableTables: listTable,
          selectedTableIds: initialSelection,
        ));

      }

    }catch(error, stackTrace){
      print('Erreur lors du chargement des réservations : $error, $stackTrace');
    }
  }
  void toggleTableSelection (int tableId) {
    final currentSelection = (state.value?.selectedTableIds ?? []);
    if (currentSelection.contains(tableId)) {
      currentSelection.remove(tableId);
    } else {
      currentSelection.add(tableId);
    }

    state = AsyncData(
      state.value!.copyWith(
        selectedTableIds: currentSelection,
      ),
    );
  }

}