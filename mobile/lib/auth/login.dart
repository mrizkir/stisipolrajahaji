import 'package:flutter/material.dart';

class Login extends StatelessWidget {
  const Login({super.key});
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
    return Scaffold(
      appBar: AppBar(
        title: const Text("Login"),
      ),
      body: Center(
        child: Column(
          children: [
            Container(
              width: 300,
              height: 50,
              child: const Text(
                "Silahkan masukan username atau password",
                style: TextStyle(fontSize: 12),
              ),
            ),
            Container(
              width: 300,
              height: 50,
              child: const TextField(
                autocorrect: false,
                autofocus: true,
                decoration: InputDecoration(
                    labelText: "Username",
                    icon: Icon(Icons.person, size: 35),
                    border: OutlineInputBorder()),
              ),
            ),
            Container(
              width: 300,
              height: 50,
              child: const TextField(
                autocorrect: false,
                obscureText: true,
                enableSuggestions: false,
                decoration: InputDecoration(
                    labelText: "Password",
                    icon: Icon(Icons.lock, size: 35),
                    border: OutlineInputBorder()),
              ),
            ),
            Container(
              width: 300,
              height: 200,
              child: DropdownButtonFormField(
                  decoration: const InputDecoration(
                    border: OutlineInputBorder(),
                  ),
                  onChanged: (value) {
                    print(value);
                  },
                  items: listRoles.map<DropdownMenuItem>((role) {
                    return DropdownMenuItem<String>(
                        value: role['key'],
                        child: Text(role["value"].toString()));
                  }).toList()),
            )
          ],
        ),
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
