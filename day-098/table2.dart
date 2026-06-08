import 'dart:convert';

import 'package:prbd_2526_f02/models/table.dart' as model;

import '../core/services/api_client.dart';

class Table {
  final int id;
  final int tableNumber;
  final int capacity;

  Table({
    required this.id,
    required this.tableNumber,
    required this.capacity,
  });

  factory Table.fromJson(Map<String, dynamic> json) {
    return Table(
      id: json['id'],
      tableNumber: json['table_number'],
      capacity: json['capacity'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'table_number': tableNumber,
      'capacity': capacity,
    };
  }

  Table copyWith({
    int? id,
    int? tableNumber,
    int? capacity}) {
    return Table(
      id: id ?? this.id,
      tableNumber: tableNumber ?? this.tableNumber,
      capacity: capacity ?? this.capacity,
    );
  }

  static Future<List<Table>> getAvailableTablesForAssignment(reservationId) async {
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
    return '(id: $id, tableNumber: $tableNumber, capacity: $capacity)';
  }
}