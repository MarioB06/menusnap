import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'app_colors.dart';

abstract final class AppTypography {
  static TextStyle get _base => GoogleFonts.inter(color: AppColors.text);

  // Headings
  static TextStyle get h1 => _base.copyWith(
        fontSize: 28,
        fontWeight: FontWeight.w700,
        height: 1.3,
      );

  static TextStyle get h2 => _base.copyWith(
        fontSize: 22,
        fontWeight: FontWeight.w600,
        height: 1.3,
      );

  static TextStyle get h3 => _base.copyWith(
        fontSize: 18,
        fontWeight: FontWeight.w600,
        height: 1.4,
      );

  // Body
  static TextStyle get body => _base.copyWith(
        fontSize: 16,
        fontWeight: FontWeight.w400,
        height: 1.5,
      );

  static TextStyle get bodyMuted => body.copyWith(
        color: AppColors.textMuted,
      );

  // Small
  static TextStyle get small => _base.copyWith(
        fontSize: 14,
        fontWeight: FontWeight.w400,
        height: 1.5,
      );

  static TextStyle get smallMuted => small.copyWith(
        color: AppColors.textMuted,
      );

  // Caption
  static TextStyle get caption => _base.copyWith(
        fontSize: 12,
        fontWeight: FontWeight.w500,
        height: 1.4,
      );

  static TextStyle get captionMuted => caption.copyWith(
        color: AppColors.textMuted,
      );
}
