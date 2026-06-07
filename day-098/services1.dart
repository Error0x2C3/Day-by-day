
import 'dart:convert';

import 'package:intl/intl.dart';
import 'package:prbd_2526_f02/core/services/api_client.dart';
import 'package:prbd_2526_f02/models/restaurant.dart';

class Services {
  final int id;
  final Restaurant restaurant;
  final int dayOfWeek;
  final DateTime startTime;
  final DateTime endTime;

  Services({
    required this.id,
    required this.restaurant,
    required this.dayOfWeek,
    required this.startTime,
    required this.endTime
  });

  factory Services.fromJson(Map<String, dynamic> json){
    return Services(
        id: json['id'],
        restaurant: Restaurant.fromJson(json['restaurant']),
        dayOfWeek: json['day_of_week'],
        startTime: DateFormat('HH:mm:ss').parse(json['start_time']),
        endTime: DateFormat('HH:mm:ss').parse(json['end_time']),
    );
  }

  Map<String, dynamic> toJson(){
    return{
      'id': id,
      'restaurant': restaurant.toJson(),
      'day_of_week': dayOfWeek,
      'start_time': startTime.toIso8601String(),
      'end_time': endTime.toIso8601String(),
    };
  }

  Services copyWith({
    int? id,
    Restaurant? restaurant,
    int? dayOfWeek,
    DateTime? startTime,
    DateTime? endTime}){

    return Services(
        id: id ?? this.id,
        restaurant: restaurant ?? this.restaurant,
        dayOfWeek: dayOfWeek ?? this.dayOfWeek,
        startTime: startTime ?? this.startTime,
        endTime: endTime ?? this.endTime
    );
  }

  // Liste de tous les services.
  static Future<List<Services>> getServices() async{
    final response = await ApiClient.get('get_services');

    if (response.statusCode == 200) {
      final List<dynamic> body = json.decode(response.body);
      // On convertit tout le Json recu en une liste d'objets de type Services.
      return body.map((dynamic item) => Services.fromJson(item)).toList();
    } else {
      throw Exception('Échec du chargement des services : ${response.statusCode}');
    }
  }
  @override
  String toString() {
    return 'Service(id: $id, restaurant: $restaurant, dayOfWeek: $dayOfWeek, start_time: $startTime, end_time: $endTime)\n';
  }
}