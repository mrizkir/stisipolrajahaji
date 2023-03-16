import 'package:flutter/material.dart';

class PanelInformasi extends StatelessWidget {
  final double widthApp;
  const PanelInformasi({super.key, required this.widthApp});

  @override
  Widget build(BuildContext context) {
    return LayoutBuilder(builder: (context, constraints) {
      return Container(
          width: widthApp * 0.25,
          height: constraints.maxHeight * 0.6,
          color: Colors.amber);
    });
  }
}
