import 'package:flutter/material.dart';

abstract final class AppColors {
  // Brand
  static const primary = Color(0xFF4F46E5);
  static const accent = Color(0xFF7C3AED);
  static const positive = Color(0xFF34D399);

  // Gradient
  static const gradientStart = primary;
  static const gradientEnd = accent;
  static const primaryGradient = LinearGradient(
    colors: [gradientStart, gradientEnd],
    begin: Alignment.centerLeft,
    end: Alignment.centerRight,
  );

  // Light Neutrals
  static const background = Color(0xFFF9FAFB);
  static const card = Color(0xFFFFFFFF);
  static const border = Color(0xFFE5E7EB);
  static const borderLight = Color(0xFFF3F4F6);
  static const text = Color(0xFF111827);
  static const textMuted = Color(0xFF6B7280);
  static const textLight = Color(0xFF9CA3AF);

  // Semantic
  static const error = Color(0xFFEF4444);
  static const warning = Color(0xFFF59E0B);
  static const info = Color(0xFF3B82F6);

  // Chip / Badge
  static const chipBackground = Color(0xFFEEF2FF);
  static const chipText = primary;
}
