import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:prbd_2526_f02/pages/widgets/top_nav_bar.dart';
import '../models/reservation.dart';
import '../models/table.dart' as model;
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
    final state = ref.watch(managerStateProvider);
    return state.when(
        loading: () => const Scaffold(
            body: Center(child: CircularProgressIndicator()), // Rond qui tourne au centre
        ),
      error: (err, stack) => Scaffold(
          body: Center(child: Text("Erreur : $err")),
      ),
        data: (state) {
          final reservation = widget.reservation;
          final tables = state.availableTables ?? [];  // Listes des tables vénant du Provider.
          // On calcule la capacité totale des tables cochées.
          // fold :
          // Je parcours tales, si elle est dans  _selectedTableIds, je fais :
          // fold(...): base sum =0 et (sum, t) => sum += sum + t.capacity.
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
                  // Si aucune table n'est choché, on n'affiche aucune infobulle
                  // Sinon on affiche un message informatif pour les capcités.
                  if(_selectedTableIds.isNotEmpty)
                    _buildCapacityInfo(totalCapacity, reservation.numberOfGuests),
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
                    child: tables.isEmpty
                      ? _buildEmptyState() // Si aucune table n'existe.
                       : _buildTablesList(tables), // Si on a des tables.
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
    );
  }

  // Appelée lorsqu'on clique sur le btn.
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

  // Si si la capcité des tables séléctionnée >= nbr de convives de la réservation.
  Widget _buildCapacityInfo(int totalCapacity, int guestsCount) {
    final bool isSufficient = totalCapacity >= guestsCount;
    final color = isSufficient ? Colors.green : Colors.orange;
    return
      Card(
        color: color.shade50,
        elevation: 0,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Row(
            children: [
              Icon(
                isSufficient ? Icons.check_circle : Icons.warning_rounded,
                color: color[700],
                size: 28,
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                       'Capacité totale: $totalCapacity ${totalCapacity > 1 ? "places":"place"}',
                       style: const TextStyle(
                         fontSize: 16,
                         fontWeight: FontWeight.bold,
                       ),
                    ),
                     const SizedBox(height: 4),
                    Row(
                      children: [
                        if (!isSufficient)
                          Icon(Icons.report_problem, size: 14, color: color[700]),
                        if (!isSufficient) const SizedBox(width: 4),
                        Text(
                          isSufficient
                              ? '✓ Capacité suffisante pour $guestsCount ${guestsCount > 1 ? "convives" : "convive"}'
                              : 'Capacité insuffisante: $totalCapacity ${totalCapacity > 1 ? "places" : "place"} pour $guestsCount ${guestsCount > 1 ? "convives" : "convive"}',
                          style: TextStyle(
                            fontSize: 14,
                            color: color.shade700,
                            fontWeight: FontWeight.w500,
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      );
  }

  // Widget pour le message s'il y a des tables.
  Widget _buildTablesList(List<model.Table> tables) {
    return ListView.builder(
      padding: const EdgeInsets.symmetric(horizontal: 16), // Espacement sur les côtés.
      itemCount: tables.length,
        itemBuilder: (context, index) {
          final table = tables[index];
          return Padding(
            padding: const EdgeInsets.symmetric(vertical: 2.0), // Espacement entre les cartes.
            child: Card(
              elevation: 0,
              // Couleur de fond légèrement différente si disponible ou non.
              color: table.isAvailable ? Colors.white :Colors.grey[50] ,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(9), // Bords très arrondis comme sur l'image
                side: BorderSide(color: Colors.grey.shade200), // Bordure légère
              ),
              // Liste des tables sous forme de checkbox.
              child: CheckboxListTile(
                contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
                title: Text(
                  'Table ${table.tableNumber}',
                  style: TextStyle(
                    fontWeight: FontWeight.w500,
                    // STYLE BARRÉ : Appliqué uniquement si indisponible.
                    decoration: table.isAvailable ? null : TextDecoration.lineThrough,
                    color: table.isAvailable ? Colors.black87 : Colors.grey,
                  ),
                ),
                subtitle: table.isAvailable
                    ?  Text('Capacité: ${table.capacity} ${table.capacity > 1 ? 'personnes' : 'personne'}')
                    :  const Text(
                        'Occupée pour ce service',
                        style: TextStyle(
                          color: Colors.red, // TEXTE EN ROUGE.
                          fontSize: 13,
                        ),
                       ),
                value :  _selectedTableIds.contains(table.id), // Si c'est true alors la table est coché en bleu.
                // Si la table est disponible on peut cliquer dessus sinon on ne peut pas.
                onChanged: table.isAvailable
                    ? (bool? checked) {
                        setState(() {
                          if(checked == true){
                            _selectedTableIds.add(table.id);
                          }else{
                            _selectedTableIds.remove(table.id);
                          }
                        });
                      }
                    : null,
                  secondary: Icon(
                    Icons.table_restaurant,
                    // Icône grisée si indisponible.
                    color: table.isAvailable ? Colors.black87 : Colors.grey[300],
                  ),
                  // Supprime la couleur de sélection par défaut pour garder le style propre.
                  activeColor: Colors.blueAccent,
                  checkboxShape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(2)),
              )
            )
          );
        },
    );
  }
  // Widget pour le message si aucune table n'existe.
  Widget _buildEmptyState() {
    return const Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.table_bar_outlined, size: 64, color: Colors.grey),
            SizedBox(height: 16),
            Text(
                'Aucune table configurée pour ce restaurant.',
                style: TextStyle(color: Colors.grey, fontSize: 16),
            ),
          ],
        ),
    );
  }
}
