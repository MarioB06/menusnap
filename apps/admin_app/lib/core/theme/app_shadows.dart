import 'package:flutter/material.dart';

abstract final class AppShadows {
  static const card = [
    BoxShadow(
      color: Color(0x0D000000), // ~5% opacity
      blurRadius: 8,
      offset: Offset(0, 2),
    ),
  ];

  static const cardHover = [
    BoxShadow(
      color: Color(0x14000000), // ~8% opacity
      blurRadius: 16,
      offset: Offset(0, 4),
    ),
  ];

  static const elevated = [
    BoxShadow(
      color: Color(0x1A000000), // ~10% opacity
      blurRadius: 24,
      offset: Offset(0, 8),
    ),
  ];
}
