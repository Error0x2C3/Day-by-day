import 'dart:convert';
import '../core/services/api_client.dart';

class Table {
  final int id;
  final int tableNumber;
  final int capacity;
  final bool? isCurrentlyAssigned;
  final bool isAvailable;

  Table({
    required this.id,
    required this.tableNumber,
    required this.capacity,
    this.isCurrentlyAssigned = false,
    this.isAvailable = true, // Vrai par défaut.
  });

  factory Table.fromJson(Map<String, dynamic> json) {
    return Table(
      id: json['id'],
      tableNumber: json['table_number'],
      capacity: json['capacity'],
      isCurrentlyAssigned: json['is_currently_assigned'],
      isAvailable:  json['is_available'] ?? true,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'table_number': tableNumber,
      'capacity': capacity,
      'isAvailable': isAvailable
    };
  }

  Table copyWith({
    int? id,
    int? tableNumber,
    int? capacity,
    bool? isCurrentlyAssigned,
    bool?  isAvailable}) {
    return Table(
      id: id ?? this.id,
      tableNumber: tableNumber ?? this.tableNumber,
      capacity: capacity ?? this.capacity,
      isCurrentlyAssigned: isCurrentlyAssigned ?? this.isCurrentlyAssigned,
      isAvailable: isAvailable ?? this.isAvailable
    );
  }

  static Future<List<Table>> getAvailableTablesForAssignment(int reservationId) async {
    final response = await ApiClient.post(
      'get_available_tables_for_assignment',
      body: json.encode({
        'reservation_id': reservationId
      }),
    );
    if (response.statusCode == 200){
      // response.body: Réponse brute du serveur, longue chaîne de caractères Ex: "[{"id":1, "table_number":5}, {...}]".
      // List<dynamic> => "Je m'attends à recevoir une liste, mais je ne sais pas encore exactement ce qu'il y a dedans".
      final List<dynamic> body = json.decode(response.body);
      // body.map(...): Parcourt chaque élément de la liste (un par un).
      // Map<String, dynamic>:un élément de la liste est une Map.
      // Table.fromJson: chaque élément (Map) de la list est à mettre dans une instance model.table
      return body.map((dynamic item) => Table.fromJson(item as Map<String, dynamic>)).toList(); // .toList() =>  List<Table>.
    }else{
      throw Exception('Échec du chargement des créneaux : ${response.statusCode}');
    }
  }

  @override
  String toString() {
    return '(id: $id, tableNumber: $tableNumber, capacity: $capacity), isAvailable: $isAvailable)';
  }
}