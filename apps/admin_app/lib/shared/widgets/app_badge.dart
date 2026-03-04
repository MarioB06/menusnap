import 'package:flutter/material.dart';
import '../../core/theme/app_colors.dart';
import '../../core/theme/app_spacing.dart';

class AppBadge extends StatelessWidget {
  final String label;
  final Color? backgroundColor;
  final Color? textColor;
  final IconData? icon;

  const AppBadge({
    super.key,
    required this.label,
    this.backgroundColor,
    this.textColor,
    this.icon,
  });

  factory AppBadge.vegan({required String label}) => AppBadge(
        label: label,
        backgroundColor: const Color(0xFFD1FAE5),
        textColor: const Color(0xFF065F46),
        icon: Icons.eco_rounded,
      );

  factory AppBadge.spicy({required String label}) => AppBadge(
        label: label,
        backgroundColor: const Color(0xFFFEE2E2),
        textColor: const Color(0xFF991B1B),
        icon: Icons.local_fire_department_rounded,
      );

  factory AppBadge.allergen({required String label}) => AppBadge(
        label: label,
        backgroundColor: const Color(0xFFFEF3C7),
        textColor: const Color(0xFF92400E),
        icon: Icons.warning_amber_rounded,
      );

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(
        horizontal: AppSpacing.sm,
        vertical: AppSpacing.xs,
      ),
      decoration: BoxDecoration(
        color: backgroundColor ?? AppColors.chipBackground,
        borderRadius: BorderRadius.circular(8),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          if (icon != null) ...[
            Icon(icon, size: 12, color: textColor ?? AppColors.chipText),
            const SizedBox(width: 4),
          ],
          Text(
            label,
            style: TextStyle(
              fontSize: 12,
              fontWeight: FontWeight.w500,
              color: textColor ?? AppColors.chipText,
            ),
          ),
        ],
      ),
    );
  }
}

class AppChip extends StatelessWidget {
  final String label;
  final bool isSelected;
  final VoidCallback? onTap;

  const AppChip({
    super.key,
    required this.label,
    this.isSelected = false,
    this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 200),
        padding: const EdgeInsets.symmetric(
          horizontal: AppSpacing.lg,
          vertical: AppSpacing.sm + 2,
        ),
        decoration: BoxDecoration(
          color: isSelected ? AppColors.primary : AppColors.card,
          borderRadius: BorderRadius.circular(AppSpacing.buttonRadius),
          border: Border.all(
            color: isSelected ? AppColors.primary : AppColors.border,
          ),
          boxShadow: isSelected
              ? [
                  BoxShadow(
                    color: AppColors.primary.withOpacity(0.2),
                    blurRadius: 8,
                    offset: const Offset(0, 2),
                  ),
                ]
              : null,
        ),
        child: Text(
          label,
          style: TextStyle(
            fontSize: 14,
            fontWeight: FontWeight.w500,
            color: isSelected ? Colors.white : AppColors.textMuted,
          ),
        ),
      ),
    );
  }
}
