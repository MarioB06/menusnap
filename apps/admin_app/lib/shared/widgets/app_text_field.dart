import 'package:flutter/material.dart';
import '../../core/theme/app_colors.dart';
import '../../core/theme/app_spacing.dart';
import '../../core/theme/app_typography.dart';

class AppTextField extends StatelessWidget {
  final TextEditingController? controller;
  final String? hintText;
  final String? labelText;
  final String? errorText;
  final bool obscureText;
  final TextInputType? keyboardType;
  final TextInputAction? textInputAction;
  final ValueChanged<String>? onChanged;
  final VoidCallback? onEditingComplete;
  final ValueChanged<String>? onSubmitted;
  final Widget? prefixIcon;
  final Widget? suffixIcon;
  final int maxLines;
  final bool autofocus;
  final FocusNode? focusNode;

  const AppTextField({
    super.key,
    this.controller,
    this.hintText,
    this.labelText,
    this.errorText,
    this.obscureText = false,
    this.keyboardType,
    this.textInputAction,
    this.onChanged,
    this.onEditingComplete,
    this.onSubmitted,
    this.prefixIcon,
    this.suffixIcon,
    this.maxLines = 1,
    this.autofocus = false,
    this.focusNode,
  });

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      mainAxisSize: MainAxisSize.min,
      children: [
        if (labelText != null) ...[
          Text(labelText!, style: AppTypography.small.copyWith(
            fontWeight: FontWeight.w500,
          )),
          const SizedBox(height: 6),
        ],
        TextField(
          controller: controller,
          obscureText: obscureText,
          keyboardType: keyboardType,
          textInputAction: textInputAction,
          onChanged: onChanged,
          onEditingComplete: onEditingComplete,
          onSubmitted: onSubmitted,
          maxLines: maxLines,
          autofocus: autofocus,
          focusNode: focusNode,
          style: AppTypography.body,
          decoration: InputDecoration(
            hintText: hintText,
            prefixIcon: prefixIcon,
            suffixIcon: suffixIcon,
            errorText: errorText,
            errorStyle: AppTypography.caption.copyWith(
              color: AppColors.error,
            ),
            contentPadding: const EdgeInsets.symmetric(
              horizontal: AppSpacing.lg,
              vertical: 14,
            ),
          ),
        ),
      ],
    );
  }
}
