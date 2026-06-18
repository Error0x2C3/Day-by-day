import 'dart:convert';
import 'package:prbd_2526_f02/core/services/api_client.dart';
import 'Restaurant.dart';
import 'table.dart';
import 'user.dart';


class Reservation {
  final int id;
  final int client;
  final DateTime timestamp;
  final int numberOfGuests;
  final String status;
  final String? specialRequests;
  final String? restaurantCity;
  final String restaurantName;
  final String? restaurantAddress;
  final String? restaurantPhone;
  final String? ownerName;
  final User? owner;
  final List<Table>? tables;


  Reservation({
    required this.id,
    required this.client,
    required this.timestamp,
    required this.numberOfGuests,
    required this.status,
    this.specialRequests,
    this.restaurantCity,
    required this.restaurantName,
    this.restaurantAddress,
    this.restaurantPhone,
    this.ownerName,
    this.owner,
    this.tables
  });

  // FromJson c'est de json à modèle, Ex: transformer json en instance de réservation.
  factory Reservation.fromJson(Map<String, dynamic> json){
    return Reservation(
      id: json['id'],
      client: json['client'],
      timestamp: DateTime.parse(json['timestamp']),
      numberOfGuests: json['number_of_guests'],
      status: json['status'],
      specialRequests: json['special_requests'],
      restaurantCity: json['restaurant_city'],
      restaurantName: json['restaurant_name'],
      restaurantAddress: json['restaurant_address'],
      restaurantPhone: json['restaurant_phone'],
      ownerName: json['owner_name'],
      owner: json['owner'] != null
          ? User.fromJson(json['owner'] as Map<String, dynamic>)
          : null,
      tables: json['tables'] != null
          ? (json['tables'] as List)
          .map((tableJson) => Table.fromJson(tableJson as Map<String, dynamic>))
          .toList()
          : null,
    );
  }

  // De modèle à json, Ex: transforme une instance en json.
  Map<String, dynamic> toJson(){
    return{
      'id': id,
      'client': client,
      'timestamp': timestamp.toIso8601String(),
      'number_of_guests': numberOfGuests,
      'status': status,
      'special_requests': specialRequests,
      'restaurant_city': restaurantCity,
      'restaurant_name': restaurantName,
      'restaurant_address': restaurantAddress,
      'restaurant_phone': restaurantPhone,
      'owner_name': ownerName,
      'owner': owner,
      'tables': tables?.map((table) => table.toJson()).toList(),
    };
  }
  Reservation copyWith({
    int? id,
    int? client,
    DateTime? timestamp,
    int? numberOfGuests,
    String? status,
    String? specialRequests,
    String? restaurantCity,
    String? restaurantName,
    String? restaurantAddress,
    String? restaurantPhone,
    String? ownerName,
    User? owner,
    List<Table>? tables,
  }){
    return Reservation(id: id ?? this.id,
                        client: client ?? this.client,
                        timestamp: timestamp ?? this.timestamp,
                        numberOfGuests: numberOfGuests ?? this.numberOfGuests,
                        status: status ?? this.status,
                        specialRequests: specialRequests ?? this.specialRequests,
                        restaurantCity: restaurantCity ?? this.restaurantCity,
                        restaurantName: restaurantName ?? this.restaurantName,
                        restaurantAddress: restaurantAddress ?? this.restaurantAddress,
                        restaurantPhone: restaurantPhone ?? this.restaurantPhone,
                        ownerName: ownerName ?? this.ownerName,
                        owner: owner ?? this.owner,
                        tables: tables ?? this.tables,
    );
  }

  static Future<List<Reservation>> getReservations() async {
    final response = await ApiClient.get('get_reservations_client');

    if (response.statusCode == 200) {
      final List<dynamic> body = json.decode(response.body);
      return body.map((dynamic item) => Reservation.fromJson(item)).toList();

    } else {
      throw Exception('Échec du chargement des réservations : ${response.statusCode}');
    }
  }

