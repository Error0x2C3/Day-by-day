import 'dart:async';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:prbd_2526_f02/models/reservation.dart';
import 'package:prbd_2526_f02/models/restaurant.dart';
import 'package:prbd_2526_f02/providers/current_user_provider.dart';
import 'package:prbd_2526_f02/providers/reference_time_provider.dart';
import 'package:prbd_2526_f02/providers/simulated_time_provider.dart';
import '../models/services.dart';

class ClientStateData {
  final List<Reservation> reservationsList;
  final List<Restaurant> restaurantsList;
  final List<Services> servicesList;
  final String? searchFilter;
  final Reservation? selectedReservation;
  final Restaurant? seletedRestaurant;
  final DateTime? reservationDate;
  final String? reservationTime;
  final int reservationGuests;
  final String reservationRequests;
  final List<String> availableSlots;
  final bool capacityWarning;
  final bool hasExistingReservationOnDate;

  const ClientStateData({
    required this.reservationsList,
    required this.restaurantsList,
    this.selectedReservation,
    this.seletedRestaurant,
    this.searchFilter,
    required this.servicesList,
    this.reservationDate,
    this.reservationTime,
    this.reservationGuests = 2,
    this.reservationRequests = '',
    this.availableSlots = const [],
    this.capacityWarning = false,
    this.hasExistingReservationOnDate = false,
  });

  ClientStateData copyWith({
    List<Reservation>? reservationsList,
    List<Restaurant>? restaurantsList,
    Reservation? selectedReservation,
    Restaurant? seletedRestaurant,
    List<Services>? servicesList,
    String? searchFilter,
    DateTime? reservationDate,
    String? reservationTime,
    int? reservationGuests,
    String? reservationRequests,
    List<String>? availableSlots,
    bool? capacityWarning,
    bool? hasExistingReservationOnDate,
  }) {
    return ClientStateData(
      reservationsList: reservationsList ?? this.reservationsList,
      restaurantsList: restaurantsList ?? this.restaurantsList,
      servicesList: servicesList ?? this.servicesList,
      searchFilter: searchFilter ?? this.searchFilter,
      selectedReservation: selectedReservation ?? this.selectedReservation,
      seletedRestaurant: seletedRestaurant ?? this.seletedRestaurant,
      reservationDate: reservationDate ?? this.reservationDate,
      reservationTime: reservationTime ?? this.reservationTime,
      reservationGuests: reservationGuests ?? this.reservationGuests,
      reservationRequests: reservationRequests ?? this.reservationRequests,
      availableSlots: availableSlots ?? this.availableSlots,
      capacityWarning: capacityWarning ?? this.capacityWarning,
      hasExistingReservationOnDate: hasExistingReservationOnDate ?? this.hasExistingReservationOnDate,
    );
  }
}

final clientStateProvider =
  AsyncNotifierProvider<ClientStateNotifier, ClientStateData>(
        () => ClientStateNotifier(),
  );

class ClientStateNotifier extends AsyncNotifier<ClientStateData> {
  Timer? _debounce;
  @override
  FutureOr<ClientStateData> build() async {
    final previousSelected = state.value?.selectedReservation;
    ref.watch(currentUserProvider);
    ref.watch(simulatedTimeProvider);
    final result = await Future.wait([
      Reservation.getReservations(),
      Restaurant.getRestaurantClient(""),
      Services.getServices(),
    ]);

    final listeReservations = result[0] as List<Reservation>;
    final listeRestaurants = result[1] as List<Restaurant>;
    final listeServices = result[2] as List<Services>;

    return ClientStateData(
      reservationsList: listeReservations,
      restaurantsList: listeRestaurants,
      servicesList: listeServices,
      selectedReservation: previousSelected,
    );
  }

  Future<void> researchRestaurants(String newSearchFilter) async {
    try {
      // récupére la liste des restos.
      final newRestaurantsList = await Restaurant.getRestaurantClient(newSearchFilter);
      // Mise à jour de l'état => l'UI se réfraissit automatiquement.
      state = const AsyncLoading();
      if (state.hasValue) {
        state = AsyncData(
          state.value!.copyWith(
              restaurantsList: newRestaurantsList,
              searchFilter: newSearchFilter,
          ),
        );
      }
    } catch (err, stack) {
      print('Erreur lors de l' 'obtention des restaurants après recherche : $err, $stack');
    }
  }

