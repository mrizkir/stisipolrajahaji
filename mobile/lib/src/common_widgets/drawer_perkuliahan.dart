import 'package:flutter/material.dart';
import 'package:mobile/src/common_widgets/drawer_item_perkuliahan.dart';
import 'package:mobile/src/features/authentication/data/user_repository.dart';
import 'package:mobile/src/features/authentication/model/user.dart';
import 'package:mobile/src/features/authentication/model/user_argument.dart';

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

  void onItemPressed(BuildContext context, {required int index}) async {
    final navigator = Navigator.of(context);
    User user = await UserRepository.getUser();
    String? token = await UserRepository.getToken();

    var url = '/perkuliahan';
    switch (index) {
      case 0:
        url = '/perkuliahan/khs';
        break;
    }

    navigator.pushNamed(url,
        arguments: UserArgument1(
            user.userid, user.username, user.email, token.toString()));
  }
}
