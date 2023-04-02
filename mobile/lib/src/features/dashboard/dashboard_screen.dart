import 'package:flutter/material.dart';
import 'package:mobile/src/common_widgets/aplikasi_bar.dart';
import 'package:mobile/src/common_widgets/bottom_navigation.dart';
import 'package:mobile/src/common_widgets/menu_card.dart';

class DashboardScreen extends StatelessWidget {
  const DashboardScreen({super.key});
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: const AplikasiBar(
        title: "MENU UTAMA",
      ),
      backgroundColor: Colors.blue.shade400,
      body: Container(
        padding: const EdgeInsets.all(30),
        child: GridView.count(crossAxisCount: 2, children: [
          MenuCard(
            title: "PERKULIAHAN",
            icon: Icons.school,
            warnaIcon: Colors.orange,
            url: '/perkuliahan',
          ),
        ]),
      ),
      bottomNavigationBar: const BottomNavigation(),
    );
  }
}