  Future<void> getSelectedReservation(int id) async {
    try {
      final newsSelectedReservation = await Reservation.getReservationById(id);

      if (state.hasValue) {
        state = AsyncData(
          state.value!.copyWith(
            selectedReservation: newsSelectedReservation,
          ),
        );
      }
    } catch (err, stack) {
      print('Erreur lors de la demande de la réservation selectionnée: $err, $stack');
    }
  }

  Future<void> refreshSelectedReservation() async {
      final currentState = state.value!;
      final currentReservation = currentState.selectedReservation;
      try {
      final newsSelectedReservation = await Reservation.getReservationById(currentReservation!.id);

      state = AsyncData(
        currentState.copyWith(
          selectedReservation: newsSelectedReservation,
        ),
      );

    } catch (err, stack) {
      print('Erreur lors du refresh de la réservation selectionnée: $err, $stack');
      state = AsyncError(err, stack);
    }
  }

  Future<void> refreshReservations() async {
    try {
      final newReservationsList = await Reservation.getReservations();

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

  Future<void> refreshRestaurants() async {
    try {
      final newRestaurantsList = await Restaurant.getRestaurantClient("");
      final newsearchFilter = "";

      if (state.hasValue) {
        state = AsyncData(
          state.value!.copyWith(
            restaurantsList: newRestaurantsList,
            searchFilter: newsearchFilter
          ),
        );
      }
    } catch (err, stack) {
      print('Erreur lors du rafraîchissement des restaurants : $err, $stack');
    }
  }

  Future<void> cancelReservationsClient(int reservationId) async {
    final currentState = state.value!;
    state = AsyncLoading();
    try{
      await Reservation.cancelReservation(reservationId);
      final updatedReservationsList = currentState.reservationsList
          .map((reservation) => reservation.id == reservationId ?
            reservation.copyWith(status: 'cancelled') : reservation,
      ).toList();

      Reservation selectedReservation = currentState.selectedReservation!;
      if (selectedReservation.id == reservationId) {
        selectedReservation = selectedReservation.copyWith(status: 'cancelled');
      }

      final updatedRestaurants = currentState.restaurantsList.map((restaurant) {
        // on cible le bon restaurant (idealement par id, sinon par nom)
        if (restaurant.name == currentState.selectedReservation?.restaurantName) {

          // On ne décrémente que si la réservation annulée était 'pending'
          final bool wasPending = currentState.selectedReservation?.status == 'pending';
          final currentPending = restaurant.countPendingReservation ?? 0;
          final int newPending = wasPending ? (currentPending > 0 ? currentPending - 1 : 0) : currentPending;

          // on cherche toutes les reservations actives restantes de ce restaurant
          final restaurantReservations = updatedReservationsList
              .where((r) => r.restaurantName == restaurant.name && r.status != 'cancelled')
              .toList();

          DateTime? newLastDatetime;
          if (restaurantReservations.isNotEmpty) {
            // on trie ou on cherche la date la plus récente parmi celles qui restent
            newLastDatetime = restaurantReservations
                .map((r) => r.timestamp)
                .reduce((value, element) => value.isAfter(element) ? value : element);
          } else {
            // s'il n'y a plus aucune reservation active, là on peut mettre null
            newLastDatetime = null;
          }

          // on applique les modifications au restaurant
          return restaurant.copyWith(
            countPendingReservation: newPending,
            lastReservationDatetime: newLastDatetime, // Contient la vraie valeur recalculée
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
  Future<void> initReservationForm({Reservation? existingReservation, required int restaurantId}) async{
    if(!state.hasValue) return; // ca regarde si les donnees sont bien chargee avant de modifer le formulaire

    if(existingReservation == null){
      // MODE AJOUT
      final datetime = ref.watch(referenceTimeProvider);
      List<String> slots = [];

      for(int i = 0 ; i <= 7 ; i++){
        slots = await Reservation.fetchAvailableSlots(restaurantId, datetime);


        if(slots.isNotEmpty){
          break;
        }else{
          datetime.add(Duration(days: 1));
        }
      }
      final haveARes = _checkIfHasReservation(datetime);
      state = AsyncData(
        state.value!.copyWith(
        reservationDate: datetime,
        reservationTime: slots.isNotEmpty ? slots.first : null,
        reservationGuests: 2,
        reservationRequests: '',
        availableSlots: slots,
        capacityWarning: false,
        hasExistingReservationOnDate: haveARes,
      ));
    }else{
      // mode EDITION
      final timestamp = existingReservation.timestamp;
      final dateOnly = DateTime(timestamp.year, timestamp.month, timestamp.day);
      final timeOnly = "${timestamp.hour.toString().padLeft(2,'0')}:${timestamp.minute.toString().padLeft(2,'0')}";
      state = AsyncData(
      state.value!.copyWith(
        reservationDate: dateOnly,
        reservationTime: timeOnly,
        reservationGuests: existingReservation.numberOfGuests,
        reservationRequests: existingReservation.specialRequests ?? '',
        capacityWarning: false,
      ));

      // on charge le creaneau pour cette date
      await loadAvailableSlotsEdition(restaurantId, dateOnly);
      print(state.value!.availableSlots);
    }
  }
  bool _checkIfHasReservation(DateTime dateToCheck){
    if(!state.hasValue) return false;
    final list = state.value!.reservationsList;
    //verifier qu'il ya au moins une reservations
    return list.any((res) {
      return
        res.timestamp.year == dateToCheck.year &&
        res.timestamp.month == dateToCheck.month &&
        res.timestamp.day == dateToCheck.day;
    });
  }

  // Donnes les heures disponible en mode add.
  Future<void> loadAvailableSlots(int restaurantId, DateTime dateOnly) async{
    if(!state.hasValue) return;
    try {
      final newSlot = await Reservation.fetchAvailableSlots(
          restaurantId, dateOnly);
      state = AsyncData(
          state.value!.copyWith(
            availableSlots: newSlot,
          )
      );
    }catch(error){
      print("Il n'y a pas de creaneau disponible : $error");
      state = AsyncData(
        state.value!.copyWith(
          availableSlots: [],
        )
      );
    }
  }
  // Donne les heures disponibles en mode edition.
  Future<void> loadAvailableSlotsEdition(int restaurantId, DateTime dateOnly) async{
    if(!state.hasValue) return;
    try {
      final newSlot = await Reservation.fetchAvailableSlotsEdition(
          restaurantId, dateOnly);
      state = AsyncData(
          state.value!.copyWith(
            availableSlots: newSlot,
          )
      );
    }catch(error){
      print("Il n'y a pas de creaneau disponible : $error");
      state = AsyncData(
          state.value!.copyWith(
            availableSlots: [],
          )
      );
    }
  }
  Future<void> updateGuestsAndCheckCapacity(int restaurantId, int newGuests) async{
    if(!state.hasValue) return;
    state = AsyncData(
      state.value!.copyWith(
        reservationGuests: newGuests,
      )
    );
    _debounce?.cancel();
    _debounce = Timer(const Duration(milliseconds: 500), () async {
      final currentDate = state.value!.reservationDate;
      final currentTime = state.value!.reservationTime;

      if (currentDate == null || currentTime == null) return;
      final timeParts = currentTime.split(':');
      final hours = int.parse(timeParts[0]); // on prends le premier chiffre avant le :
      final minutes = int.parse(timeParts[1]); // on prends le chiffre apres le : du temps

      final completeDateTime = DateTime(
        currentDate.year,
        currentDate.month,
        currentDate.day,
        hours,
        minutes,
      );
      try {
       final hasWarning = await Reservation.checkCapacityWarning(
            restaurantId, completeDateTime, newGuests);
       if (state.hasValue) {
         state = AsyncData(
           state.value!.copyWith(
             capacityWarning: hasWarning,
           )
         );
       }
      }catch(error){
        print("Erreur lors de la vérification de la capacité : $error");
      }
    });
  }

  Future<void> updateGuestsAndCheckCapacityEdition(int reservationid,int restaurantId, int newGuests) async{
    if(!state.hasValue) return;
    state = AsyncData(
        state.value!.copyWith(
          reservationGuests: newGuests,
        )
    );
    _debounce?.cancel();
    _debounce = Timer(const Duration(milliseconds: 500), () async {
      final currentDate = state.value!.reservationDate;
      final currentTime = state.value!.reservationTime;

      if (currentDate == null || currentTime == null) return;
      final timeParts = currentTime.split(':');
      final hours = int.parse(timeParts[0]); // on prends le premier chiffre avant le :
      final minutes = int.parse(timeParts[1]); // on prends le chiffre apres le : du temps

      final completeDateTime = DateTime(
        currentDate.year,
        currentDate.month,
        currentDate.day,
        hours,
        minutes,
      );
      try {
        final hasWarning = await Reservation.checkCapacityWarningEdition(
            reservationid,restaurantId, completeDateTime, newGuests);
        if (state.hasValue) {
          state = AsyncData(
              state.value!.copyWith(
                capacityWarning: hasWarning,
              )
          );
        }
      }catch(error){
        print("Erreur lors de la vérification de la capacité : $error");
      }
    });
  }

  Future<void> updateDate(int restaurantId, DateTime newDate) async {
    if(!state.hasValue) return;
    // regarde si on a une reservation
    final haveARes = _checkIfHasReservation(newDate);
    state = AsyncData(
        state.value!.copyWith(
          reservationDate: newDate,
          reservationTime: null,
          availableSlots: null,
          hasExistingReservationOnDate: haveARes,
        )
    );
    await loadAvailableSlots(restaurantId, newDate);
  }

  Future<void> updateDateEdition(int restaurantId, DateTime newDate) async {
    if(!state.hasValue) return;
    // regarde si on a une reservation
    final haveARes = _checkIfHasReservation(newDate);
    state = AsyncData(
        state.value!.copyWith(
          reservationDate: newDate,
          reservationTime: null,
          availableSlots: [],
          hasExistingReservationOnDate: haveARes,
        )
    );
    await loadAvailableSlotsEdition(restaurantId, newDate);
  }

  void updateTime(int restaurantId, String newTime, {int? reservationId}) {
    if (!state.hasValue) return;

    state = AsyncData(
        state.value!.copyWith(
          reservationTime: newTime,
        )
    );
    // Mode edition :
    if(reservationId != null){
      updateGuestsAndCheckCapacityEdition(reservationId, restaurantId, state.value!.reservationGuests);
    }else{
      updateGuestsAndCheckCapacity(restaurantId, state.value!.reservationGuests);
    }
  }

  Future<String?> submitReservation(int restaurantId, Reservation? existingReservation, String requests) async {
    if (!state.hasValue) return "Erreur de chargement du formulaire.";
    final formData = state.value!;

    if (formData.reservationDate == null || formData.reservationTime == null) {
      return 'Veuillez sélectionner une date et une heure.';
    }

    if (formData.capacityWarning == true){
      return 'Impossible de réserver : car la capacité est surcharger. ';
    }

    final date = formData.reservationDate!;
    final timeParts = formData.reservationTime!.split(':');
    final completeDateTime = DateTime(
      date.year, date.month, date.day,
      int.parse(timeParts[0]), int.parse(timeParts[1]),
    );


    try {
      if (existingReservation == null) {
        await Reservation.createReservation(restaurantId, completeDateTime, formData.reservationGuests, requests);
      } else {
        // Mode edition :
        await Reservation.updateReservationEdition(
            existingReservation.id,
            completeDateTime,
            formData.reservationGuests,
            requests
        );
        // On met à jour  ccette réservation précise avec les nouvelles valeurs.
        await getSelectedReservation(existingReservation.id);
      }
      await refreshReservations(); // on met a jour la liste global des réservations.
      return null; // cbon pas d'erreur ici

    } catch (erreur) {
      return "Erreur lors de la réservation : $erreur";
    }
  }
}