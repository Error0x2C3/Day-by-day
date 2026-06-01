import 'dart:developer';

import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';
import 'package:prbd_2526_f02/pages/reservation_form_ext.dart';
import '../providers/client_state_provider.dart';

class ReservationFormScreen extends ConsumerStatefulWidget {
  const ReservationFormScreen({super.key});
  @override
  ConsumerState<ReservationFormScreen> createState() => _ReservationFormScreenState();
}
class _ReservationFormScreenState extends ConsumerState<ReservationFormScreen> {
  TimeOfDay? _selectedTime;  // Contient l'heure sélectionné.
  int? _NbrConviveAffichee;
  DateTime?  _dateAffichee;

  @override
  Widget build(BuildContext context) {
    final clientStateAsync = ref.watch(clientStateProvider);
    // ref.read(clientStateProvider.notifier).getAvailableBookingtTimes(reservationId).
    final reservationId = ModalRoute.of(context)!.settings.arguments as int;
    // .value => extraire l'objet ClientStateData de la boîte  AsyncNotifierProvider/AsyncValue<ClientStateData>.
    final clientState = ref.watch(clientStateProvider).value;
    // Si le state est null (pendant le chargement des datas).
    if(clientState == null){ return CircularProgressIndicator();}
    final reservationCurrent = clientState.findReservation(reservationId);
    if(_dateAffichee == null){
      _dateAffichee = clientState.getDateReservation(reservationCurrent!).copyWith();
    }
    final dateReservationConst = clientState.getDateReservation(reservationCurrent!); // Datetime est immmuable en Flutter.
    // le ! de reservationCurrent! TODO
    final services = clientState.servicesDujour(reservationCurrent!, _dateAffichee!.weekday);  // weekday => obetenir le jour de la semaine à partir d'un DateTime.
    var restaurant =  clientState.getRestaurantReservation(reservationCurrent);
    if(_NbrConviveAffichee == null){
      _NbrConviveAffichee = clientState.getNbrConviveReservation(reservationCurrent.numberOfGuests);
    }
    //var NbrConviveAffichee = clientState.getNbrConviveReservationAffichee(reservationCurrent.numberOfGuests);
    final  List<TimeOfDay> ListAvailableBookingtTimes = clientState.getAvailableBookingTimes(services,reservationCurrent,dateReservationConst,_dateAffichee!);
    if(_selectedTime == null && ListAvailableBookingtTimes.isNotEmpty ){
      _selectedTime = ListAvailableBookingtTimes.first; // Par défaut on séléectionne la première heure de la liste.
    }
    print(ListAvailableBookingtTimes);
    final theme = Theme.of(context);
    final simulatedTime = DateTime(2024, 12, 4, 16, 0);
    // Les heures de réservation disponible pour le jour de la réservation.
    // final List<TimeOfDay> ListAvailableBookingtTimes = ref.read(clientStateProvider.notifier).getAvailableBookingtTimes2(reservationId,reservationCurrent.timestamp.weekday);

    return Scaffold(
      appBar: AppBar(
        leading: IconButton(
          icon: const Icon(Icons.arrow_back),
          onPressed: () => Navigator.pop(context),
        ),
        title: const Text('Nouvelle réservation'),
        automaticallyImplyLeading: false,
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            tooltip: 'Rafraîchir les données',
            onPressed: () {},
          ),
        ],
        elevation: 2,
        backgroundColor: theme.colorScheme.primary,
        foregroundColor: theme.colorScheme.onPrimary,
        surfaceTintColor: Colors.transparent,
        flexibleSpace: Align(
          alignment: Alignment.topCenter,
          child: SafeArea(
            child: Padding(
              padding: const EdgeInsets.only(top: 2.0),
              child: Tooltip(
                message:
                'Date/heure simulée utilisée pour les tests.\nCliquez pour modifier.',
                child: Text(
                  DateFormat('EEEE dd/MM/yyyy HH:mm', 'fr_FR')
                      .format(simulatedTime),
                  style: TextStyle(
                    fontSize: 10,
                    color: Colors.grey[400],
                    fontWeight: FontWeight.normal,
                  ),
                ),
              ),
            ),
          ),
        ),
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(16.0),
          child: Form(
            autovalidateMode: AutovalidateMode.onUserInteraction,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                Text(
                  restaurant.name,
                  style: TextStyle(
                    fontSize: 20,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 24),
                Row(
                  children: [
                    Expanded(
                      child: Material(
                        color: Colors.transparent,
                        child: InkWell(
                          onTap: () {},
                          child: Padding(
                            padding: const EdgeInsets.symmetric(vertical: 8.0),
                            child: Row(
                              children: [
                                const Icon(Icons.calendar_today),
                                const SizedBox(width: 16),
                                Expanded(
                                  child: Column(
                                    crossAxisAlignment:
                                    CrossAxisAlignment.start,
                                    mainAxisSize: MainAxisSize.min,
                                    children: [
                                      const Text('Date'),
                                      Text(
                                        _dateAffichee.toString(),
                                        style: theme.textTheme.bodySmall,
                                      ),
                                    ],
                                  ),
                                ),
                                const Icon(Icons.chevron_right),
                              ],
                            ),
                          ),
                        ),
                      ),
                    ),
                    const SizedBox(width: 8),
                    IconButton(
                      icon: const Icon(Icons.remove),
                      onPressed: () {
                        setState(() { // Force Flutter à relancer build() depuis le début.
                          print('avant :');
                          print(dateReservationConst);
                          print('------------ _dateAffiché');
                          print(_dateAffichee);
                          if(clientState.compareDateTimeTo(dateReservationConst, _dateAffichee!) < 0){
                            // je dois réasigner la varialbe car Datetime est immuable.
                            _dateAffichee = clientState.subtrDaysToDateTime(_dateAffichee!, 1);
                          }
                          print('après :');
                          print(dateReservationConst);
                          print('------------ _dateAffiché');
                          print(_dateAffichee);
                        });
                      },
                    ),
                    IconButton(
                      icon: const Icon(Icons.add),
                      onPressed: () {
                        setState(() { // Force Flutter à relancer build() depuis le début.
                          // je dois réasigner la varialbe car Datetime est immuable.
                          _dateAffichee = clientState.addDaysToDateTime(_dateAffichee!, 1);

                        });
                      },
                    ),
                    const SizedBox(width: 16),
                  ],
                ),
                const Divider(),
                if(ListAvailableBookingtTimes.isEmpty)
                Container(
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: Colors.orange.shade50,
                    borderRadius: BorderRadius.circular(8),
                    border: Border.all(color: Colors.orange),
                  ),
                  child: Row(
                    children: [
                      const Icon(Icons.warning, color: Colors.orange),
                      const SizedBox(width: 8),
                      const Expanded(
                        child: Text(
                          'Le restaurant est fermé ce jour-là.',
                          style: TextStyle(
                            color: Colors.orange,
                            fontSize: 13,
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 24),
                const Text(
                  'Créneaux disponibles',
                  style: TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.w600,
                  ),
                ),
                const SizedBox(height: 12),
                Wrap(
                  spacing: 8.0,
                  runSpacing: 8.0,
                  children : ListAvailableBookingtTimes.map((hour){ // map va tranformer List<TimeOfDay> en List<Widget>
                    final bool isSelected = _selectedTime == hour;
                    final String labelHour = hour.format(context);
                    return InkWell(
                      onTap: (){
                        setState(() { // Force Flutter à relancer build() depuis le début.
                          _selectedTime = hour;
                        });

                      },
                      child : Container(
                        width: 80,
                        padding: const EdgeInsets.symmetric(
                          horizontal: 12,
                          vertical: 8,
                        ),
                        decoration: BoxDecoration(
                          color: isSelected ? theme.colorScheme.primary : theme.colorScheme.surface,
                          borderRadius: BorderRadius.circular(16),
                          border: Border.all(
                            color: isSelected ? theme.colorScheme.primary : theme.colorScheme.outline,
                            width: 1,
                          ),
                        ),
                        child: Center(
                          child: Text(
                            labelHour,
                            style: TextStyle(
                              color: isSelected ? Colors.white : theme.colorScheme.onSurface,
                              fontSize: 14,
                              fontWeight: isSelected ? FontWeight.w600 : FontWeight.normal,
                            ),
                          ),
                        ),
                      ),
                    );
                  }).toList(),
                ),
                const SizedBox(height: 24),
                ListTile(
                  leading: const Icon(Icons.people),
                  title: const Text('Nombre de convives'),
                  subtitle:  Text(_NbrConviveAffichee.toString()),
                  trailing: Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      IconButton(
                        icon: const Icon(Icons.remove),
                        onPressed: () {
                          setState(() { // Force Flutter à relancer build() depuis le début.
                            if(_NbrConviveAffichee! > 0){
                              _NbrConviveAffichee = (_NbrConviveAffichee! -1);
                              print(_NbrConviveAffichee);
                            }
                          });

                        },
                      ),
                      IconButton(
                        icon: const Icon(Icons.add),
                        onPressed: () {
                          setState(() { // Force Flutter à relancer build() depuis le début.
                            _NbrConviveAffichee = (_NbrConviveAffichee! +1);
                            print(_NbrConviveAffichee);
                          });
                        },
                      ),
                    ],
                  ),
                ),
                Padding(
                  padding: const EdgeInsets.only(top: 16.0),
                  child: Container(
                    padding: const EdgeInsets.all(12),
                    decoration: BoxDecoration(
                      color: Colors.orange.shade50,
                      borderRadius: BorderRadius.circular(8),
                      border: Border.all(color: Colors.orange),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          children: [
                            const Icon(Icons.warning, color: Colors.orange),
                            const SizedBox(width: 8),
                            const Expanded(
                              child: Text(
                                'Surréservation (capacité)',
                                style: TextStyle(
                                  color: Colors.orange,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 4),
                        const Text(
                          'La capacité du restaurant pour ce créneau pourrait être insuffisante pour le nombre de convives demandé.',
                          style: TextStyle(fontSize: 13),
                        ),
                      ],
                    ),
                  ),
                ),
                const SizedBox(height: 16),
                TextFormField(
                  decoration: const InputDecoration(
                    labelText: 'Demandes spéciales (optionnel)',
                    border: OutlineInputBorder(),
                    hintText: 'Allergies, préférences...',
                  ),
                  maxLines: 3,
                ),
                const SizedBox(height: 32),
                ElevatedButton.icon(
                  onPressed: () {},
                  style: ElevatedButton.styleFrom(
                    padding: const EdgeInsets.symmetric(vertical: 16),
                  ),
                  icon: const Icon(Icons.add),
                  label: const Text('Créer la réservation'),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

