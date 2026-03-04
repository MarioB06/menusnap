import 'package:flutter/material.dart';
import '../../../core/theme/app_colors.dart';
import '../../../core/theme/app_spacing.dart';
import '../../../core/theme/app_typography.dart';
import '../../../shared/models/dish.dart';
import '../../../shared/widgets/app_badge.dart';

class DishDetailSheet extends StatelessWidget {
  final Dish dish;

  const DishDetailSheet({super.key, required this.dish});

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: const BoxDecoration(
        color: AppColors.card,
        borderRadius: BorderRadius.vertical(top: Radius.circular(24)),
      ),
      child: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          mainAxisSize: MainAxisSize.min,
          children: [
            // Handle bar
            Center(
              child: Container(
                margin: const EdgeInsets.only(top: 12),
                width: 40,
                height: 4,
                decoration: BoxDecoration(
                  color: AppColors.border,
                  borderRadius: BorderRadius.circular(2),
                ),
              ),
            ),

            // Image
            if (dish.imageUrl != null)
              Padding(
                padding: const EdgeInsets.all(AppSpacing.lg),
                child: ClipRRect(
                  borderRadius: BorderRadius.circular(AppSpacing.cardRadius),
                  child: Image.network(
                    dish.imageUrl!,
                    width: double.infinity,
                    height: 200,
                    fit: BoxFit.cover,
                    errorBuilder: (_, __, ___) => Container(
                      height: 120,
                      color: AppColors.chipBackground,
                      child: const Center(
                        child: Icon(
                          Icons.restaurant_rounded,
                          size: 48,
                          color: AppColors.primary,
                        ),
                      ),
                    ),
                  ),
                ),
              ),

            Padding(
              padding: const EdgeInsets.fromLTRB(
                AppSpacing.screenPadding,
                AppSpacing.lg,
                AppSpacing.screenPadding,
                AppSpacing.xxl,
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Name + Price
                  Row(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Expanded(
                        child: Text(dish.name, style: AppTypography.h2),
                      ),
                      AppSpacing.horizontalMd,
                      Container(
                        padding: const EdgeInsets.symmetric(
                          horizontal: 12,
                          vertical: 6,
                        ),
                        decoration: BoxDecoration(
                          gradient: AppColors.primaryGradient,
                          borderRadius: BorderRadius.circular(10),
                        ),
                        child: Text(
                          dish.formattedPrice,
                          style: AppTypography.body.copyWith(
                            color: Colors.white,
                            fontWeight: FontWeight.w700,
                          ),
                        ),
                      ),
                    ],
                  ),

                  // Description
                  if (dish.description != null &&
                      dish.description!.isNotEmpty) ...[
                    AppSpacing.verticalMd,
                    Text(dish.description!, style: AppTypography.bodyMuted),
                  ],

                  // Tags
                  if (dish.dietaryTags.isNotEmpty) ...[
                    AppSpacing.verticalLg,
                    Text(
                      'Ernährung',
                      style: AppTypography.caption.copyWith(
                        fontWeight: FontWeight.w600,
                        color: AppColors.textMuted,
                        letterSpacing: 0.5,
                      ),
                    ),
                    AppSpacing.verticalSm,
                    Wrap(
                      spacing: 8,
                      runSpacing: 8,
                      children: dish.dietaryTags
                          .map((tag) => AppBadge.vegan(label: tag))
                          .toList(),
                    ),
                  ],

                  // Allergens
                  if (dish.allergens.isNotEmpty) ...[
                    AppSpacing.verticalLg,
                    Text(
                      'Allergene',
                      style: AppTypography.caption.copyWith(
                        fontWeight: FontWeight.w600,
                        color: AppColors.textMuted,
                        letterSpacing: 0.5,
                      ),
                    ),
                    AppSpacing.verticalSm,
                    Wrap(
                      spacing: 8,
                      runSpacing: 8,
                      children: dish.allergens
                          .map((a) => AppBadge.allergen(label: a))
                          .toList(),
                    ),
                  ],

                  // Bottom padding for safe area
                  SizedBox(
                    height: MediaQuery.of(context).padding.bottom + 16,
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
