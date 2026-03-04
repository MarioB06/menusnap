import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:mobile_scanner/mobile_scanner.dart';
import '../../../core/theme/app_colors.dart';
import '../../../core/theme/app_spacing.dart';
import '../../../core/theme/app_typography.dart';
import '../../../shared/models/recent_menu.dart';
import '../../home/presentation/recents_provider.dart';

class ScanScreen extends ConsumerStatefulWidget {
  const ScanScreen({super.key});

  @override
  ConsumerState<ScanScreen> createState() => _ScanScreenState();
}

class _ScanScreenState extends ConsumerState<ScanScreen> {
  final MobileScannerController _scannerController = MobileScannerController(
    detectionSpeed: DetectionSpeed.normal,
    facing: CameraFacing.back,
  );
  bool _hasScanned = false;
  bool _torchEnabled = false;

  @override
  void dispose() {
    _scannerController.dispose();
    super.dispose();
  }

  void _onDetect(BarcodeCapture capture) {
    if (_hasScanned) return;
    final barcodes = capture.barcodes;
    if (barcodes.isEmpty) return;

    final rawValue = barcodes.first.rawValue;
    if (rawValue == null || rawValue.isEmpty) return;

    setState(() => _hasScanned = true);
    _handleScannedValue(rawValue);
  }

  void _handleScannedValue(String value) {
    // Try to parse as URL: /menu/{slug}/{uuid}
    final uri = Uri.tryParse(value);
    if (uri != null && uri.pathSegments.length >= 2) {
      final segments = uri.pathSegments;
      // Look for /menu/{slug}/{uuid} pattern
      final menuIndex = segments.indexOf('menu');
      if (menuIndex >= 0 && menuIndex + 1 < segments.length) {
        final slug = segments[menuIndex + 1];
        final uuid = segments.length > menuIndex + 2
            ? segments[menuIndex + 2]
            : null;

        // Save to recents
        ref.read(recentsProvider.notifier).addRecent(RecentMenu(
              restaurantName: slug,
              slug: slug,
              tableUuid: uuid,
              openedAt: DateTime.now(),
            ));

        // Navigate — for now go to code input with the slug
        // In production, this would resolve the restaurant ID from slug
        if (mounted) {
          context.pop();
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text('Restaurant gefunden: $slug'),
              behavior: SnackBarBehavior.floating,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(AppSpacing.buttonRadius),
              ),
            ),
          );
        }
        return;
      }
    }

    // Try as simple code/ID
    final id = int.tryParse(value);
    if (id != null && mounted) {
      context.pop();
      context.push('/menu/$id');
      return;
    }

    // Unknown format
    if (mounted) {
      setState(() => _hasScanned = false);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: const Text('QR-Code nicht erkannt. Bitte erneut versuchen.'),
          behavior: SnackBarBehavior.floating,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(AppSpacing.buttonRadius),
          ),
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      body: Stack(
        children: [
          // Camera
          MobileScanner(
            controller: _scannerController,
            onDetect: _onDetect,
          ),

          // Overlay
          _ScannerOverlay(),

          // Top bar
          Positioned(
            top: MediaQuery.of(context).padding.top + 8,
            left: 8,
            right: 8,
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                _CircleButton(
                  icon: Icons.close_rounded,
                  onTap: () => context.pop(),
                ),
                _CircleButton(
                  icon: _torchEnabled
                      ? Icons.flash_on_rounded
                      : Icons.flash_off_rounded,
                  onTap: () {
                    _scannerController.toggleTorch();
                    setState(() => _torchEnabled = !_torchEnabled);
                  },
                ),
              ],
            ),
          ),

          // Bottom hint
          Positioned(
            bottom: MediaQuery.of(context).padding.bottom + 48,
            left: 40,
            right: 40,
            child: Column(
              children: [
                Container(
                  padding: const EdgeInsets.symmetric(
                    horizontal: AppSpacing.lg,
                    vertical: AppSpacing.sm + 2,
                  ),
                  decoration: BoxDecoration(
                    color: Colors.black54,
                    borderRadius: BorderRadius.circular(AppSpacing.buttonRadius),
                  ),
                  child: Text(
                    'Richte die Kamera auf den QR-Code',
                    style: AppTypography.small.copyWith(color: Colors.white),
                    textAlign: TextAlign.center,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

class _CircleButton extends StatelessWidget {
  final IconData icon;
  final VoidCallback onTap;

  const _CircleButton({required this.icon, required this.onTap});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 44,
        height: 44,
        decoration: BoxDecoration(
          color: Colors.black45,
          borderRadius: BorderRadius.circular(22),
        ),
        child: Icon(icon, color: Colors.white, size: 22),
      ),
    );
  }
}

class _ScannerOverlay extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return CustomPaint(
      size: MediaQuery.of(context).size,
      painter: _ScannerOverlayPainter(),
    );
  }
}

