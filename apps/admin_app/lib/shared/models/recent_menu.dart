/// Locally stored recent menu entry.
class RecentMenu {
  final String restaurantName;
  final String? address;
  final String slug;
  final String? tableUuid;
  final int? restaurantId;
  final DateTime openedAt;

  const RecentMenu({
    required this.restaurantName,
    this.address,
    required this.slug,
    this.tableUuid,
    this.restaurantId,
    required this.openedAt,
  });

  factory RecentMenu.fromJson(Map<String, dynamic> json) => RecentMenu(
        restaurantName: json['restaurant_name'] as String,
        address: json['address'] as String?,
        slug: json['slug'] as String,
        tableUuid: json['table_uuid'] as String?,
        restaurantId: json['restaurant_id'] as int?,
        openedAt: DateTime.parse(json['opened_at'] as String),
      );

  Map<String, dynamic> toJson() => {
        'restaurant_name': restaurantName,
        'address': address,
        'slug': slug,
        'table_uuid': tableUuid,
        'restaurant_id': restaurantId,
        'opened_at': openedAt.toIso8601String(),
      };
}
