import 'package:flutter/material.dart';
import 'package:shimmer/shimmer.dart';
import '../../core/theme/app_colors.dart';
import '../../core/theme/app_spacing.dart';
import '../../core/theme/app_typography.dart';
import 'primary_button.dart';

/// Empty state placeholder
class EmptyState extends StatelessWidget {
  final IconData icon;
  final String title;
  final String? subtitle;
  final String? actionLabel;
  final VoidCallback? onAction;

  const EmptyState({
    super.key,
    required this.icon,
    required this.title,
    this.subtitle,
    this.actionLabel,
    this.onAction,
  });

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(AppSpacing.xxl),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              width: 72,
              height: 72,
              decoration: BoxDecoration(
                color: AppColors.chipBackground,
                borderRadius: BorderRadius.circular(20),
              ),
              child: Icon(icon, size: 32, color: AppColors.primary),
            ),
            AppSpacing.verticalLg,
            Text(title, style: AppTypography.h3, textAlign: TextAlign.center),
            if (subtitle != null) ...[
              AppSpacing.verticalSm,
              Text(
                subtitle!,
                style: AppTypography.bodyMuted,
                textAlign: TextAlign.center,
              ),
            ],
            if (actionLabel != null && onAction != null) ...[
              AppSpacing.verticalXl,
              PrimaryButton(
                label: actionLabel!,
                onPressed: onAction,
                width: 200,
              ),
            ],
          ],
        ),
      ),
    );
  }
}

/// Error state with retry
class ErrorState extends StatelessWidget {
  final String message;
  final VoidCallback? onRetry;

  const ErrorState({
    super.key,
    required this.message,
    this.onRetry,
  });

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(AppSpacing.xxl),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              width: 72,
              height: 72,
              decoration: BoxDecoration(
                color: AppColors.error.withOpacity(0.1),
                borderRadius: BorderRadius.circular(20),
              ),
              child: const Icon(
                Icons.error_outline_rounded,
                size: 32,
                color: AppColors.error,
              ),
            ),
            AppSpacing.verticalLg,
            Text(
              'Etwas ist schiefgelaufen',
              style: AppTypography.h3,
              textAlign: TextAlign.center,
            ),
            AppSpacing.verticalSm,
            Text(
              message,
              style: AppTypography.bodyMuted,
              textAlign: TextAlign.center,
            ),
            if (onRetry != null) ...[
              AppSpacing.verticalXl,
              PrimaryButton(
                label: 'Erneut versuchen',
                onPressed: onRetry,
                width: 200,
                icon: Icons.refresh_rounded,
              ),
            ],
          ],
        ),
      ),
    );
  }
}

/// Loading state with shimmer
class LoadingState extends StatelessWidget {
  final int itemCount;

  const LoadingState({super.key, this.itemCount = 3});

  @override
  Widget build(BuildContext context) {
    return Shimmer.fromColors(
      baseColor: AppColors.border,
      highlightColor: AppColors.card,
      child: ListView.separated(
        shrinkWrap: true,
        physics: const NeverScrollableScrollPhysics(),
        padding: AppSpacing.screenH,
        itemCount: itemCount,
        separatorBuilder: (_, __) => AppSpacing.verticalMd,
        itemBuilder: (_, __) => Container(
          height: 88,
          decoration: BoxDecoration(
            color: AppColors.card,
            borderRadius: BorderRadius.circular(AppSpacing.cardRadius),
          ),
        ),
      ),
    );
  }
}

/// Full-screen loading overlay
class LoadingOverlay extends StatelessWidget {
  const LoadingOverlay({super.key});

  @override
  Widget build(BuildContext context) {
    return const Center(
      child: CircularProgressIndicator(
        color: AppColors.primary,
        strokeWidth: 3,
      ),
    );
  }
}
