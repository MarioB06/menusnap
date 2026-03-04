import 'dart:convert';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:hive_flutter/hive_flutter.dart';
import '../../../core/config/app_config.dart';
import '../../../shared/models/recent_menu.dart';

final recentsRepositoryProvider = Provider<RecentsRepository>((ref) {
  return RecentsRepository();
});

class RecentsRepository {
  static const int _maxRecents = 20;

  Box<String>? _box;

  Future<Box<String>> _getBox() async {
    _box ??= await Hive.openBox<String>(AppConfig.recentsBoxKey);
    return _box!;
  }

  Future<List<RecentMenu>> getRecents() async {
    final box = await _getBox();
    final items = <RecentMenu>[];
    for (var i = 0; i < box.length; i++) {
      final raw = box.getAt(i);
      if (raw != null) {
        items.add(RecentMenu.fromJson(
          json.decode(raw) as Map<String, dynamic>,
        ));
      }
    }
    items.sort((a, b) => b.openedAt.compareTo(a.openedAt));
    return items;
  }

  Future<void> addRecent(RecentMenu recent) async {
    final box = await _getBox();

    // Remove duplicate by slug
    final existing = <int>[];
    for (var i = 0; i < box.length; i++) {
      final raw = box.getAt(i);
      if (raw != null) {
        final item = RecentMenu.fromJson(
          json.decode(raw) as Map<String, dynamic>,
        );
        if (item.slug == recent.slug) {
          existing.add(i);
        }
      }
    }
    for (final idx in existing.reversed) {
      await box.deleteAt(idx);
    }

    await box.add(json.encode(recent.toJson()));

    // Trim to max
    while (box.length > _maxRecents) {
      await box.deleteAt(0);
    }
  }

  Future<void> clearRecents() async {
    final box = await _getBox();
    await box.clear();
  }
}
