import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../../core/theme/app_colors.dart';
import '../../../core/theme/app_spacing.dart';
import '../../../core/theme/app_typography.dart';
import '../../../shared/widgets/app_scaffold.dart';
import '../../../shared/widgets/app_text_field.dart';
import '../../../shared/widgets/primary_button.dart';

class CodeInputScreen extends StatefulWidget {
  const CodeInputScreen({super.key});

  @override
  State<CodeInputScreen> createState() => _CodeInputScreenState();
}

class _CodeInputScreenState extends State<CodeInputScreen> {
  final _codeController = TextEditingController();
  String? _errorText;

  @override
  void dispose() {
    _codeController.dispose();
    super.dispose();
  }

  void _handleSubmit() {
    final code = _codeController.text.trim();

    if (code.isEmpty) {
      setState(() => _errorText = 'Bitte gib einen Code ein.');
      return;
    }

    if (code.length < 2) {
      setState(() => _errorText = 'Der Code ist zu kurz.');
      return;
    }

    setState(() => _errorText = null);

    // Try to parse as restaurant ID
    final id = int.tryParse(code);
    if (id != null) {
      context.push('/menu/$id');
      return;
    }

    // Otherwise treat as slug
    // In production this would do an API lookup
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text('Restaurant-Code: $code'),
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(AppSpacing.buttonRadius),
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return AppScaffold(
      title: 'Code eingeben',
      showBackButton: true,
      body: Padding(
        padding: AppSpacing.screenAll,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            AppSpacing.verticalXl,

            // Icon
            Center(
              child: Container(
                width: 72,
                height: 72,
                decoration: BoxDecoration(
                  color: AppColors.chipBackground,
                  borderRadius: BorderRadius.circular(20),
                ),
                child: const Icon(
                  Icons.keyboard_alt_outlined,
                  size: 32,
                  color: AppColors.primary,
                ),
              ),
            ),
            AppSpacing.verticalXl,

            Center(
              child: Text(
                'Restaurant-Code eingeben',
                style: AppTypography.h2,
                textAlign: TextAlign.center,
              ),
            ),
            AppSpacing.verticalSm,
            Center(
              child: Text(
                'Gib den Code ein, der auf dem Tisch oder der Karte steht.',
                style: AppTypography.bodyMuted,
                textAlign: TextAlign.center,
              ),
            ),
            const SizedBox(height: 32),

            AppTextField(
              controller: _codeController,
              hintText: 'z.B. 12345 oder restaurant-slug',
              errorText: _errorText,
              textInputAction: TextInputAction.done,
              onSubmitted: (_) => _handleSubmit(),
              onChanged: (_) {
                if (_errorText != null) setState(() => _errorText = null);
              },
              prefixIcon: const Icon(Icons.tag_rounded, size: 20),
            ),
            AppSpacing.verticalXl,

            PrimaryButton(
              label: 'Menü öffnen',
              icon: Icons.arrow_forward_rounded,
              onPressed: _handleSubmit,
            ),
          ],
        ),
      ),
    );
  }
}
