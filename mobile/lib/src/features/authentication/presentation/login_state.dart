import 'package:flutter/material.dart';
import 'dart:convert';

import 'package:mobile/src/features/authentication/data/user_repository.dart';
import 'package:mobile/src/utils/network/rest.dart';

class LoginState extends StatefulWidget {
  const LoginState({super.key});
  @override
  State<StatefulWidget> createState() {
    return _LoginState();
  }
}

class _LoginState extends State<LoginState> {
  final _formKey = GlobalKey<FormState>();

  final TextEditingController txtUsername = TextEditingController();
  final TextEditingController txtUserpassword = TextEditingController();
  String cmbRole = '';

  @override
  Widget build(BuildContext context) {
    final listRoles = [
      // {"key": "sa", "value": "Super Admin"},
      // {"key": "m", "value": "Manajemen"},
      // {"key": "pmb", "value": "PMB"},
      // {"key": "k", "value": "Keuangan"},
      // {"key": "on", "value": "Operator Nilai"},
      // {"key": "d", "value": "Dosen"},
      {"key": "mh", "value": "Mahasiswa"},
      // {"key": "mb", "value": "Mahasiswa Baru"},
      // {"key": "al", "value": "Alumni"},
      // {"key": "ot", "value": "Orang Tua Wali"},
    ];
    return Form(
      key: _formKey,
      child: ListView(children: [
        Container(
          margin: const EdgeInsets.all(7),
          width: 300,
          height: 50,
          child: const Text(
            "Silahkan masukan username atau password",
            style: TextStyle(fontSize: 12),
          ),
        ),
        Container(
          margin: const EdgeInsets.all(7),
          width: 300,
          height: 50,
          child: TextFormField(
            controller: txtUsername,
            autocorrect: false,
            autofocus: true,
            decoration: const InputDecoration(
                labelText: "Username",
                icon: Icon(Icons.person, size: 35),
                border: OutlineInputBorder()),
          ),
        ),
        Container(
          margin: const EdgeInsets.all(7),
          width: 300,
          height: 50,
          child: TextFormField(
            controller: txtUserpassword,
            autocorrect: false,
            obscureText: true,
            enableSuggestions: false,
            decoration: const InputDecoration(
                labelText: "Password",
                icon: Icon(Icons.lock, size: 35),
                border: OutlineInputBorder()),
          ),
        ),
        Container(
          margin: const EdgeInsets.all(7),
          width: 300,
          height: 200,
          child: DropdownButtonFormField(
              decoration: const InputDecoration(
                border: OutlineInputBorder(),
                icon: Icon(Icons.account_box, size: 35),
              ),
              onChanged: (value) {
                cmbRole = value;
              },
              items: listRoles.map<DropdownMenuItem>((role) {
                return DropdownMenuItem<String>(
                    value: role['key'], child: Text(role["value"].toString()));
              }).toList()),
        ),
        ElevatedButton(
          onPressed: () async {
            final navigator = Navigator.of(context);
            final snackBar = ScaffoldMessenger.of(context);

            final response = await Rest.httPostWithoutToken('/auth/login', {
              'username': txtUsername.text,
              'password': txtUserpassword.text,
              'page': cmbRole,
            });
            String pesan = '';
            if (response.statusCode == 200) {
              var jsonResponse = jsonDecode(response.body);
              pesan = 'Berhasil login';
              await UserRepository.setToken(jsonResponse['access_token']);
              var me = Rest.httGetWithToken('/auth/me');
              me.then((value) async {
                await UserRepository.setUser(value.body);
              });
              navigator.pushReplacementNamed('/dashboard');
            } else {
              pesan = 'Gagal Login';
            }
            snackBar.showSnackBar(
              SnackBar(
                  content: Text('Kode : ${response.statusCode} Pesan: $pesan')),
            );
          },
          child: const Text("Login"),
        ),
      ]),
    );
  }

  @override
  void dispose() {
    txtUsername.dispose();
    txtUserpassword.dispose();
    cmbRole = '';
    super.dispose();
  }
}
