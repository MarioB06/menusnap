import 'package:flutter/material.dart';
import '../../core/theme/app_colors.dart';
import '../../core/theme/app_shadows.dart';
import '../../core/theme/app_spacing.dart';

class CardContainer extends StatelessWidget {
  final Widget child;
  final EdgeInsets? padding;
  final VoidCallback? onTap;
  final double? borderRadius;
  final Color? color;
  final bool showBorder;

  const CardContainer({
    super.key,
    required this.child,
    this.padding,
    this.onTap,
    this.borderRadius,
    this.color,
    this.showBorder = false,
  });

  @override
  Widget build(BuildContext context) {
    final radius = borderRadius ?? AppSpacing.cardRadius;
    return Container(
      decoration: BoxDecoration(
        color: color ?? AppColors.card,
        borderRadius: BorderRadius.circular(radius),
        boxShadow: AppShadows.card,
        border: showBorder
            ? Border.all(color: AppColors.borderLight)
            : null,
      ),
      child: Material(
        color: Colors.transparent,
        borderRadius: BorderRadius.circular(radius),
        child: InkWell(
          onTap: onTap,
          borderRadius: BorderRadius.circular(radius),
          child: Padding(
            padding: padding ?? AppSpacing.cardAll,
            child: child,
          ),
        ),
      ),
    );
  }
}
