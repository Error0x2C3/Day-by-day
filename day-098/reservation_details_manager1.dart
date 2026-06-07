import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';
import 'package:prbd_2526_f02/pages/assign_tables.dart';
import 'package:prbd_2526_f02/pages/widgets/top_nav_bar.dart';
import 'package:prbd_2526_f02/providers/manager_state_provider.dart';

import '../providers/reference_time_provider.dart';


class ReservationDetailsManagerScreen extends ConsumerWidget {
  const ReservationDetailsManagerScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final managerStateAsync = ref.watch(managerStateProvider);
    final referenceTime = ref.watch(referenceTimeProvider);
    final reservation = managerStateAsync.value?.selectedReservation;
    final isEditable = reservation?.status == 'pending' || reservation?.status == 'confirmed';
    final isCompletedButonActive = referenceTime.isAfter(reservation!.timestamp);

    Color statusColor = Colors.orange;
    String statusText = 'En attente';

    switch (reservation.status) {
      case 'confirmed':
        statusColor = Colors.green;
        statusText = 'Confirmée';
        break;
      case 'cancelled':
        statusColor = Colors.red;
        statusText = 'Annulée';
        break;
      case 'completed':
        statusColor = Colors.blue;
        statusText = 'Terminée';
        break;
    }

    return Scaffold(
      appBar: TopNavBar(
        title: 'Détails de la réservation',
        showBackButton: true,
        onRefresh: () async {
          ref.read(managerStateProvider.notifier).getSelectedReservationByIdManager(reservation.id);
        },
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              Card(
                child: Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        'Informations client',
                        style: TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const SizedBox(height: 8),
                      Row(
                        children: [
                          const Icon(Icons.person, size: 20),
                          const SizedBox(width: 8),
                          Text('Nom: ${reservation.owner?.fullName}'),
                        ],
                      ),
                      const SizedBox(height: 8),
                      Row(
                        children: [
                          const Icon(Icons.email, size: 20),
                          const SizedBox(width: 8),
                          Text('Email: ${reservation.owner?.email}'),
                        ],
                      ),
                      const SizedBox(height: 8),
                      Row(
                        children: [
                          const Icon(Icons.phone, size: 20),
                          const SizedBox(width: 8),
                          Text(reservation.owner?.phone != null
                              ? 'Téléphone: ${reservation.owner!.phone}'
                              : 'Téléphone: Non renseigné',
                            style: TextStyle(color: Colors.grey[600]),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 16),
              Card(
                child: Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        'Détails de la réservation',
                        style: TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const SizedBox(height: 8),
                      Row(
                        children: [
                          const Icon(Icons.restaurant, size: 20),
                          const SizedBox(width: 8),
                          Expanded(
                            child: Text(
                              reservation.restaurantName,
                              style: const TextStyle(
                                fontSize: 16,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 16),
                      const Divider(),
                      const SizedBox(height: 16),
                      Row(
                        children: [
                          const Icon(Icons.calendar_today, size: 20),
                          const SizedBox(width: 8),
                          Text(
                            'Date: ${DateFormat('EEE dd/MM/yyyy', 'fr_FR').format(reservation.timestamp)}',
                          ),
                        ],
                      ),
                      const SizedBox(height: 8),
                      Row(
                        children: [
                          const Icon(Icons.access_time, size: 20),
                          const SizedBox(width: 8),
                          Text('Heure: ${DateFormat('HH:mm').format(reservation.timestamp)}'),
                        ],
                      ),
                      const SizedBox(height: 8),
                      Row(
                        children: [
                          const Icon(Icons.people, size: 20),
                          const SizedBox(width: 8),
                          Text('Nombre de convives: ${reservation.numberOfGuests}'),
                        ],
                      ),
                      const SizedBox(height: 8),
                      Row(
                        children: [
                          const Icon(Icons.info, size: 20),
                          const SizedBox(width: 8),
                          const Text('Statut: '),
                          Chip(
                            label: Text(
                              statusText,
                              style: TextStyle(
                                fontSize: 12,
                                color: Colors.white,
                              ),
                            ),
                            backgroundColor: statusColor,
                            padding: EdgeInsets.zero,
                          ),
                        ],
                      ),
                      if (reservation.specialRequests != null) ...[
                        const SizedBox(height: 16),
                        const Divider(),
                        const SizedBox(height: 8),
                        const Text(
                          'Demandes spéciales',
                          style: TextStyle(
                            fontSize: 14,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          reservation.specialRequests!,
                          style: TextStyle(color: Colors.grey[700]),
                        ),
                      ],
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 16),
              const Text(
                'Tables assignées',
                style: TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: 8),
              Card(
                child: Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      if (reservation.tables != null && reservation.tables!.isNotEmpty) ...[
                        for (var table in reservation.tables!)
                          Padding(
                            padding: const EdgeInsets.only(bottom: 8.0),
                            child: Row(
                              children: [
                                const Icon(Icons.table_restaurant, size: 20, color: Colors.black87),
                                const SizedBox(width: 8),
                                Text('Table ${table.tableNumber}'),
                                const SizedBox(width: 16),
                                Text(
                                  '(${table.capacity} places)',
                                  style: TextStyle(color: Colors.grey[600]),
                                ),
                              ],
                            ),
                          ),
                      ] else ...[
                        Text(
                          'Aucune table assignée (en attente de confirmation)',
                        ),
                      ],
                    ],
                  ),
                ),
              ),
              if(isEditable) ...[
                const SizedBox(height: 24),
                // ElevatedButton.icon(
                //   onPressed: isCompletedButonActive ? () {
                //     _showDialogBoxConfirmed(context, ref, reservation.id);
                //   } : null,
                //   icon: const Icon(Icons.check_circle),
                //   label: const Text('Marquer comme terminée'),
                // ),
                if (reservation.status == 'pending')
                  ElevatedButton.icon(
                     onPressed: () {
                       Navigator.push(
                         context,
                         MaterialPageRoute(
                           builder: (context) => AssignTablesScreen(
                             reservationId: reservation.id,
                           ),
                         ),
                       );
                     },
                     icon: const Icon(Icons.table_restaurant),
                     label: const Text('Confirmer et assigner tables'),
                   ),
                const SizedBox(height: 8),
                OutlinedButton.icon(
                  onPressed: () => _showDialogBoxCancel(context, ref, reservation.id),
                  icon: const Icon(Icons.cancel),
                  label: const Text('Annuler'),
                ),
              ]
            ],
          ),
        ),
      ),
    );
  }

  Future<void> _showDialogBoxConfirmed(BuildContext context, WidgetRef ref, int reservationId) {
    return showDialog(
      context: context,
      barrierDismissible: false,
      builder: (context) =>
          AlertDialog(
            title: const Text('Terminer la réservation'),
            content: const Text(
              'Voulez-vous marquer cette réservation comme terminée ?',
            ),
            actions: [
              TextButton(
                onPressed: () => Navigator.pop(context),
                child: const Text('Non'),
              ),
              TextButton(
                style: TextButton.styleFrom(
                  foregroundColor: Colors.deepOrange,
                ),
                onPressed: () {
                  ref.read(managerStateProvider.notifier).confirmReservationManager(reservationId);
                  Navigator.pop(context);

                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(
                      content: Text("Réservation confirmée avec succès."),
                      backgroundColor: Colors.blueGrey,
                      duration: Duration(seconds: 3),
                    ),
                  );
                },
                child: const Text('Oui'),
              ),
            ],
          ),
    );
  }

  Future<void> _showDialogBoxCancel(BuildContext context, WidgetRef ref, int reservationId) {
    return showDialog(
      context: context,
      barrierDismissible: false,
      builder: (context) =>
          AlertDialog(
            title: const Text('Annuler la réservation'),
            content: const Text(
              'Êtes-vous sûr de vouloir annuler cette réservation ?',
            ),
            actions: [
              TextButton(
                onPressed: () => Navigator.pop(context),
                child: const Text('Non'),
              ),
              TextButton(
                style: TextButton.styleFrom(
                  foregroundColor: Colors.deepOrange,
                ),
                onPressed: () {
                  ref.read(managerStateProvider.notifier).cancelReservationsManager(reservationId);
                  Navigator.pop(context);

                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(
                      content: Text("Réservation annulée avec succès."),
                      backgroundColor: Colors.blueGrey,
                      duration: Duration(seconds: 3),
                    ),
                  );
                },
                child: const Text('Oui'),
              ),
            ],
          ),
    );
  }
}
