import 'package:flutter/material.dart';
import 'package:mobile/src/features/authentication/data/user_repository.dart';
import 'package:mobile/src/features/authentication/model/user.dart';

class DrawerPerkuliahan extends StatefulWidget {
  const DrawerPerkuliahan({super.key});

  @override
  State<DrawerPerkuliahan> createState() => _DrawerPerkuliahan();
}

class _DrawerPerkuliahan extends State<DrawerPerkuliahan> {
  late User user;

  void setDataUser() async {
    User temp = await UserRepository.getUser();
    setState(() {
      user = temp;
    });
  }

  @override
  void initState() {
    setDataUser();
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    String username = user.username;
    String email = user.email;

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
