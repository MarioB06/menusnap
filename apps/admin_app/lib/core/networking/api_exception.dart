class ApiException implements Exception {
  final String message;
  final int? statusCode;
  final Map<String, dynamic>? errors;

  const ApiException({
    required this.message,
    this.statusCode,
    this.errors,
  });

  bool get isUnauthorized => statusCode == 401;
  bool get isForbidden => statusCode == 403;
  bool get isNotFound => statusCode == 404;
  bool get isValidation => statusCode == 422;
  bool get isServer => statusCode != null && statusCode! >= 500;

  String? fieldError(String field) {
    final fieldErrors = errors?[field];
    if (fieldErrors is List && fieldErrors.isNotEmpty) {
      return fieldErrors.first.toString();
    }
    return null;
  }

  @override
  String toString() => 'ApiException($statusCode): $message';
}

class NetworkException extends ApiException {
  const NetworkException()
      : super(
          message: 'Keine Internetverbindung.',
          statusCode: null,
        );
}

class TimeoutException extends ApiException {
  const TimeoutException()
      : super(
          message: 'Zeitüberschreitung. Bitte erneut versuchen.',
          statusCode: null,
        );
}
