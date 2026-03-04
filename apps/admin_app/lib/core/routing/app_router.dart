import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../features/auth/presentation/login_screen.dart';
import '../../features/auth/presentation/register_screen.dart';
import '../../features/home/presentation/home_screen.dart';
import '../../features/scan/presentation/scan_screen.dart';
import '../../features/scan/presentation/code_input_screen.dart';
import '../../features/menu/presentation/menu_screen.dart';
import '../../features/settings/presentation/settings_screen.dart';
import '../../features/splash/presentation/splash_screen.dart';

abstract final class AppRoutes {
  static const splash = '/';
  static const login = '/login';
  static const register = '/register';
  static const home = '/home';
  static const scan = '/scan';
  static const codeInput = '/code-input';
  static const menu = '/menu/:restaurantId';
  static const settings = '/settings';
}

final appRouter = GoRouter(
  initialLocation: AppRoutes.splash,
  routes: [
    GoRoute(
      path: AppRoutes.splash,
      builder: (context, state) => const SplashScreen(),
    ),
    GoRoute(
      path: AppRoutes.login,
      builder: (context, state) => const LoginScreen(),
    ),
    GoRoute(
      path: AppRoutes.register,
      builder: (context, state) => const RegisterScreen(),
    ),
    GoRoute(
      path: AppRoutes.home,
      builder: (context, state) => const HomeScreen(),
    ),
    GoRoute(
      path: AppRoutes.scan,
      builder: (context, state) => const ScanScreen(),
    ),
    GoRoute(
      path: AppRoutes.codeInput,
      builder: (context, state) => const CodeInputScreen(),
    ),
    GoRoute(
      path: AppRoutes.menu,
      builder: (context, state) {
        final idStr = state.pathParameters['restaurantId'] ?? '0';
        final restaurantId = int.tryParse(idStr) ?? 0;
        return MenuScreen(restaurantId: restaurantId);
      },
    ),
    GoRoute(
      path: AppRoutes.settings,
      builder: (context, state) => const SettingsScreen(),
    ),
  ],
  errorBuilder: (context, state) => Scaffold(
    body: Center(
      child: Text('Seite nicht gefunden: ${state.uri}'),
    ),
  ),
);
