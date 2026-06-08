import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/date_symbol_data_local.dart';
import 'package:intl/intl.dart';
import 'package:prbd_2526_f02/pages/widgets/top_nav_bar.dart';
import '../models/reservation.dart';
import '../providers/manager_state_provider.dart';

class AssignTablesScreen extends ConsumerStatefulWidget {
    final Reservation reservation; // On reçoit la réservation.
    const AssignTablesScreen({
      super.key,
      required this.reservation,
  });

  @override
  ConsumerState<AssignTablesScreen> createState() => _AssignTablesScreenState();
}


class _AssignTablesScreenState extends  ConsumerState<AssignTablesScreen> {
  // On stocke localement les Ids des tables cochées.
  final List<int> _selectedTableIds = [];
  @override
  void initState() {
    super.initState();
    // On demande au manager_provider de charger le détail de cette réservation
    // et la liste des tables disponibles pour ce restaurant dès l'ouverture.
    Future.microtask(() {
      ref.read(managerStateProvider.notifier).loadTablesForAssignment(widget.reservation.id);
    });
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(managerStateProvider).value;
    final reservation = widget.reservation;
    final tables = state?.availableTables ?? []; // Listes des tables vénant du Provider.
    // if (reservation == null) return const Scaffold(body: Center(child: CircularProgressIndicator()));
    // On calcule la capacité totale des tables cochées.
    final int totalCapacity =  tables.where((t) => _selectedTableIds.contains(t.id)).fold(0, (sum, t) => sum + t.capacity);
    final bool isCapacitySufficient = totalCapacity >= reservation.numberOfGuests;
    return Scaffold(
      appBar: TopNavBar(
        title: 'Assigner des tables',
        showBackButton: true,
      ),
      body: SafeArea(
        child: Column(
          children: [
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: Card(
                child: Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        'Réservation',
                        style: TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      const SizedBox(height: 8),
                      Text('${reservation.numberOfGuests.toString()} ${reservation.numberOfGuests > 0 ? "convives":"convive" }'),
                      Text('${reservation.timestamp.day}/${reservation.timestamp.month}/${reservation.timestamp.year} à ${reservation.timestamp.hour}:${reservation.timestamp.minute}'),
                    ],
                  ),
                ),
              ),
            ),
            Padding(
              // _buildCapacityInfo(reservation.numberOfGuests, totalCapacity, isCapacitySufficient),
              padding: const EdgeInsets.symmetric(horizontal: 16.0),
              child: Card(
                color: Colors.green.shade50,
                child: Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: Row(
                    children: [
                      Icon(
                        Icons.check_circle,
                        color: Colors.green[700],
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            const Text(
                              'Capacité totale: 6 places',
                              style: TextStyle(
                                fontSize: 16,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                            const SizedBox(height: 4),
                            Text(
                              '✓ Capacité suffisante pour 4 convives',
                              style: TextStyle(
                                fontSize: 14,
                                color: Colors.green.shade700,
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: Align(
                alignment: Alignment.centerLeft,
                child: Text(
                  'Sélectionnez les tables disponibles',
                  style: const TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
            ),
            Expanded(
              // Liste des tables sous forme de checkbox.
              child: ListView.builder(
                itemCount: tables.length,
                itemBuilder: (context, index) {
                  final table = tables[index];
                  return CheckboxListTile(
                    title: Text('Table ${table.tableNumber}'),
                    subtitle: Text('Capacité: ${table.capacity} ${table.capacity > 1 ? 'personnes':'personne' }'),
                    value: _selectedTableIds.contains(table.id),
                    onChanged: (bool? checked) {
                      setState(() {
                        if (checked == true) {
                          _selectedTableIds.add(table.id);
                        } else {
                          _selectedTableIds.remove(table.id);
                        }
                      });
                    },
                  );
                },
              ),
                // padding: const EdgeInsets.all(16.0),
                // children: [
                //   Card(
                //     margin: const EdgeInsets.only(bottom: 8),
                //     child: CheckboxListTile(
                //       value: true,
                //       onChanged: (_) {},
                //       title: const Text('Table 1'),
                //       subtitle: const Text(
                //         'Capacité: 2 personnes',
                //       ),
                //       secondary: const Icon(Icons.table_restaurant),
                //     ),
                //   ),
                //   Card(
                //     margin: const EdgeInsets.only(bottom: 8),
                //     child: CheckboxListTile(
                //       value: true,
                //       onChanged: (_) {},
                //       title: const Text('Table 2'),
                //       subtitle: const Text(
                //         'Capacité: 4 personnes',
                //       ),
                //       secondary: const Icon(Icons.table_restaurant),
                //     ),
                //   ),
                //   Card(
                //     margin: const EdgeInsets.only(bottom: 8),
                //     child: CheckboxListTile(
                //       value: false,
                //       onChanged: (_) {},
                //       title: const Text('Table 3'),
                //       subtitle: const Text(
                //         'Capacité: 6 personnes',
                //       ),
                //       secondary: const Icon(Icons.table_restaurant),
                //     ),
                //   ),
                //   Card(
                //     margin: const EdgeInsets.only(bottom: 8),
                //     color: Colors.grey.shade100,
                //     child: CheckboxListTile(
                //       value: false,
                //       onChanged: null,
                //       title: Text(
                //         'Table 4',
                //         style: TextStyle(
                //           color: Colors.grey.shade600,
                //           decoration: TextDecoration.lineThrough,
                //         ),
                //       ),
                //       subtitle: Text(
                //         'Occupée pour ce service',
                //         style: TextStyle(
                //           color: Colors.red.shade300,
                //         ),
                //       ),
                //       secondary: Icon(
                //         Icons.table_restaurant,
                //         color: Colors.grey.shade400,
                //       ),
                //     ),
                //   ),
                // ],
            ),

            Padding(
              padding: const EdgeInsets.all(16.0),
              child: SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: isCapacitySufficient ? () => _confirm(context) : null,
                  child: const Text('Confirmer la réservation'),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  // Si clique sur confirmé et assigné.
  void _confirm(BuildContext context) {
    ref.read(managerStateProvider.notifier).confirmWithTables(widget.reservation.id, _selectedTableIds);
    Navigator.pop(context); // Retour aux détails.
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text('Réservation confirmée avec les tables assignées'),
        backgroundColor: Colors.green,
      ),
    );
  }

  Widget _buildCapacityInfo(int guests, int total, bool sufficient) {
    // encadré vert/rouge pour la capacité.
    return Container( /* ... */ );
  }
}
