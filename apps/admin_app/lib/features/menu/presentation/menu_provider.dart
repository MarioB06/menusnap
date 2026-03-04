import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../shared/models/restaurant.dart';
import '../data/menu_repository.dart';

// Load full restaurant with menus/categories/dishes
final restaurantDetailProvider =
    FutureProvider.family<Restaurant, int>((ref, restaurantId) async {
  final repo = ref.watch(menuRepositoryProvider);
  return repo.getRestaurant(restaurantId);
});

// List all user restaurants
final restaurantsProvider = FutureProvider<List<Restaurant>>((ref) async {
  final repo = ref.watch(menuRepositoryProvider);
  return repo.getRestaurants();
});

// Selected category index for menu screen
final selectedCategoryIndexProvider = StateProvider<int>((ref) => 0);

// Search query for menu items
final menuSearchQueryProvider = StateProvider<String>((ref) => '');