  static Future<void> completedReservation(int reservationId)async{
    final response = await ApiClient.post(
      'completed_reservation',
      body: json.encode({'reservation_id': reservationId}),
    );

    if(response.statusCode != 204) {
      print("Détails de l'erreur: ${response.body}");
      throw Exception('Échec de la confirmation de la réservation : ${response.statusCode}');
    }
  }

  static Future<void> cancelReservation(int reservationId) async {
    final response = await ApiClient.post(
      'cancel_reservation',
      body: json.encode({'reservation_id': reservationId}),
    );

    if(response.statusCode != 204) {
      throw Exception('Échec de l''anulation de la réservation : ${response.statusCode}');
    }
  }

  static Future<Reservation> getReservationById(int reservationId) async {
    final response = await ApiClient.get('get_reservation_client?reservation_id=$reservationId');

    if (response.statusCode == 200) {
      final dynamic body = json.decode(response.body);
      return Reservation.fromJson(body);

    } else {
      throw Exception('Échec du chargement des réservations via id: ${response.statusCode}');
    }
  }

  // fonction pour obtenir la liste des réservations pour le réstaurant d'un manager
  static Future<List<Reservation>> getReservationsByIdManager(int restaurantId) async {
    final response = await ApiClient.get('get_reservations_manager?restaurant_id=$restaurantId');

    if (response.statusCode == 200) {
      final List<dynamic> body = json.decode(response.body);
      return body.map((dynamic item) => Reservation.fromJson(item)).toList();

    } else {
      throw Exception('Échec du chargement des réservations via id pour le manager: ${response.statusCode}');
    }
  }

  // fonction pour les details d'un réservation coté manager
  static Future<Reservation> getReservationByIdManager(int reservationId) async {
    final response = await ApiClient.get('get_reservation_manager?reservation_id=$reservationId');

    if (response.statusCode == 200) {
      final dynamic body = json.decode(response.body);
      return Reservation.fromJson(body);

    } else {
      throw Exception('Échec du chargement des réservations via id pour le manager: ${response.statusCode}');
    }
  }

  static Future<List<String>> fetchAvailableSlots(int restaurantId, DateTime date, {int? reservationId}) async {
    final dateString = "${date.year}-${date.month.toString().padLeft(2, '0')}-${date.day.toString().padLeft(2,'0')}";
    String endpoint = reservationId == null
        ? 'get_available_slots?restaurantid=$restaurantId&datechosen=$dateString' :
    'get_available_slots?restaurantid=$restaurantId&datechosen=$dateString&reservationid=$reservationId';
    final response = await ApiClient.get(endpoint);
    if (response.statusCode == 200){
      final List<dynamic> data = json.decode(response.body);
      return data.map((item) => item['time'] as String).toList();
    }else{
    throw Exception('Échec du chargement des créneaux : ${response.statusCode}');
    }
  }

  static Future<int> getAvailableCapacity(int restaurantId, DateTime vDateTime, {int? reservationId}) async {
    String endpoint = reservationId == null ?
    'get_available_capacity?restaurantid=$restaurantId&p_datetime=${vDateTime.toIso8601String()}' :
    'get_available_capacity?restaurantid=$restaurantId&p_datetime=${vDateTime.toIso8601String()}&reservationid=$reservationId';
    final response = await ApiClient.get(endpoint);

    if (response.statusCode == 200){
      return json.decode(response.body);
    }else{
      throw Exception('Échec du chargement des capacités : ${response.statusCode}');
    }
  }

  static Future<void> createReservation(int restaurantId, DateTime vDateTime, int guests, String requests) async {
    final response = await ApiClient.post(
      'create_reservation',
      body: json.encode({
        'restaurantid': restaurantId,
        'p_datetime': vDateTime.toIso8601String(),
        'guests': guests,
        'requests': requests.isEmpty ? null : requests,
      }),
    );
    if (response.statusCode != 200  && response.statusCode != 204){
      throw Exception('Échec de la création : ${response.body}');
    }
  }

