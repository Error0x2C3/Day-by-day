/*
Note :
J'utilise une extension au lieu de faire un provider family sur client_statte,
car avec un provider family :
1) un fichier/provider family poour chaque page => lourd.
2) chaque prorider family ajouté  crée un nouvel état en mémoire => alourdit le cache de l'application.

Je ne crée les fonctions dans les providers car :
1) les providers risque devenir un fourtout de tous les fonctions utiles pour els pages.

Extension mieux car :
- Pas de nouveau provider.
- Pas de code ajouter aux providers existants.
- Une place spéficique qui gére la récupération des données du provider et les donnes à la page spéficique.
- Un fichiers extensions avec les données nécesaire pour chaque page, c'es bcp de petit fichiers mais
    les informations de chaque page sont regroupés et bien organisées.

Un extension est une fonctions déguisé, ne stocke aucune nouvelle varialbes (pas de contruteur) en mémoire,
Ne fait que transformer et filtrer des donées existantse.

 */

import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';
import 'package:prbd_2526_f02/models/Restaurant.dart' hide Restaurant;
import '../models/reservation.dart';
import '../models/services.dart';
import '../models/restaurant.dart';
import '../providers/client_state_provider.dart';

// On entend le classe ClientStateData.
// On crée l'extension sur la donnée classe ClientStateData elle-meême, non sur  AsyncNotifierProvider.
extension ReservationFormData on ClientStateData{
  // Les fontions :
  // Elles peuvent être utilisées directement sur la classe ClientStateDate comme si elles appartennaient à celui-ci.


  // Le type de retour est une Réservation,
  // ? au cas au c'est null, risque qu'on ait pas id.
  Reservation? findReservation(int id){
    return reservationsList.where((r) => r.id == id).firstOrNull; // firstOrNull mieux gérer les erreurs que le firstWhere().
  }

  // Renvoie les services du restaurant à un jour donnée.
  List<Services> servicesDujour(Reservation reservation, int dayOfWeekReservation) {
    final dayOfWeek = reservation.timestamp.weekday;
    final restaurantId = reservation.restaurant.id;

    // Liste des services du restaurant pour un jour donné.
    final filteredServices = servicesList.where(
      // reservationCurrent.timestamp.weekday weekday => obetenir le jour de la semaine.
      (s) => s.restaurant.id == restaurantId &&  s.dayOfWeek == dayOfWeekReservation
    ).toList();

    // Tri chronologique.
    filteredServices.sort((a, b) => a.startTime.compareTo(b.startTime)); // On trie pour avoir les services dans loredre chronologique.
    return filteredServices;
  }

  // Calcule les créneaux horaires disponibles basés sur les services.
  List<TimeOfDay> getAvailableBookingTimes(List<Services>  servicesDuJour) {
    List<TimeOfDay> bookingTimes = []; // Liste des heures disponible.
    // Ex de DateTime en Dart  => 1970-01-01 20:30:00.000.
    // Ex de TimeOfDay => 20:30.
    for(Services service in servicesDuJour){
      // tmp et fin sont aux format Datime.
      DateTime tmp = service.startTime; // Ex: 14h00.
      DateTime fin = service.endTime;
      int slot_duration = service.restaurant.slotDuration; // Ex: (int) 30.
      while(tmp.isBefore(fin)){ // Tant que tmp est inf à l'heure de fin.

        TimeOfDay time = TimeOfDay.fromDateTime(tmp);
        bookingTimes.add(time);
        // Ajoute 30 minute par exemple.
        tmp = tmp.add(Duration(minutes: slot_duration));// Ex : 14h30.
      }
    }
    return bookingTimes;
  }

  // Donne le restaurant pour une id reservation donnée.
  Restaurant getRestaurantReservation(int idRestaurantReservation){
    return  restaurantsList.firstWhere(
            (r) => r.id == idRestaurantReservation
    );
  }

  // Retourne la date à affichée pour une réservation donnée.
  String getDateReservationAffichee(DateTime reservationCurrentDatetime){
    final dateFormat = DateFormat('EEEE d/MM/yyyy', 'fr_FR');
    return dateFormat.format(reservationCurrentDatetime);
  }

  String getNbrConviveReservationAffichee(int nbrConvive){
    return "";
  }
}