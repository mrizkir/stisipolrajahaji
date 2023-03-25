import 'package:flutter/material.dart';
import 'package:mobile/src/features/authentication/data/user_repository.dart';

class DashboardScreen extends StatelessWidget {
  const DashboardScreen({super.key});
  @override
  Widget build(BuildContext context) {
    final widthApp = MediaQuery.of(context).size.width;
    final heightApp = MediaQuery.of(context).size.height;
    final paddingTop = MediaQuery.of(context).padding.top;

    final aplikasiBar = AppBar(
      title: const Text("Dashboard"),
      leading: Container(),
    );

    final heightBody =
        heightApp - paddingTop - aplikasiBar.preferredSize.height;

    return Scaffold(
      appBar: aplikasiBar,
      body: Container(
        width: widthApp,
        height: heightBody * 0.3,
        color: Colors.grey,
        child: Column(
          mainAxisAlignment: MainAxisAlignment.spaceEvenly,
          children: [Text("Selamat Datang")],
        ),
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () async {
          var token = await UserRepository.getToken();
          print(token);
        },
        child: const Icon(Icons.exit_to_app),
      ),
      floatingActionButtonLocation: FloatingActionButtonLocation.centerFloat,
    );
  }
}
