import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import '../../../core/config/app_config.dart';
import '../../../core/routing/app_router.dart';
import '../../../core/theme/app_colors.dart';
import '../../../core/theme/app_spacing.dart';
import '../../../core/theme/app_typography.dart';
import '../../../shared/widgets/app_scaffold.dart';
import '../../../shared/widgets/card_container.dart';
import '../../auth/presentation/auth_provider.dart';

class SettingsScreen extends ConsumerWidget {
  const SettingsScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final userAsync = ref.watch(currentUserProvider);
    final user = userAsync.valueOrNull;

    return AppScaffold(
      title: 'Einstellungen',
      showBackButton: true,
      body: SingleChildScrollView(
        padding: AppSpacing.screenAll,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // User info
            if (user != null) ...[
              CardContainer(
                child: Row(
                  children: [
                    Container(
                      width: 48,
                      height: 48,
                      decoration: BoxDecoration(
                        gradient: AppColors.primaryGradient,
                        borderRadius: BorderRadius.circular(14),
                      ),
                      child: Center(
                        child: Text(
                          user.name.isNotEmpty
                              ? user.name[0].toUpperCase()
                              : '?',
                          style: AppTypography.h2.copyWith(color: Colors.white),
                        ),
                      ),
                    ),
                    AppSpacing.horizontalMd,
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            user.name,
                            style: AppTypography.body.copyWith(
                              fontWeight: FontWeight.w600,
                            ),
                          ),
                          Text(user.email, style: AppTypography.captionMuted),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
              AppSpacing.verticalXl,
            ] else ...[
              CardContainer(
                onTap: () => context.push(AppRoutes.login),
                child: Row(
                  children: [
                    Container(
                      width: 48,
                      height: 48,
                      decoration: BoxDecoration(
                        color: AppColors.chipBackground,
                        borderRadius: BorderRadius.circular(14),
                      ),
                      child: const Icon(
                        Icons.person_outline_rounded,
                        color: AppColors.primary,
                      ),
                    ),
                    AppSpacing.horizontalMd,
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'Anmelden',
                            style: AppTypography.body.copyWith(
                              fontWeight: FontWeight.w600,
                            ),
                          ),
                          Text(
                            'Melde dich an, um dein Restaurant zu verwalten.',
                            style: AppTypography.captionMuted,
                          ),
                        ],
                      ),
                    ),
                    const Icon(
                      Icons.chevron_right_rounded,
                      color: AppColors.textLight,
                    ),
                  ],
                ),
              ),
              AppSpacing.verticalXl,
            ],

            // Settings items
            Text(
              'ALLGEMEIN',
              style: AppTypography.caption.copyWith(
                color: AppColors.textMuted,
                letterSpacing: 1,
                fontWeight: FontWeight.w600,
              ),
            ),
            AppSpacing.verticalMd,

            CardContainer(
              padding: EdgeInsets.zero,
              child: Column(
                children: [
                  _SettingsItem(
                    icon: Icons.palette_outlined,
                    title: 'Erscheinungsbild',
                    subtitle: 'Systemeinstellung',
                    onTap: () {
                      // Theme selection could be added here
                    },
                  ),
                  const Divider(height: 1, indent: 56),
                  _SettingsItem(
                    icon: Icons.info_outline_rounded,
                    title: 'App-Version',
                    subtitle: AppConfig.appVersion,
                  ),
                ],
              ),
            ),

            AppSpacing.verticalXl,

            // Dev info
            Text(
              'ENTWICKLER',
              style: AppTypography.caption.copyWith(
                color: AppColors.textMuted,
                letterSpacing: 1,
                fontWeight: FontWeight.w600,
              ),
            ),
            AppSpacing.verticalMd,

            CardContainer(
              padding: EdgeInsets.zero,
              child: _SettingsItem(
                icon: Icons.link_rounded,
                title: 'API Base URL',
                subtitle: AppConfig.baseUrl,
              ),
            ),

            // Logout
            if (user != null) ...[
              AppSpacing.verticalXl,
              CardContainer(
                onTap: () async {
                  final confirmed = await showDialog<bool>(
                    context: context,
                    builder: (ctx) => AlertDialog(
                      title: const Text('Abmelden?'),
                      content: const Text(
                          'Möchtest du dich wirklich abmelden?'),
                      shape: RoundedRectangleBorder(
                        borderRadius:
                            BorderRadius.circular(AppSpacing.cardRadius),
                      ),
                      actions: [
                        TextButton(
                          onPressed: () => Navigator.of(ctx).pop(false),
                          child: const Text('Abbrechen'),
                        ),
                        TextButton(
                          onPressed: () => Navigator.of(ctx).pop(true),
                          style: TextButton.styleFrom(
                            foregroundColor: AppColors.error,
                          ),
                          child: const Text('Abmelden'),
                        ),
                      ],
                    ),
                  );
                  if (confirmed == true) {
                    await ref.read(currentUserProvider.notifier).logout();
                    if (context.mounted) {
                      context.go(AppRoutes.home);
                    }
                  }
                },
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    const Icon(Icons.logout_rounded, color: AppColors.error, size: 20),
                    AppSpacing.horizontalSm,
                    Text(
                      'Abmelden',
                      style: AppTypography.body.copyWith(
                        color: AppColors.error,
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                  ],
                ),
              ),
            ],

            const SizedBox(height: 40),
          ],
        ),
      ),
    );
  }
}

class _SettingsItem extends StatelessWidget {
  final IconData icon;
  final String title;
  final String subtitle;
  final VoidCallback? onTap;

  const _SettingsItem({
    required this.icon,
    required this.title,
    required this.subtitle,
    this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return InkWell(
      onTap: onTap,
      child: Padding(
        padding: const EdgeInsets.symmetric(
          horizontal: AppSpacing.lg,
          vertical: 14,
        ),
        child: Row(
          children: [
            Icon(icon, size: 22, color: AppColors.textMuted),
            AppSpacing.horizontalMd,
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(title, style: AppTypography.body),
                  Text(subtitle, style: AppTypography.captionMuted),
                ],
              ),
            ),
            if (onTap != null)
              const Icon(
                Icons.chevron_right_rounded,
                color: AppColors.textLight,
                size: 20,
              ),
          ],
        ),
      ),
    );
  }
}
