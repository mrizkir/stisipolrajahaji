import 'package:flutter/material.dart';

class DrawerItemPerkuliahan extends StatelessWidget {
  final String name;
  final IconData icon;
  final Function() onPressed;

  const DrawerItemPerkuliahan(
      {super.key,
      required this.name,
      required this.icon,
      required this.onPressed});

  @override
  Widget build(BuildContext context) {
    return InkWell(
        onTap: onPressed,
        child: SizedBox(
          height: 40,
          child: Row(children: [
            Icon(icon, size: 20, color: Colors.green),
            const SizedBox(
              width: 40,
            ),
            Text(name,
                style: const TextStyle(fontSize: 12, color: Colors.green))
          ]),
        ));
  }
}
