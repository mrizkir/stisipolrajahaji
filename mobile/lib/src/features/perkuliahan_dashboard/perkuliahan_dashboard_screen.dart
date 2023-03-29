import 'package:flutter/material.dart';
import 'package:mobile/src/common_widgets/drawer_perkuliahan.dart';

class PerkuliahanDashboardScreen extends StatelessWidget {
  const PerkuliahanDashboardScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final aplikasiBar = AppBar(
      title: const Text("PERKULIAHAN"),
      backgroundColor: Colors.green.shade300,
    );

    return Scaffold(
      appBar: aplikasiBar,
      backgroundColor: Colors.blue.shade400,
      body: null,
      drawer: const DrawerPerkuliahan(),
    );
  }
}
