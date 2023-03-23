import 'package:flutter/material.dart';

import 'login_state.dart';

class LoginScreen extends StatelessWidget {
  const LoginScreen({super.key});
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      resizeToAvoidBottomInset: false,
      appBar: AppBar(
        title: const Text("Login"),
      ),
      body: const LoginState(),
    );
  }
}
