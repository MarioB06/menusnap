class Dish {
  final int id;
  final int categoryId;
  final String name;
  final String? description;
  final double price;
  final String? imageUrl;
  final List<String> allergens;
  final List<String> dietaryTags;
  final bool isAvailable;
  final int sortOrder;
  final DateTime createdAt;
  final DateTime updatedAt;

  const Dish({
    required this.id,
    required this.categoryId,
    required this.name,
    this.description,
    required this.price,
    this.imageUrl,
    this.allergens = const [],
    this.dietaryTags = const [],
    this.isAvailable = true,
    this.sortOrder = 0,
    required this.createdAt,
    required this.updatedAt,
  });

  factory Dish.fromJson(Map<String, dynamic> json) => Dish(
        id: json['id'] as int,
        categoryId: json['category_id'] as int,
        name: json['name'] as String,
        description: json['description'] as String?,
        price: (json['price'] as num).toDouble(),
        imageUrl: json['image_url'] as String?,
        allergens: (json['allergens'] as List<dynamic>?)
                ?.map((e) => e.toString())
                .toList() ??
            [],
        dietaryTags: (json['dietary_tags'] as List<dynamic>?)
                ?.map((e) => e.toString())
                .toList() ??
            [],
        isAvailable: json['is_available'] as bool? ?? true,
        sortOrder: json['sort_order'] as int? ?? 0,
        createdAt: DateTime.parse(json['created_at'] as String),
        updatedAt: DateTime.parse(json['updated_at'] as String),
      );

  Map<String, dynamic> toJson() => {
        'id': id,
        'category_id': categoryId,
        'name': name,
        'description': description,
        'price': price,
        'image_url': imageUrl,
        'allergens': allergens,
        'dietary_tags': dietaryTags,
        'is_available': isAvailable,
        'sort_order': sortOrder,
      };

  String get formattedPrice => '${price.toStringAsFixed(2)} \u20AC';
}
