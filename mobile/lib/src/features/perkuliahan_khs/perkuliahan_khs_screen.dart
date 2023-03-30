import 'package:flutter/material.dart';
import 'package:mobile/src/common_widgets/aplikasi_bar.dart';
import 'package:mobile/src/common_widgets/drawer_perkuliahan.dart';
import 'package:mobile/src/features/authentication/model/user_argument.dart';

class PerkuliahanKhsScreen extends StatelessWidget {
  const PerkuliahanKhsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final args = ModalRoute.of(context)!.settings.arguments as UserArgument1;

    return Scaffold(
      appBar: const AplikasiBar(
        title: "KARTU HASIL STUDI",
      ),
      backgroundColor: Colors.blue.shade400,
      body: null,
      drawer: DrawerPerkuliahan(username: args.username, email: args.email),
    );
  }
}
