import 'dish.dart';

class Category {
  final int id;
  final int menuId;
  final String name;
  final String? description;
  final int sortOrder;
  final List<Dish> dishes;
  final DateTime createdAt;
  final DateTime updatedAt;

  const Category({
    required this.id,
    required this.menuId,
    required this.name,
    this.description,
    this.sortOrder = 0,
    this.dishes = const [],
    required this.createdAt,
    required this.updatedAt,
  });

  factory Category.fromJson(Map<String, dynamic> json) => Category(
        id: json['id'] as int,
        menuId: json['menu_id'] as int,
        name: json['name'] as String,
        description: json['description'] as String?,
        sortOrder: json['sort_order'] as int? ?? 0,
        dishes: (json['dishes'] as List<dynamic>?)
                ?.map((e) => Dish.fromJson(e as Map<String, dynamic>))
                .toList() ??
            [],
        createdAt: DateTime.parse(json['created_at'] as String),
        updatedAt: DateTime.parse(json['updated_at'] as String),
      );

  Map<String, dynamic> toJson() => {
        'id': id,
        'menu_id': menuId,
        'name': name,
        'description': description,
        'sort_order': sortOrder,
      };
}
