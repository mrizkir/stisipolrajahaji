import 'package:flutter/material.dart';
import 'admin/dashboard.dart';
import 'admin/perkuliahan/khs.dart';
import 'auth/login.dart';

void main() {
  runApp(const PortalEkampusApp());
}

class PortalEkampusApp extends StatelessWidget {
  const PortalEkampusApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      home: const Login(),
      initialRoute: '/login',
      routes: {
        '/login': (context) => const Login(),
        '/admin/dashboard': (context) => const Dashboard(),
        '/admin/perkuliahan/khs': (context) => const KHS(),
      },
    );
  }
}
