abstract final class AppConfig {
  static const String appName = 'MenuSnap';
  static const String appVersion = '1.0.0';

  // Base URL - change this to your production URL
  static const String baseUrl = String.fromEnvironment(
    'BASE_URL',
    defaultValue: 'https://menusnap.ch',
  );

  static String get apiBaseUrl => '$baseUrl/api/v1';

  // Storage keys
  static const String tokenKey = 'auth_token';
  static const String userKey = 'user_data';
  static const String recentsBoxKey = 'recents';
  static const String favoritesBoxKey = 'favorites';
  static const String settingsBoxKey = 'settings';
}
