import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/networking/dio_client.dart';
import '../../../shared/models/restaurant.dart';
import '../../../shared/models/menu.dart';
import '../../../shared/models/category.dart';

final menuRepositoryProvider = Provider<MenuRepository>((ref) {
  return MenuRepository(ref.watch(dioClientProvider));
});

class MenuRepository {
  final DioClient _client;

  MenuRepository(this._client);

  // --- Restaurants ---

  Future<List<Restaurant>> getRestaurants() async {
    final response = await _client.get('/restaurants');
    final data = response.data as Map<String, dynamic>;
    final list = data['data'] as List<dynamic>;
    return list
        .map((e) => Restaurant.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  Future<Restaurant> getRestaurant(int id) async {
    final response = await _client.get('/restaurants/$id');
    final data = response.data as Map<String, dynamic>;
    return Restaurant.fromJson(data['data'] as Map<String, dynamic>);
  }

  // --- Menus ---

  Future<List<Menu>> getMenus(int restaurantId) async {
    final response = await _client.get('/restaurants/$restaurantId/menus');
    final data = response.data as Map<String, dynamic>;
    final list = data['data'] as List<dynamic>;
    return list.map((e) => Menu.fromJson(e as Map<String, dynamic>)).toList();
  }

  Future<Menu> getMenu(int restaurantId, int menuId) async {
    final response =
        await _client.get('/restaurants/$restaurantId/menus/$menuId');
    final data = response.data as Map<String, dynamic>;
    return Menu.fromJson(data['data'] as Map<String, dynamic>);
  }

  // --- Categories ---

  Future<List<Category>> getCategories(int menuId) async {
    final response = await _client.get('/menus/$menuId/categories');
    final data = response.data as Map<String, dynamic>;
    final list = data['data'] as List<dynamic>;
    return list
        .map((e) => Category.fromJson(e as Map<String, dynamic>))
        .toList();
  }
}
