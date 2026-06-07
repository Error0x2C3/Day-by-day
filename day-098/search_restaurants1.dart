import 'dart:async';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:prbd_2526_f02/pages/widgets/restaurant_card.dart';
import 'package:prbd_2526_f02/pages/widgets/top_nav_bar.dart';
import 'package:prbd_2526_f02/providers/client_state_provider.dart';

import '../models/restaurant.dart';

class SearchRestaurantsScreen extends ConsumerStatefulWidget {
  const SearchRestaurantsScreen({super.key});
  @override
  ConsumerState<SearchRestaurantsScreen> createState() =>
      _SearchRestaurantsScreenState();
}

class _SearchRestaurantsScreenState extends ConsumerState<SearchRestaurantsScreen>{
  Timer? _debounce;
  late TextEditingController _searchController;

  @override
  void initState() {
    super.initState();
    final initialFilter = ref.read(clientStateProvider).value?.searchFilter;
    _searchController = TextEditingController(text: initialFilter);
  }

  @override
  Widget build(BuildContext context) {
    final searchAsync = ref.watch(clientStateProvider);

    return Scaffold(
      appBar: TopNavBar(
        title: 'Rechercher un restaurant',
        showBackButton: true,
        onRefresh: ref.read(clientStateProvider.notifier).refreshRestaurants,
      ),
      body: SafeArea(
        child: Column(
          children: [
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: TextField(
                controller: _searchController,
                autofocus: true,
                decoration: const InputDecoration(
                  hintText: 'Rechercher par nom, ville, description...',
                  prefixIcon: Icon(Icons.search),
                  border: OutlineInputBorder(),
                ),
              onChanged: (userEntry) {
                  _debounce?.cancel();
                  _debounce = Timer(const Duration(milliseconds: 500), () {
                    // Lance une recherche d'après le mots tapé par l'user.
                    ref.read(clientStateProvider.notifier).researchRestaurants(userEntry);
                  });
                },
              ),
            ),
            Expanded(
              child: Column(
                children: [
                  Expanded(
                    child: searchAsync.when(
                      loading: () => const Center(
                          child: CircularProgressIndicator()
                      ),
                      error: (erreur, stackTrace) =>
                          Center(
                            child: Text('Erreur : $erreur'),
                          ),
                      data: (clientData) {
                        final listeRestaurants = clientData.restaurantsList;
                        if (listeRestaurants.isEmpty) {
                          return const Center(child: Text('Aucun restaurant trouvé.'));
                        }
                        // On prends x résultats max de la list.
                        final restaurantsAfficher = listeRestaurants.take(Restaurant.maxSearch).toList();

                        return Column(
                          children: [
                            if(listeRestaurants.length > Restaurant.maxSearch)
                            Container(
                              width: double.infinity,
                              padding: const EdgeInsets.symmetric(
                                vertical: 8.0,
                                horizontal: 16.0,
                              ),
                              color: Colors.orange.shade50,
                              child: Row(
                                children: [
                                  Icon(
                                    Icons.info_outline,
                                    size: 20,
                                    color: Colors.orange.shade800,
                                  ),
                                  const SizedBox(width: 8),
                                  Expanded(
                                    child: Text(
                                      'Trop de résultats trouvés. Veuillez affiner votre recherche pour voir plus de restaurants.',
                                      style: TextStyle(
                                        fontSize: 13,
                                        color: Colors.orange.shade900,
                                        fontWeight: FontWeight.w500,
                                      ),
                                    ),
                                  ),
                                ],
                              ),
                            ),
                            Expanded(
                              child: ListView.builder(
                                padding: const EdgeInsets.all(16.0),
                                itemCount: restaurantsAfficher.length,
                                itemBuilder: (contexte, index) {
                                  final restaurant = restaurantsAfficher[index];
                                  return RestaurantCard(restaurant: restaurant);
                                },
                              ),
                            ),
                          ],
                        );
                      },
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}