class _ScannerOverlayPainter extends CustomPainter {
  @override
  void paint(Canvas canvas, Size size) {
    final scanArea = Rect.fromCenter(
      center: Offset(size.width / 2, size.height / 2 - 40),
      width: 260,
      height: 260,
    );

    // Dark overlay
    final backgroundPath = Path()
      ..addRect(Rect.fromLTWH(0, 0, size.width, size.height));
    final cutoutPath = Path()
      ..addRRect(RRect.fromRectAndRadius(scanArea, const Radius.circular(24)));
    final overlayPath = Path.combine(
      PathOperation.difference,
      backgroundPath,
      cutoutPath,
    );
    canvas.drawPath(
      overlayPath,
      Paint()..color = Colors.black.withOpacity(0.55),
    );

    // Corner lines
    final cornerPaint = Paint()
      ..color = AppColors.primary
      ..strokeWidth = 3.5
      ..style = PaintingStyle.stroke
      ..strokeCap = StrokeCap.round;

    const cornerLength = 32.0;
    const r = 24.0;

    // Top-left
    canvas.drawArc(
      Rect.fromLTWH(scanArea.left, scanArea.top, r * 2, r * 2),
      3.14159, // pi
      1.5708,  // pi/2
      false,
      cornerPaint,
    );
    canvas.drawLine(
      Offset(scanArea.left, scanArea.top + r),
      Offset(scanArea.left, scanArea.top + r + cornerLength),
      cornerPaint,
    );
    canvas.drawLine(
      Offset(scanArea.left + r, scanArea.top),
      Offset(scanArea.left + r + cornerLength, scanArea.top),
      cornerPaint,
    );

    // Top-right
    canvas.drawArc(
      Rect.fromLTWH(scanArea.right - r * 2, scanArea.top, r * 2, r * 2),
      -1.5708,
      1.5708,
      false,
      cornerPaint,
    );
    canvas.drawLine(
      Offset(scanArea.right, scanArea.top + r),
      Offset(scanArea.right, scanArea.top + r + cornerLength),
      cornerPaint,
    );
    canvas.drawLine(
      Offset(scanArea.right - r, scanArea.top),
      Offset(scanArea.right - r - cornerLength, scanArea.top),
      cornerPaint,
    );

    // Bottom-left
    canvas.drawArc(
      Rect.fromLTWH(scanArea.left, scanArea.bottom - r * 2, r * 2, r * 2),
      1.5708,
      1.5708,
      false,
      cornerPaint,
    );
    canvas.drawLine(
      Offset(scanArea.left, scanArea.bottom - r),
      Offset(scanArea.left, scanArea.bottom - r - cornerLength),
      cornerPaint,
    );
    canvas.drawLine(
      Offset(scanArea.left + r, scanArea.bottom),
      Offset(scanArea.left + r + cornerLength, scanArea.bottom),
      cornerPaint,
    );

    // Bottom-right
    canvas.drawArc(
      Rect.fromLTWH(
          scanArea.right - r * 2, scanArea.bottom - r * 2, r * 2, r * 2),
      0,
      1.5708,
      false,
      cornerPaint,
    );
    canvas.drawLine(
      Offset(scanArea.right, scanArea.bottom - r),
      Offset(scanArea.right, scanArea.bottom - r - cornerLength),
      cornerPaint,
    );
    canvas.drawLine(
      Offset(scanArea.right - r, scanArea.bottom),
      Offset(scanArea.right - r - cornerLength, scanArea.bottom),
      cornerPaint,
    );
  }

  @override
  bool shouldRepaint(covariant CustomPainter oldDelegate) => false;
}
