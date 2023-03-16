import 'package:flutter/material.dart';

class Login extends StatelessWidget {
  const Login({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Login"),
      ),
      body: Column(
        children: [
          Container(
            width: 200,
            height: 50,
            child: Text(
              "Silahkan masukan username atau password",
              style: TextStyle(fontSize: 12),
            ),
          ),
          Container(
            width: 200,
            height: 50,
            child: TextField(
              autocorrect: false,
            ),
          ),
          Container(
            width: 200,
            height: 50,
            child: TextField(
              autocorrect: false,
            ),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {
          Navigator.of(context).pushNamed('/admin/dashboard');
        },
        child: const Icon(Icons.keyboard_arrow_right),
      ),
      floatingActionButtonLocation: FloatingActionButtonLocation.centerFloat,
    );
  }
}
