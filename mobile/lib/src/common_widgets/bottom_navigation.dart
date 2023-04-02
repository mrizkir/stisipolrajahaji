import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:logger/logger.dart';
import 'package:mobile/src/utils/network/rest.dart';

class BottomNavigation extends StatelessWidget {
  const BottomNavigation({super.key});
  @override
  Widget build(BuildContext context) {
    return BottomNavigationBar(
      onTap: (index) async {
        final navigator = Navigator.of(context);
        switch (index) {
          case 0:
            navigator.pushReplacementNamed('/dashboard');
            break;
          case 1:
            Rest.httPostWithToken('/auth/logout', {}).then((value) {
              SystemChannels.platform.invokeMethod('SystemNavigator.pop');
            }).catchError((e) {
              Logger().e(e);
              SystemChannels.platform.invokeMethod('SystemNavigator.pop');
            });
            break;
        }
      },
      selectedItemColor: Colors.black54,
      unselectedItemColor: Colors.grey.withOpacity(0.5),
      showSelectedLabels: false,
      showUnselectedLabels: false,
      elevation: 0,
      items: [
        BottomNavigationBarItem(label: "Beranda", icon: Icon(Icons.home)),
        BottomNavigationBarItem(label: "Keluar", icon: Icon(Icons.exit_to_app)),
      ],
    );
  }
}
