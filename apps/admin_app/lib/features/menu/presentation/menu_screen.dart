import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/theme/app_colors.dart';
import '../../../core/theme/app_spacing.dart';
import '../../../core/theme/app_typography.dart';
import '../../../shared/models/dish.dart';
import '../../../shared/models/category.dart';
import '../../../shared/widgets/app_badge.dart';
import '../../../shared/widgets/app_states.dart';
import '../../../shared/widgets/app_text_field.dart';
import '../../../shared/widgets/card_container.dart';
import 'menu_provider.dart';
import 'dish_detail_sheet.dart';

class MenuScreen extends ConsumerWidget {
  final int restaurantId;

  const MenuScreen({super.key, required this.restaurantId});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final restaurantAsync = ref.watch(restaurantDetailProvider(restaurantId));
    final selectedCategoryIdx = ref.watch(selectedCategoryIndexProvider);
    final searchQuery = ref.watch(menuSearchQueryProvider);

    return Scaffold(
      backgroundColor: AppColors.background,
      body: restaurantAsync.when(
        loading: () => const SafeArea(child: LoadingState(itemCount: 5)),
        error: (e, _) => SafeArea(
          child: ErrorState(
            message: e.toString(),
            onRetry: () => ref.invalidate(restaurantDetailProvider(restaurantId)),
          ),
        ),
        data: (restaurant) {
          // Flatten all categories from all menus
          final allCategories = <Category>[];
          for (final menu in restaurant.menus) {
            allCategories.addAll(menu.categories);
          }

          if (allCategories.isEmpty) {
            return SafeArea(
              child: Column(
                children: [
                  _buildHeader(context, restaurant.name, restaurant.address),
                  const Expanded(
                    child: EmptyState(
                      icon: Icons.restaurant_menu_rounded,
                      title: 'Keine Menüeinträge',
                      subtitle: 'Dieses Restaurant hat noch keine Gerichte.',
                    ),
                  ),
                ],
              ),
            );
          }

          // Get dishes for selected category (with search filter)
          final safeIdx = selectedCategoryIdx.clamp(0, allCategories.length - 1);
          final currentCategory = allCategories[safeIdx];
          final dishes = _filterDishes(currentCategory.dishes, searchQuery);

          return SafeArea(
            child: RefreshIndicator(
              color: AppColors.primary,
              onRefresh: () async {
                ref.invalidate(restaurantDetailProvider(restaurantId));
              },
              child: CustomScrollView(
                slivers: [
                  // Header
                  SliverToBoxAdapter(
                    child: _buildHeader(
                      context,
                      restaurant.name,
                      restaurant.address,
                    ),
                  ),

                  // Search
                  SliverToBoxAdapter(
                    child: Padding(
                      padding: const EdgeInsets.symmetric(
                        horizontal: AppSpacing.screenPadding,
                      ),
                      child: AppTextField(
                        hintText: 'Gerichte suchen...',
                        prefixIcon: const Icon(Icons.search_rounded, size: 20),
                        onChanged: (val) {
                          ref.read(menuSearchQueryProvider.notifier).state = val;
                        },
                        suffixIcon: searchQuery.isNotEmpty
                            ? IconButton(
                                icon: const Icon(Icons.close_rounded, size: 18),
                                onPressed: () {
                                  ref.read(menuSearchQueryProvider.notifier).state = '';
                                },
                              )
                            : null,
                      ),
                    ),
                  ),

                  SliverToBoxAdapter(child: AppSpacing.verticalLg),

                  // Category chips
                  SliverToBoxAdapter(
                    child: SizedBox(
                      height: 42,
                      child: ListView.separated(
                        scrollDirection: Axis.horizontal,
                        padding: AppSpacing.screenH,
                        itemCount: allCategories.length,
                        separatorBuilder: (_, __) => AppSpacing.horizontalSm,
                        itemBuilder: (context, index) {
                          final cat = allCategories[index];
                          final isSelected = index == safeIdx;
                          return AppChip(
                            label: cat.name,
                            isSelected: isSelected,
                            onTap: () {
                              ref.read(selectedCategoryIndexProvider.notifier).state = index;
                            },
                          );
                        },
                      ),
                    ),
                  ),

                  SliverToBoxAdapter(child: AppSpacing.verticalLg),

                  // Dishes
                  if (dishes.isEmpty)
                    const SliverFillRemaining(
                      hasScrollBody: false,
                      child: EmptyState(
                        icon: Icons.search_off_rounded,
                        title: 'Keine Ergebnisse',
                        subtitle: 'Versuche einen anderen Suchbegriff.',
                      ),
                    )
                  else
                    SliverPadding(
                      padding: AppSpacing.screenH,
                      sliver: SliverList.separated(
                        itemCount: dishes.length,
                        separatorBuilder: (_, __) =>
                            const SizedBox(height: AppSpacing.md),
                        itemBuilder: (context, index) {
                          final dish = dishes[index];
                          return _DishCard(
                            dish: dish,
                            onTap: () => _showDishDetail(context, dish),
                          );
                        },
                      ),
                    ),

                  const SliverToBoxAdapter(
                    child: SizedBox(height: 40),
                  ),
                ],
              ),
            ),
          );
        },
      ),
    );
  }

  Widget _buildHeader(BuildContext context, String name, String? address) {
    return Padding(
      padding: const EdgeInsets.fromLTRB(
        AppSpacing.screenPadding,
        AppSpacing.lg,
        AppSpacing.screenPadding,
        AppSpacing.lg,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              GestureDetector(
                onTap: () => Navigator.of(context).maybePop(),
                child: Container(
                  width: 40,
                  height: 40,
                  decoration: BoxDecoration(
                    color: AppColors.card,
                    borderRadius: BorderRadius.circular(12),
                    border: Border.all(color: AppColors.borderLight),
                  ),
                  child: const Icon(
                    Icons.arrow_back_ios_rounded,
                    size: 18,
                    color: AppColors.text,
                  ),
                ),
              ),
              AppSpacing.horizontalMd,
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      name,
                      style: AppTypography.h2,
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                    if (address != null && address.isNotEmpty)
                      Text(
                        address,
                        style: AppTypography.captionMuted,
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
                  ],
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  List<Dish> _filterDishes(List<Dish> dishes, String query) {
    if (query.isEmpty) return dishes.where((d) => d.isAvailable).toList();
    final q = query.toLowerCase();
    return dishes
        .where((d) =>
            d.isAvailable &&
            (d.name.toLowerCase().contains(q) ||
                (d.description?.toLowerCase().contains(q) ?? false) ||
                d.dietaryTags.any((t) => t.toLowerCase().contains(q)) ||
                d.allergens.any((a) => a.toLowerCase().contains(q))))
        .toList();
  }

  void _showDishDetail(BuildContext context, Dish dish) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (_) => DishDetailSheet(dish: dish),
    );
  }
}

