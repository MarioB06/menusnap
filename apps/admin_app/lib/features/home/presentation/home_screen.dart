import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import '../../../core/routing/app_router.dart';
import '../../../core/theme/app_colors.dart';
import '../../../core/theme/app_spacing.dart';
import '../../../core/theme/app_typography.dart';
import '../../../shared/widgets/card_container.dart';
import '../../../shared/widgets/primary_button.dart';
import '../../../shared/widgets/secondary_button.dart';
import '../../../shared/widgets/section_header.dart';
import '../../../shared/widgets/app_states.dart';
import 'recents_provider.dart';

class HomeScreen extends ConsumerWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final recentsAsync = ref.watch(recentsProvider);

    return Scaffold(
      backgroundColor: AppColors.background,
      body: SafeArea(
        child: SingleChildScrollView(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Top bar
              Padding(
                padding: const EdgeInsets.fromLTRB(
                  AppSpacing.screenPadding,
                  AppSpacing.lg,
                  AppSpacing.screenPadding,
                  0,
                ),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Row(
                      children: [
                        Container(
                          width: 40,
                          height: 40,
                          decoration: BoxDecoration(
                            gradient: AppColors.primaryGradient,
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: const Icon(
                            Icons.qr_code_scanner_rounded,
                            color: Colors.white,
                            size: 20,
                          ),
                        ),
                        AppSpacing.horizontalMd,
                        Text(
                          'MenuSnap',
                          style: AppTypography.h2.copyWith(
                            fontWeight: FontWeight.w700,
                          ),
                        ),
                      ],
                    ),
                    IconButton(
                      onPressed: () => context.push(AppRoutes.settings),
                      icon: const Icon(
                        Icons.settings_outlined,
                        color: AppColors.textMuted,
                      ),
                    ),
                  ],
                ),
              ),

              // Hero section
              Padding(
                padding: const EdgeInsets.all(AppSpacing.screenPadding),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    AppSpacing.verticalXl,
                    Text(
                      'Entdecke\nRestaurant-Menüs',
                      style: AppTypography.h1.copyWith(
                        fontSize: 32,
                        height: 1.2,
                      ),
                    ),
                    AppSpacing.verticalSm,
                    Text(
                      'Scanne den QR-Code am Tisch und sieh das Menü sofort.',
                      style: AppTypography.bodyMuted,
                    ),
                    const SizedBox(height: 32),

                    // Primary CTA
                    PrimaryButton(
                      label: 'QR-Code scannen',
                      icon: Icons.qr_code_scanner_rounded,
                      onPressed: () => context.push(AppRoutes.scan),
                    ),
                    AppSpacing.verticalMd,

                    // Secondary CTA
                    SecondaryButton(
                      label: 'Code eingeben',
                      icon: Icons.keyboard_alt_outlined,
                      onPressed: () => context.push(AppRoutes.codeInput),
                    ),
                  ],
                ),
              ),

              AppSpacing.verticalSm,

              // Recents section
              Padding(
                padding: AppSpacing.screenH,
                child: SectionHeader(
                  title: 'Zuletzt geöffnet',
                  actionText: recentsAsync.valueOrNull?.isNotEmpty == true
                      ? 'Alle löschen'
                      : null,
                  onAction: () {
                    ref.read(recentsProvider.notifier).clearAll();
                  },
                ),
              ),
              AppSpacing.verticalMd,

              recentsAsync.when(
                data: (recents) {
                  if (recents.isEmpty) {
                    return const Padding(
                      padding: EdgeInsets.symmetric(vertical: AppSpacing.xl),
                      child: EmptyState(
                        icon: Icons.history_rounded,
                        title: 'Noch keine Menüs',
                        subtitle: 'Scanne einen QR-Code, um ein Menü zu öffnen.',
                      ),
                    );
                  }
                  return ListView.separated(
                    shrinkWrap: true,
                    physics: const NeverScrollableScrollPhysics(),
                    padding: AppSpacing.screenH,
                    itemCount: recents.length,
                    separatorBuilder: (_, __) => AppSpacing.verticalMd,
                    itemBuilder: (context, index) {
                      final recent = recents[index];
                      return CardContainer(
                        onTap: () {
                          if (recent.restaurantId != null) {
                            context.push('/menu/${recent.restaurantId}');
                          }
                        },
                        child: Row(
                          children: [
                            Container(
                              width: 48,
                              height: 48,
                              decoration: BoxDecoration(
                                color: AppColors.chipBackground,
                                borderRadius: BorderRadius.circular(12),
                              ),
                              child: const Icon(
                                Icons.restaurant_menu_rounded,
                                color: AppColors.primary,
                                size: 22,
                              ),
                            ),
                            AppSpacing.horizontalMd,
                            Expanded(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    recent.restaurantName,
                                    style: AppTypography.body.copyWith(
                                      fontWeight: FontWeight.w600,
                                    ),
                                    maxLines: 1,
                                    overflow: TextOverflow.ellipsis,
                                  ),
                                  if (recent.address != null)
                                    Text(
                                      recent.address!,
                                      style: AppTypography.captionMuted,
                                      maxLines: 1,
                                      overflow: TextOverflow.ellipsis,
                                    ),
                                ],
                              ),
                            ),
                            AppSpacing.horizontalSm,
                            Text(
                              _formatDate(recent.openedAt),
                              style: AppTypography.captionMuted,
                            ),
                            AppSpacing.horizontalXs,
                            const Icon(
                              Icons.chevron_right_rounded,
                              color: AppColors.textLight,
                              size: 20,
                            ),
                          ],
                        ),
                      );
                    },
                  );
                },
                loading: () => const LoadingState(itemCount: 3),
                error: (e, _) => ErrorState(
                  message: e.toString(),
                  onRetry: () => ref.invalidate(recentsProvider),
                ),
              ),

              const SizedBox(height: 40),
            ],
          ),
        ),
      ),
    );
  }

  String _formatDate(DateTime date) {
    final now = DateTime.now();
    final diff = now.difference(date);
    if (diff.inMinutes < 60) return 'Gerade eben';
    if (diff.inHours < 24) return 'Vor ${diff.inHours}h';
    if (diff.inDays == 1) return 'Gestern';
    if (diff.inDays < 7) return 'Vor ${diff.inDays}T';
    return '${date.day}.${date.month}.${date.year}';
  }
}