  static Future<void> updateReservation(int reservationId, DateTime newDatetime, int guests, String requests) async {
    final response = await ApiClient.post(
      'update_reservation',
      body: json.encode({
        'reservation_id': reservationId,
        'new_datetime': newDatetime.toIso8601String(),
        'new_guests': guests,
        'new_requests': requests.isEmpty ? null : requests,
      }),
    );
    if (response.statusCode != 200  && response.statusCode != 204){
      throw Exception('Échec de la création : ${response.body}');
    }
  }

  static Future<Map<String, dynamic>> getFormContext(int restaurantId, DateTime date , {int? reservationId}) async {
    final dateString = "${date.year}-${date.month.toString().padLeft(2, '0')}-${date.day.toString().padLeft(2,'0')}";

    String endpoint = reservationId == null
        ? 'get_form_context?restaurantid=$restaurantId&date=$dateString'
        : 'get_form_context?restaurantid=$restaurantId&date=$dateString&reservationid=$reservationId';

    final response = await ApiClient.get(endpoint);

    if(response.statusCode == 200){
      return json.decode(response.body);
    }else{
      throw Exception('Échec du chargement du contexte : ${response.statusCode}');
    }
  }

  static Future<void> updateReservationEdition(int reservationId, DateTime newDatetime, int guests, String requests) async {
    final response = await ApiClient.post(
      'update_reservation_edition',
      body: json.encode({
        'reservation_id': reservationId,
        'new_datetime': newDatetime.toIso8601String(),
        'new_guests': guests,
        'new_requests': requests.isEmpty ? null : requests,
      }),
    );
    if (response.statusCode != 200  && response.statusCode != 204){
      throw Exception('Échec de la création : ${response.body}');
    }
  }

  static Future<Reservation> confirmAndAssignTables(int reservationId, List<int> tableIds) async {
    final response = await ApiClient.post(
      'confirm_reservation_with_tables',
      body: json.encode({
        'reservation_id': reservationId,
        'table_ids': tableIds,
      }),
    );
    if (response.statusCode == 200) {
      final dynamic body = json.decode(response.body);
      return Reservation.fromJson(body);
    } else {
      throw Exception('Échec du chargement des réservations via id: ${response.statusCode}');
    }
  }

  // Donne le restaurant liée à la réseration.
  static Future<void> getRestaurantByIdReservation(int reservationId) async {
    final response = await ApiClient.post(
      'get_restaurant_by_reservation',
      body: json.encode({
        'reservation_id': reservationId,
      }),
    );
    if (response.statusCode != 200  && response.statusCode != 204){
      throw Exception('Échec de la création : ${response.body}');
    }
  }

  static Future<Restaurant> getRestaurantByReservation(int reservationId) async {
    // On utilise ApiClient.get avec le paramètre p_reservation_id
    final response = await ApiClient.get(
        'get_restaurant_by_reservation?reservation_id=$reservationId'
        );
    if (response.statusCode == 200) {
      // On décode le corps de la réponse (qui est un objet JSON unique).
      final dynamic body = json.decode(response.body);
      // On transforme le JSON en objet Restaurant via son constructeur fromJson.
        return Restaurant.fromJson(body);
    } else {
      // En cas d'erreur (404, 500, etc.), on lance une exception.
      throw Exception('Échec du chargement du restaurant pour la réservation $reservationId : ${response.statusCode}');
    }
  }
  @override
  String toString() {
    return 'Reservation(id: $id, '
        'client: $client, '
        'date: $timestamp, '
        'guests: $numberOfGuests, '
        'status: $status, '
        'requests: $specialRequests, '
        'restaurantName: $restaurantName, '
        'restaurantAddress: $restaurantAddress, '
        'restaurantPhone: $restaurantPhone, '
        'owner: $owner, '
        'tables: $tables)\n';
  }

}