class _DishCard extends StatelessWidget {
  final Dish dish;
  final VoidCallback onTap;

  const _DishCard({required this.dish, required this.onTap});

  @override
  Widget build(BuildContext context) {
    return CardContainer(
      onTap: onTap,
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Image or placeholder
          if (dish.imageUrl != null)
            ClipRRect(
              borderRadius: BorderRadius.circular(12),
              child: Image.network(
                dish.imageUrl!,
                width: 72,
                height: 72,
                fit: BoxFit.cover,
                errorBuilder: (_, __, ___) => _imagePlaceholder(),
              ),
            )
          else
            _imagePlaceholder(),
          AppSpacing.horizontalMd,
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  dish.name,
                  style: AppTypography.h3.copyWith(fontSize: 16),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
                if (dish.description != null && dish.description!.isNotEmpty) ...[
                  const SizedBox(height: 4),
                  Text(
                    dish.description!,
                    style: AppTypography.smallMuted,
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                ],
                if (dish.dietaryTags.isNotEmpty || dish.allergens.isNotEmpty) ...[
                  const SizedBox(height: 8),
                  Wrap(
                    spacing: 4,
                    runSpacing: 4,
                    children: [
                      for (final tag in dish.dietaryTags)
                        AppBadge.vegan(label: tag),
                      for (final allergen in dish.allergens.take(2))
                        AppBadge.allergen(label: allergen),
                    ],
                  ),
                ],
              ],
            ),
          ),
          AppSpacing.horizontalSm,
          Text(
            dish.formattedPrice,
            style: AppTypography.body.copyWith(
              fontWeight: FontWeight.w700,
              color: AppColors.primary,
            ),
          ),
        ],
      ),
    );
  }

  Widget _imagePlaceholder() {
    return Container(
      width: 72,
      height: 72,
      decoration: BoxDecoration(
        color: AppColors.chipBackground,
        borderRadius: BorderRadius.circular(12),
      ),
      child: const Icon(
        Icons.restaurant_rounded,
        color: AppColors.primary,
        size: 28,
      ),
    );
  }
}
