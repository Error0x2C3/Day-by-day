import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:prbd_2526_f02/Pages/home_client.dart';
import 'package:prbd_2526_f02/Pages/login.dart';
import 'package:prbd_2526_f02/Pages/signup.dart';
import 'package:prbd_2526_f02/pages/home_manager.dart';
import 'package:prbd_2526_f02/pages/reservation_form.dart';
import 'package:prbd_2526_f02/pages/restaurant_details.dart';
import 'package:prbd_2526_f02/pages/restaurant_manager_reservations.dart';
import '../models/reservation.dart';
import '../pages/assign_tables.dart';
import '../pages/reservation_details_client.dart';
import '../pages/reservation_details_manager.dart';
import '../pages/search_restaurants.dart';
import '../providers/current_user_provider.dart';

class MyApp extends ConsumerWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final securityNotifier = ref.read(currentUserProvider.notifier);

    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'frontend',
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: Colors.blue),
        useMaterial3: true,
      ),
      initialRoute: securityNotifier.isLoggedIn ?
      (securityNotifier.isManager ? '/homeManager' : '/homeClient') : '/login',
      routes: {
        '/login': (context) => LoginScreen(),
        '/homeClient': (context) => HomeClientScreen(),
        '/signup': (context) => SignupScreen(),
        '/searchResaurant': (context) => SearchRestaurantsScreen(),
        '/ReservationDetailsScreen': (context) => ReservationDetailsScreen(),
        '/ReservationFormScreen': (context) {
          final args = ModalRoute.of(context)?.settings.arguments as Map<String, dynamic>?;
          final int restaurantId = args?['restaurantId'];
          final Reservation? existingReservations = args?['existingReservations'];
          return ReservationFormScreen(
            restaurantId: restaurantId,
            existingReservations: existingReservations,
          );
        },
        '/homeManager': (context) => ManagerRestaurantsScreen(),
        '/RestaurantDetailsScreen': (context) => RestaurantDetailsScreen(),
        '/RestaurantManagmentReservations': (context) => RestaurantManagementReservationsScreen(),
        '/ReservationDetailsManagerScreen': (context) => ReservationDetailsManagerScreen(),
        '/AssignTablesScreen': (context)  {
          final args = ModalRoute.of(context)?.settings.arguments as Map<String, dynamic>?;
          final Reservation reservation =  args?['reservation'];
          return AssignTablesScreen(
            reservation: reservation,

          );
        },
      },
    );
  }
}