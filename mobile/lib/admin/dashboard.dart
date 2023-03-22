import 'package:flutter/material.dart';

import '../components/panelinformasi.dart';
import 'package:mobile/helpers/helperstorage.dart';

class Dashboard extends StatelessWidget {
  const Dashboard({super.key});
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
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceEvenly,
          children: [
            PanelInformasi(
              widthApp: widthApp,
            ),
            PanelInformasi(
              widthApp: widthApp,
            ),
            PanelInformasi(
              widthApp: widthApp,
            ),
          ],
        ),
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () async {
          var token = await HelperStorage.getToken();
          print(token);
        },
        child: const Icon(Icons.exit_to_app),
      ),
      floatingActionButtonLocation: FloatingActionButtonLocation.centerFloat,
    );
  }
}
