import 'package:flutter/material.dart';

class DrawerFilterMode8 extends StatefulWidget {
  const DrawerFilterMode8({super.key});

  @override
  State<StatefulWidget> createState() {
    return _DrawerFilterMode8();
  }
}

class _DrawerFilterMode8 extends State<DrawerFilterMode8> {
  @override
  Widget build(BuildContext context) {
    return Drawer(
      child: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: <Widget>[
            DropdownButton(items: [], onChanged: (val) {}),
          ],
        ),
      ),
    );
  }
}
