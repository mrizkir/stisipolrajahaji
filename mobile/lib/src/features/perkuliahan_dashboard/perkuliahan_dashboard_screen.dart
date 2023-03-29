import 'package:flutter/material.dart';
import 'package:mobile/src/common_widgets/drawer_perkuliahan.dart';
import 'package:mobile/src/features/authentication/model/user_argument.dart';

class PerkuliahanDashboardScreen extends StatelessWidget {
  const PerkuliahanDashboardScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final aplikasiBar = AppBar(
      title: const Text("PERKULIAHAN"),
      backgroundColor: Colors.green.shade300,
    );

    final args = ModalRoute.of(context)!.settings.arguments as UserArgument1;

    return Scaffold(
      appBar: aplikasiBar,
      backgroundColor: Colors.blue.shade400,
      body: null,
      drawer: DrawerPerkuliahan(username: args.username, email: args.email),
    );
  }
}
