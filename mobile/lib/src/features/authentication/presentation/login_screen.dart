import 'package:flutter/material.dart';
import 'package:mobile/src/common_widgets/aplikasi_bar.dart';

import 'login_state.dart';

class LoginScreen extends StatelessWidget {
  const LoginScreen({super.key});
  @override
  Widget build(BuildContext context) {
    return const Scaffold(
      resizeToAvoidBottomInset: false,
      appBar: AplikasiBar(
        title: "LOGIN",
      ),
      body: LoginState(),
    );
  }
}
