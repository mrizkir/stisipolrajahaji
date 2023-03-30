import 'package:flutter/material.dart';
import 'package:mobile/src/common_widgets/drawer_item_perkuliahan.dart';

class DrawerPerkuliahan extends StatelessWidget {
  final String username;
  final String email;

  const DrawerPerkuliahan(
      {super.key, required this.username, required this.email});

  @override
  Widget build(BuildContext context) {
    return Drawer(
      child: Column(
        children: [
          UserAccountsDrawerHeader(
              accountName: Text(username), accountEmail: Text(email)),
          DrawerItemPerkuliahan(
              name: 'KHS',
              icon: Icons.abc_sharp,
              onPressed: () => onItemPressed(context, index: 0))
        ],
      ),
    );
  }

  void onItemPressed(BuildContext context, {required int index}) {
    print(index);
  }
}
