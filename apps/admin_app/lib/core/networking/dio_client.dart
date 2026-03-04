import 'package:dio/dio.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import '../config/app_config.dart';
import 'api_exception.dart';

final dioClientProvider = Provider<DioClient>((ref) {
  return DioClient();
});

class DioClient {
  late final Dio _dio;
  final _storage = const FlutterSecureStorage();

  DioClient() {
    _dio = Dio(BaseOptions(
      baseUrl: AppConfig.apiBaseUrl,
      connectTimeout: const Duration(seconds: 15),
      receiveTimeout: const Duration(seconds: 15),
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
    ));

    _dio.interceptors.add(_AuthInterceptor(_storage));
    _dio.interceptors.add(LogInterceptor(
      requestBody: true,
      responseBody: true,
      logPrint: (obj) => print('[DIO] $obj'),
    ));
  }

  Future<Response<T>> get<T>(
    String path, {
    Map<String, dynamic>? queryParameters,
  }) =>
      _handleRequest(() => _dio.get<T>(path, queryParameters: queryParameters));

  Future<Response<T>> post<T>(
    String path, {
    dynamic data,
  }) =>
      _handleRequest(() => _dio.post<T>(path, data: data));

  Future<Response<T>> put<T>(
    String path, {
    dynamic data,
  }) =>
      _handleRequest(() => _dio.put<T>(path, data: data));

  Future<Response<T>> delete<T>(String path) =>
      _handleRequest(() => _dio.delete<T>(path));

  Future<Response<T>> _handleRequest<T>(
    Future<Response<T>> Function() request,
  ) async {
    try {
      return await request();
    } on DioException catch (e) {
      throw _mapDioException(e);
    }
  }

  ApiException _mapDioException(DioException e) {
    switch (e.type) {
      case DioExceptionType.connectionTimeout:
      case DioExceptionType.sendTimeout:
      case DioExceptionType.receiveTimeout:
        return const TimeoutException();
      case DioExceptionType.connectionError:
        return const NetworkException();
      case DioExceptionType.badResponse:
        final data = e.response?.data;
        final statusCode = e.response?.statusCode;
        return ApiException(
          message: data is Map ? (data['message'] ?? 'Fehler aufgetreten.') : 'Fehler aufgetreten.',
          statusCode: statusCode,
          errors: data is Map ? data['errors'] as Map<String, dynamic>? : null,
        );
      default:
        return const ApiException(message: 'Unbekannter Fehler aufgetreten.');
    }
  }

  // Token management
  Future<void> saveToken(String token) async {
    await _storage.write(key: AppConfig.tokenKey, value: token);
  }

  Future<void> clearToken() async {
    await _storage.delete(key: AppConfig.tokenKey);
  }

  Future<String?> getToken() async {
    return await _storage.read(key: AppConfig.tokenKey);
  }
}

class _AuthInterceptor extends Interceptor {
  final FlutterSecureStorage _storage;

  _AuthInterceptor(this._storage);

  @override
  void onRequest(
    RequestOptions options,
    RequestInterceptorHandler handler,
  ) async {
    final token = await _storage.read(key: AppConfig.tokenKey);
    if (token != null) {
      options.headers['Authorization'] = 'Bearer $token';
    }
    handler.next(options);
  }

  @override
  void onError(DioException err, ErrorInterceptorHandler handler) {
    if (err.response?.statusCode == 401) {
      _storage.delete(key: AppConfig.tokenKey);
    }
    handler.next(err);
  }
}
