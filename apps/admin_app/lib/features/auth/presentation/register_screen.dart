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

class RegisterScreen extends ConsumerStatefulWidget {
  const RegisterScreen({super.key});

  @override
  ConsumerState<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends ConsumerState<RegisterScreen> {
  final _nameController = TextEditingController();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  final _confirmController = TextEditingController();
  bool _obscurePassword = true;

  @override
  void dispose() {
    _nameController.dispose();
    _emailController.dispose();
    _passwordController.dispose();
    _confirmController.dispose();
    super.dispose();
  }

  Future<void> _handleRegister() async {
    final name = _nameController.text.trim();
    final email = _emailController.text.trim();
    final password = _passwordController.text;
    final confirm = _confirmController.text;

    if (name.isEmpty || email.isEmpty || password.isEmpty || confirm.isEmpty) {
      return;
    }

    final success = await ref.read(authStateProvider.notifier).register(
          name: name,
          email: email,
          password: password,
          passwordConfirmation: confirm,
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
      appBar: AppBar(
        backgroundColor: AppColors.background,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios_rounded, size: 20),
          onPressed: () => context.pop(),
        ),
      ),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: AppSpacing.screenAll,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text('Konto erstellen', style: AppTypography.h1),
              AppSpacing.verticalSm,
              Text(
                'Registriere dich, um dein Restaurant zu verwalten.',
                style: AppTypography.bodyMuted,
              ),
              const SizedBox(height: 32),

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

              AppTextField(
                controller: _nameController,
                labelText: 'Name',
                hintText: 'Dein Name',
                textInputAction: TextInputAction.next,
                prefixIcon: const Icon(Icons.person_outline_rounded, size: 20),
              ),
              AppSpacing.verticalLg,
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
                hintText: 'Mindestens 8 Zeichen',
                obscureText: _obscurePassword,
                textInputAction: TextInputAction.next,
                prefixIcon: const Icon(Icons.lock_outline_rounded, size: 20),
                suffixIcon: IconButton(
                  icon: Icon(
                    _obscurePassword ? Icons.visibility_off_outlined : Icons.visibility_outlined,
                    size: 20,
                  ),
                  onPressed: () => setState(() => _obscurePassword = !_obscurePassword),
                ),
              ),
              AppSpacing.verticalLg,
              AppTextField(
                controller: _confirmController,
                labelText: 'Passwort bestätigen',
                hintText: 'Passwort wiederholen',
                obscureText: true,
                textInputAction: TextInputAction.done,
                onSubmitted: (_) => _handleRegister(),
                prefixIcon: const Icon(Icons.lock_outline_rounded, size: 20),
              ),
              const SizedBox(height: 32),

              PrimaryButton(
                label: 'Registrieren',
                onPressed: _handleRegister,
                isLoading: authState.isLoading,
              ),
              AppSpacing.verticalXl,

              Center(
                child: GestureDetector(
                  onTap: () => context.pop(),
                  child: RichText(
                    text: TextSpan(
                      text: 'Bereits ein Konto? ',
                      style: AppTypography.small.copyWith(color: AppColors.textMuted),
                      children: [
                        TextSpan(
                          text: 'Anmelden',
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
            ],
          ),
        ),
      ),
    );
  }
}
