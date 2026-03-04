import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import '../../../core/routing/app_router.dart';
import '../../../core/theme/app_colors.dart';
import '../../../core/theme/app_spacing.dart';
import '../../../core/theme/app_typography.dart';
import '../../../shared/widgets/app_text_field.dart';
import '../../../shared/widgets/primary_button.dart';
import 'auth_provider.dart';

class LoginScreen extends ConsumerStatefulWidget {
  const LoginScreen({super.key});

  @override
  ConsumerState<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends ConsumerState<LoginScreen> {
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _obscurePassword = true;

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  Future<void> _handleLogin() async {
    final email = _emailController.text.trim();
    final password = _passwordController.text;

    if (email.isEmpty || password.isEmpty) return;

    final success = await ref.read(authStateProvider.notifier).login(
          email: email,
          password: password,
        );

    if (success && mounted) {
      context.go(AppRoutes.home);
    }
  }

  @override
  Widget build(BuildContext context) {
    final authState = ref.watch(authStateProvider);

    return Scaffold(
      backgroundColor: AppColors.background,
      body: SafeArea(
        child: SingleChildScrollView(
          padding: AppSpacing.screenAll,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const SizedBox(height: 48),
              // Logo
              Container(
                width: 56,
                height: 56,
                decoration: BoxDecoration(
                  gradient: AppColors.primaryGradient,
                  borderRadius: BorderRadius.circular(16),
                ),
                child: const Icon(
                  Icons.qr_code_scanner_rounded,
                  color: Colors.white,
                  size: 28,
                ),
              ),
              AppSpacing.verticalXl,
              Text('Willkommen zurück', style: AppTypography.h1),
              AppSpacing.verticalSm,
              Text(
                'Melde dich an, um dein Restaurant zu verwalten.',
                style: AppTypography.bodyMuted,
              ),
              const SizedBox(height: 40),

              // Error
              if (authState.error != null) ...[
                Container(
                  padding: AppSpacing.cardAll,
                  decoration: BoxDecoration(
                    color: AppColors.error.withOpacity(0.08),
                    borderRadius: BorderRadius.circular(AppSpacing.buttonRadius),
                    border: Border.all(color: AppColors.error.withOpacity(0.2)),
                  ),
                  child: Row(
                    children: [
                      const Icon(Icons.error_outline, color: AppColors.error, size: 20),
                      AppSpacing.horizontalSm,
                      Expanded(
                        child: Text(
                          authState.error!,
                          style: AppTypography.small.copyWith(color: AppColors.error),
                        ),
                      ),
                    ],
                  ),
                ),
                AppSpacing.verticalXl,
              ],

              // Fields
              AppTextField(
                controller: _emailController,
                labelText: 'E-Mail',
                hintText: 'deine@email.de',
                keyboardType: TextInputType.emailAddress,
                textInputAction: TextInputAction.next,
                prefixIcon: const Icon(Icons.email_outlined, size: 20),
              ),
              AppSpacing.verticalLg,
              AppTextField(
                controller: _passwordController,
                labelText: 'Passwort',
                hintText: 'Passwort eingeben',
                obscureText: _obscurePassword,
                textInputAction: TextInputAction.done,
                onSubmitted: (_) => _handleLogin(),
                prefixIcon: const Icon(Icons.lock_outline_rounded, size: 20),
                suffixIcon: IconButton(
                  icon: Icon(
                    _obscurePassword ? Icons.visibility_off_outlined : Icons.visibility_outlined,
                    size: 20,
                  ),
                  onPressed: () => setState(() => _obscurePassword = !_obscurePassword),
                ),
              ),
              const SizedBox(height: 32),

              // Login button
              PrimaryButton(
                label: 'Anmelden',
                onPressed: _handleLogin,
                isLoading: authState.isLoading,
              ),
              AppSpacing.verticalXl,

              // Register link
              Center(
                child: GestureDetector(
                  onTap: () => context.push(AppRoutes.register),
                  child: RichText(
                    text: TextSpan(
                      text: 'Noch kein Konto? ',
                      style: AppTypography.small.copyWith(color: AppColors.textMuted),
                      children: [
                        TextSpan(
                          text: 'Registrieren',
                          style: AppTypography.small.copyWith(
                            color: AppColors.primary,
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ),

              AppSpacing.verticalXl,

              // Skip login
              Center(
                child: TextButton(
                  onPressed: () => context.go(AppRoutes.home),
                  child: Text(
                    'Ohne Anmeldung fortfahren',
                    style: AppTypography.small.copyWith(color: AppColors.textLight),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
