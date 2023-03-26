import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:mobile/src/common_widgets/menu_card.dart';

class DashboardScreen extends StatelessWidget {
  const DashboardScreen({super.key});
  @override
  Widget build(BuildContext context) {
    final aplikasiBar = AppBar(
      title: const Text("MENU UTAMA"),
      backgroundColor: Colors.green.shade300,
    );

    return Scaffold(
      appBar: aplikasiBar,
      backgroundColor: Colors.blue.shade400,
      body: Container(
        padding: EdgeInsets.all(30),
        child: GridView.count(crossAxisCount: 2, children: [
          MenuCard(
            title: "PERKULIAHAN",
            icon: Icons.school,
            warnaIcon: Colors.orange,
          ),
        ]),
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {
          SystemChannels.platform.invokeMethod('SystemNavigator.pop');
        },
        tooltip: 'Keluar',
        child: const Icon(Icons.exit_to_app),
      ),
      floatingActionButtonLocation: FloatingActionButtonLocation.centerFloat,
    );
  }
}
