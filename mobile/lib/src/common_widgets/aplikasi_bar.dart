import 'package:flutter/material.dart';

class AplikasiBar extends StatelessWidget implements PreferredSizeWidget {
  final String? title;
  const AplikasiBar({super.key, this.title});

  @override
  Widget build(BuildContext context) {
    return AppBar(
      title: Text('$title'),
      backgroundColor: Colors.green.shade300,
      centerTitle: true,
      elevation: 0,
    );
  }

  @override
  Size get preferredSize => const Size.fromHeight(60.0);
}
