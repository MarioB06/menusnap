import 'package:flutter/material.dart';
import '../../core/theme/app_colors.dart';

class AppScaffold extends StatelessWidget {
  final String? title;
  final Widget body;
  final List<Widget>? actions;
  final Widget? floatingActionButton;
  final bool showBackButton;
  final PreferredSizeWidget? bottom;
  final Color? backgroundColor;

  const AppScaffold({
    super.key,
    this.title,
    required this.body,
    this.actions,
    this.floatingActionButton,
    this.showBackButton = false,
    this.bottom,
    this.backgroundColor,
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: backgroundColor ?? AppColors.background,
      appBar: title != null
          ? AppBar(
              title: Text(
                title!,
                style: const TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.w600,
                ),
              ),
              leading: showBackButton
                  ? IconButton(
                      icon: const Icon(Icons.arrow_back_ios_rounded, size: 20),
                      onPressed: () => Navigator.of(context).maybePop(),
                    )
                  : null,
              automaticallyImplyLeading: showBackButton,
              actions: actions,
              bottom: bottom,
            )
          : null,
      body: SafeArea(child: body),
      floatingActionButton: floatingActionButton,
    );
  }
}
