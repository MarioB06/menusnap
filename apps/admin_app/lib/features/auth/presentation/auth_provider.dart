import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/networking/api_exception.dart';
import '../../../shared/models/user.dart';
import '../data/auth_repository.dart';

// Current user state
final currentUserProvider = StateNotifierProvider<CurrentUserNotifier, AsyncValue<User?>>((ref) {
  return CurrentUserNotifier(ref.watch(authRepositoryProvider));
});

// Auth state for login/register forms
final authStateProvider = StateNotifierProvider<AuthStateNotifier, AuthFormState>((ref) {
  return AuthStateNotifier(ref.watch(authRepositoryProvider), ref);
});

class CurrentUserNotifier extends StateNotifier<AsyncValue<User?>> {
  final AuthRepository _repo;

  CurrentUserNotifier(this._repo) : super(const AsyncValue.data(null));

  Future<void> checkAuth() async {
    final isAuth = await _repo.isAuthenticated();
    if (isAuth) {
      try {
        state = const AsyncValue.loading();
        final user = await _repo.getProfile();
        state = AsyncValue.data(user);
      } catch (_) {
        state = const AsyncValue.data(null);
      }
    }
  }

  void setUser(User user) {
    state = AsyncValue.data(user);
  }

  Future<void> logout() async {
    await _repo.logout();
    state = const AsyncValue.data(null);
  }
}

class AuthStateNotifier extends StateNotifier<AuthFormState> {
  final AuthRepository _repo;
  final Ref _ref;

  AuthStateNotifier(this._repo, this._ref) : super(const AuthFormState());

  Future<bool> login({required String email, required String password}) async {
    state = state.copyWith(isLoading: true, error: null);
    try {
      final result = await _repo.login(email: email, password: password);
      _ref.read(currentUserProvider.notifier).setUser(result.user);
      state = state.copyWith(isLoading: false);
      return true;
    } on ApiException catch (e) {
      state = state.copyWith(isLoading: false, error: e.message);
      return false;
    }
  }

  Future<bool> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
  }) async {
    state = state.copyWith(isLoading: true, error: null);
    try {
      final result = await _repo.register(
        name: name,
        email: email,
        password: password,
        passwordConfirmation: passwordConfirmation,
      );
      _ref.read(currentUserProvider.notifier).setUser(result.user);
      state = state.copyWith(isLoading: false);
      return true;
    } on ApiException catch (e) {
      state = state.copyWith(isLoading: false, error: e.message);
      return false;
    }
  }

  void clearError() {
    state = state.copyWith(error: null);
  }
}

class AuthFormState {
  final bool isLoading;
  final String? error;

  const AuthFormState({this.isLoading = false, this.error});

  AuthFormState copyWith({bool? isLoading, String? error}) {
    return AuthFormState(
      isLoading: isLoading ?? this.isLoading,
      error: error,
    );
  }
}
