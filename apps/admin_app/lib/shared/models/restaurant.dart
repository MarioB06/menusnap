import 'menu.dart';

class Restaurant {
  final int id;
  final String name;
  final String slug;
  final String? description;
  final String? logoUrl;
  final String? address;
  final String? phone;
  final String? website;
  final bool isActive;
  final List<Menu> menus;
  final DateTime createdAt;
  final DateTime updatedAt;

  const Restaurant({
    required this.id,
    required this.name,
    required this.slug,
    this.description,
    this.logoUrl,
    this.address,
    this.phone,
    this.website,
    this.isActive = true,
    this.menus = const [],
    required this.createdAt,
    required this.updatedAt,
  });

  factory Restaurant.fromJson(Map<String, dynamic> json) => Restaurant(
        id: json['id'] as int,
        name: json['name'] as String,
        slug: json['slug'] as String,
        description: json['description'] as String?,
        logoUrl: json['logo_url'] as String?,
        address: json['address'] as String?,
        phone: json['phone'] as String?,
        website: json['website'] as String?,
        isActive: json['is_active'] as bool? ?? true,
        menus: (json['menus'] as List<dynamic>?)
                ?.map((e) => Menu.fromJson(e as Map<String, dynamic>))
                .toList() ??
            [],
        createdAt: DateTime.parse(json['created_at'] as String),
        updatedAt: DateTime.parse(json['updated_at'] as String),
      );

  Map<String, dynamic> toJson() => {
        'id': id,
        'name': name,
        'slug': slug,
        'description': description,
        'logo_url': logoUrl,
        'address': address,
        'phone': phone,
        'website': website,
        'is_active': isActive,
      };
}
