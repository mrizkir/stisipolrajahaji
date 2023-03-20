import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'dart:io';

import 'admin/dashboard.dart';
import 'admin/perkuliahan/khs.dart';
import 'auth/login.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  ByteData data =
      await PlatformAssetBundle().load('assets/ca/lets-encrypt-r3.pem');
  SecurityContext.defaultContext
      .setTrustedCertificatesBytes(data.buffer.asUint8List());

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
