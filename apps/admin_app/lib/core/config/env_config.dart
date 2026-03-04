/// Runtime environment configuration.
/// Override via --dart-define=BASE_URL=https://your-domain.com
class EnvConfig {
  const EnvConfig._();

  static const String baseUrl = String.fromEnvironment(
    'BASE_URL',
    defaultValue: 'https://menusnap.ch',
  );

  static const bool isProduction = String.fromEnvironment(
        'ENV',
        defaultValue: 'production',
      ) ==
      'production';
}
