import 'package:flutter/material.dart';

class DrawerPerkuliahan extends StatelessWidget {
  final String username;
  final String email;

  const DrawerPerkuliahan(
      {super.key, required this.username, required this.email});

  @override
  Widget build(BuildContext context) {
    return Drawer(
      child: ListView(
        children: [
          UserAccountsDrawerHeader(
              accountName: Text(username), accountEmail: Text(email))
        ],
      ),
    );
  }
}
