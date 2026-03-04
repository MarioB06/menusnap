import 'category.dart';

class Menu {
  final int id;
  final int restaurantId;
  final String name;
  final String? description;
  final bool isActive;
  final int sortOrder;
  final List<Category> categories;
  final DateTime createdAt;
  final DateTime updatedAt;

  const Menu({
    required this.id,
    required this.restaurantId,
    required this.name,
    this.description,
    this.isActive = true,
    this.sortOrder = 0,
    this.categories = const [],
    required this.createdAt,
    required this.updatedAt,
  });

  factory Menu.fromJson(Map<String, dynamic> json) => Menu(
        id: json['id'] as int,
        restaurantId: json['restaurant_id'] as int,
        name: json['name'] as String,
        description: json['description'] as String?,
        isActive: json['is_active'] as bool? ?? true,
        sortOrder: json['sort_order'] as int? ?? 0,
        categories: (json['categories'] as List<dynamic>?)
                ?.map((e) => Category.fromJson(e as Map<String, dynamic>))
                .toList() ??
            [],
        createdAt: DateTime.parse(json['created_at'] as String),
        updatedAt: DateTime.parse(json['updated_at'] as String),
      );

  Map<String, dynamic> toJson() => {
        'id': id,
        'restaurant_id': restaurantId,
        'name': name,
        'description': description,
        'is_active': isActive,
        'sort_order': sortOrder,
      };
}
