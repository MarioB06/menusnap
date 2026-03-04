import 'package:flutter/material.dart';

abstract final class AppSpacing {
  // Base grid: 8px
  static const double xs = 4;
  static const double sm = 8;
  static const double md = 12;
  static const double lg = 16;
  static const double xl = 24;
  static const double xxl = 32;
  static const double xxxl = 48;

  // Screen
  static const double screenPadding = 16;
  static const double sectionSpacing = 24;

  // Card
  static const double cardPadding = 16;
  static const double cardRadius = 16;

  // Button / Input
  static const double buttonRadius = 12;
  static const double buttonHeight = 48;
  static const double inputRadius = 12;

  // Helpers
  static const screenH = EdgeInsets.symmetric(horizontal: screenPadding);
  static const screenAll = EdgeInsets.all(screenPadding);
  static const cardAll = EdgeInsets.all(cardPadding);

  static const verticalXs = SizedBox(height: xs);
  static const verticalSm = SizedBox(height: sm);
  static const verticalMd = SizedBox(height: md);
  static const verticalLg = SizedBox(height: lg);
  static const verticalXl = SizedBox(height: xl);
  static const verticalXxl = SizedBox(height: xxl);

  static const horizontalXs = SizedBox(width: xs);
  static const horizontalSm = SizedBox(width: sm);
  static const horizontalMd = SizedBox(width: md);
  static const horizontalLg = SizedBox(width: lg);
}
