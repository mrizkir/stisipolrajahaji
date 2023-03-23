import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'dart:io';

import 'src/routing/routes_app.dart';

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
      initialRoute: '/authentication/login',
      routes: routesApp,
    );
  }
}
