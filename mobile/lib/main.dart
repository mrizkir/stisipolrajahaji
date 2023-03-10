import 'package:flutter/material.dart';

class PortalEkampusApp extends StatelessWidget {
  const PortalEkampusApp({super.key});
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      home: Scaffold(
          appBar: AppBar(title: const Text("PortalEkampus")),
          body: const Center(
            child: Text("Selamat datang di PortalEkampus"),
          )),
    );
  }
}

void main() {
  runApp(const PortalEkampusApp());
}
