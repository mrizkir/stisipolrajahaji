import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

import 'package:mobile/helpers/helperstorage.dart';

class Login extends StatelessWidget {
  const Login({super.key});
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      resizeToAvoidBottomInset: false,
      appBar: AppBar(
        title: const Text("Login"),
      ),
      body: const WrapperLoginForm(),
    );
  }
}

class WrapperLoginForm extends StatefulWidget {
  const WrapperLoginForm({super.key});

  @override
  State<StatefulWidget> createState() {
    return LoginFormState();
  }
}

class LoginFormState extends State<WrapperLoginForm> {
  final _formKey = GlobalKey<FormState>();

  final TextEditingController txtUsername = TextEditingController();
  final TextEditingController txtUserpassword = TextEditingController();
  String cmbRole = '';

  @override
  Widget build(BuildContext context) {
    // final List<String> listRoles = ['manajemen'];
    final listRoles = [
      {"key": "sa", "value": "Super Admin"},
      {"key": "m", "value": "Manajemen"},
      {"key": "pmb", "value": "PMB"},
      {"key": "k", "value": "Keuangan"},
      {"key": "on", "value": "Operator Nilai"},
      {"key": "d", "value": "Dosen"},
      {"key": "mh", "value": "Mahasiswa"},
      {"key": "mb", "value": "Mahasiswa Baru"},
      {"key": "al", "value": "Alumni"},
      {"key": "ot", "value": "Orang Tua Wali"},
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
            Uri url = Uri.parse(
                "https://backend.stisipolrajahaji.ac.id/v2/auth/login");
            final response = await http.post(
              url,
              headers: <String, String>{
                'Content-Type': 'application/json; charset=UTF-8',
              },
              body: jsonEncode({
                'username': txtUsername.text,
                'password': txtUserpassword.text,
                'page': cmbRole,
              }),
            );

            String pesan = '';
            if (response.statusCode == 200) {
              var jsonResponse = jsonDecode(response.body);
              pesan = 'Berhasil login';
              await HelperStorage.setToken("test");
              print(jsonResponse.toString());
              // Navigator.of(context).pushNamed('/admin/dashboard');
            } else {
              pesan = 'Gagal Login';
            }
            ScaffoldMessenger.of(context).showSnackBar(
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
