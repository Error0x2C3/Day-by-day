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

    // Liste des services du restaurant pour un jour donné.
    final filteredServices = servicesList.where(
      // reservationCurrent.timestamp.weekday weekday => obetenir le jour de la semaine.
      (s) => s.restaurant.name == reservation.restaurantName &&  s.dayOfWeek == dayOfWeekReservation
    ).toList();

    // Tri chronologique.
    filteredServices.sort((a, b) => a.startTime.compareTo(b.startTime)); // On trie pour avoir les services dans loredre chronologique.
    return filteredServices;
  }

  // Calcule les créneaux horaires disponibles basés sur les services.
  List<TimeOfDay> getAvailableBookingTimes(List<Services>  servicesDuJour, Reservation reservationCurrent,DateTime dateReserationConst, DateTime _dateAffichee) {
    List<TimeOfDay> bookingTimes = []; // Liste des heures disponible.
    // Ex de DateTime en Dart  => 1970-01-01 20:30:00.000.
    // Ex de TimeOfDay => 20:30.
    for(Services service in servicesDuJour){
      // tmp et fin sont aux format Datime.
      TimeOfDay tmp = TimeOfDay.fromDateTime(service.startTime); // Datetime du début d'un service transofmer en TimeOfDay.
      TimeOfDay fin = TimeOfDay.fromDateTime(service.endTime); // Datetime de fin d'un service transofmer en TimeOfDay.
      TimeOfDay hourCurrentReservation = TimeOfDay.fromDateTime(reservationCurrent.timestamp); // Datetime de la réservation transformer en TimeOfDay.

      int slotDuration = service.restaurant.slotDuration!; // Ex: (int) 30.

      while((comparetoTimeOfDay(tmp, fin) < 0)){ // Tant que tmp est inf à l'heure de fin.
        // Ne fait le B) que Si la date sélectionné ==  la date de la réservation.
        if(compareDateTimeTo(dateReserationConst, _dateAffichee) == 0){
          // B) Ne prend que les heures qui sont supé ou égale à l'heure du service de la réservation.
          if(comparetoTimeOfDay(hourCurrentReservation,tmp ) == -1 || comparetoTimeOfDay(hourCurrentReservation,tmp ) == 0 ){
            bookingTimes.add(tmp);
          }
        }else{
          // Mets toutes les heures des services du restaurant.
          bookingTimes.add(tmp);
        }
        // Ajoute 30 minute par exemple.
        tmp = addMinutes(tmp, slotDuration);
      }
    }
    return bookingTimes;
  }

  // Ajoute x minutes à une variable de type TimeOfday.
  TimeOfDay addMinutes(TimeOfDay hour, int minutesAdd){
    int totalMinutes = hour.hour * 60 + hour.minute;
    int newTotalMinutes = totalMinutes + minutesAdd;
    return TimeOfDay(hour: (newTotalMinutes ~/60)%24, minute: newTotalMinutes %60);
  }

  // Compare deux TimeOfDay.
  int comparetoTimeOfDay(TimeOfDay d1, TimeOfDay d2){
    int debutMins =  d1.hour * 60 + d1.minute;
    int finMins = d2.hour * 60 + d2.minute;
    if(debutMins < finMins){
      return -1;
    }else if (debutMins > finMins){
      return 1;
    }
    return 0;
  }
  // Donne le restaurant pour une id reservation donnée.
  Restaurant getRestaurantReservation(Reservation Reservation){
    return  restaurantsList.firstWhere(
            (r) => r.name == Reservation.restaurantName
    );
  }

  // Retourne la date à affichée pour une réservation donnée.
  String getDateReservationAffichee(DateTime reservationCurrentDatetime){
    final dateFormat = DateFormat('EEEE d/MM/yyyy', 'fr_FR');
    return dateFormat.format(reservationCurrentDatetime);
  }

  // Retourne la date à affichée pour une réservation donnée.
  DateTime getDateReservation(Reservation reservationCurrent){
    return reservationCurrent.timestamp;
  }

  int compareDateTimeTo(DateTime d1, DateTime d2){
    // On crée deux variables DateTime qui ont que l'année, mois et jour sans heure et minute en plus.
    final date1 = DateTime(d1.year, d1.month, d1.day);
    final date2 = DateTime(d2.year, d2.month, d2.day);
    // Si date1 > d2 => 1, d1 < d2 => -1
    return date1.compareTo(date2);
  }
  
  DateTime addDaysToDateTime(DateTime datetime, int day){
    return datetime.add(Duration(days: day));
  }
  DateTime subtrDaysToDateTime(DateTime datetime, int day){
    return datetime.subtract(Duration(days: day));
  }
  String getNbrConviveReservationAffichee(int nbrConvive){
    return nbrConvive.toString();
  }

  int getNbrConviveReservation(int nbrConvive){
    return nbrConvive;
  }
}