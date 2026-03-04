import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../shared/models/recent_menu.dart';
import '../data/recents_repository.dart';

final recentsProvider =
    AsyncNotifierProvider<RecentsNotifier, List<RecentMenu>>(
        RecentsNotifier.new);

class RecentsNotifier extends AsyncNotifier<List<RecentMenu>> {
  @override
  Future<List<RecentMenu>> build() async {
    final repo = ref.watch(recentsRepositoryProvider);
    return repo.getRecents();
  }

  Future<void> addRecent(RecentMenu recent) async {
    final repo = ref.read(recentsRepositoryProvider);
    await repo.addRecent(recent);
    ref.invalidateSelf();
  }

  Future<void> clearAll() async {
    final repo = ref.read(recentsRepositoryProvider);
    await repo.clearRecents();
    ref.invalidateSelf();
  }
}
