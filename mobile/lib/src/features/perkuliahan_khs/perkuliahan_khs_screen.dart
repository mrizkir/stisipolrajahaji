import 'package:flutter/material.dart';
import 'package:mobile/src/common_widgets/aplikasi_bar.dart';
import 'package:mobile/src/common_widgets/bottom_navigation.dart';
import 'package:mobile/src/common_widgets/drawer_filter_mode_8.dart';
import 'package:mobile/src/common_widgets/drawer_perkuliahan.dart';
import 'package:mobile/src/features/authentication/model/user_argument.dart';

import 'perkuliahan_khs_state.dart';

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
      body: const PerkuliahanKhsState(),
      drawer: DrawerPerkuliahan(username: args.username, email: args.email),
      endDrawer: const DrawerFilterMode8(),
      bottomNavigationBar: const BottomNavigation(),
    );
  }
}
