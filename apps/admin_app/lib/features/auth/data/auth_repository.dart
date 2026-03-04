import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/networking/dio_client.dart';
import '../../../shared/models/user.dart';

final authRepositoryProvider = Provider<AuthRepository>((ref) {
  return AuthRepository(ref.watch(dioClientProvider));
});

class AuthRepository {
  final DioClient _client;

  AuthRepository(this._client);

  Future<AuthResult> login({
    required String email,
    required String password,
  }) async {
    final response = await _client.post('/auth/login', data: {
      'email': email,
      'password': password,
    });
    final data = response.data as Map<String, dynamic>;
    final user = User.fromJson(data['data'] as Map<String, dynamic>);
    final token = data['token'] as String;
    await _client.saveToken(token);
    return AuthResult(user: user, token: token);
  }

  Future<AuthResult> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
  }) async {
    final response = await _client.post('/auth/register', data: {
      'name': name,
      'email': email,
      'password': password,
      'password_confirmation': passwordConfirmation,
    });
    final data = response.data as Map<String, dynamic>;
    final user = User.fromJson(data['data'] as Map<String, dynamic>);
    final token = data['token'] as String;
    await _client.saveToken(token);
    return AuthResult(user: user, token: token);
  }

  Future<User> getProfile() async {
    final response = await _client.get('/auth/profile');
    final data = response.data as Map<String, dynamic>;
    return User.fromJson(data['data'] as Map<String, dynamic>);
  }

  Future<void> logout() async {
    try {
      await _client.post('/auth/logout');
    } finally {
      await _client.clearToken();
    }
  }

  Future<bool> isAuthenticated() async {
    final token = await _client.getToken();
    return token != null;
  }
}

class AuthResult {
  final User user;
  final String token;

  const AuthResult({required this.user, required this.token});
}
