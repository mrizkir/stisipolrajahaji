import 'package:flutter/material.dart';
import 'package:mobile/src/features/authentication/data/user_repository.dart';
import 'package:mobile/src/features/authentication/model/user.dart';
import 'package:mobile/src/features/authentication/model/user_argument.dart';

class MenuCard extends StatelessWidget {
  final String title;
  final IconData icon;
  final MaterialColor warnaIcon;
  final String url;

  const MenuCard(
      {super.key,
      required this.title,
      required this.icon,
      required this.warnaIcon,
      required this.url});
  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.all(8),
      child: InkWell(
        onTap: () async {
          final navigator = Navigator.of(context);
          User user = await UserRepository.getUser();
          String? token = await UserRepository.getToken();

          navigator.pushNamed(url,
              arguments: UserArgument1(
                  user.userid, user.username, user.email, token.toString()));
        },
        splashColor: Colors.amber.shade100,
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, size: 70, color: warnaIcon),
            Text(
              title,
              style: const TextStyle(fontSize: 17),
            )
          ],
        ),
      ),
    );
  }
}
