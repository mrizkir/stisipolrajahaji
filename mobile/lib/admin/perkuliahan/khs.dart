import 'package:flutter/material.dart';

class KHS extends StatelessWidget {
  const KHS({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Kartu Hasil Studi"),
      ),
      body: Center(
          child: Column(
        children: [
          Row(
            children: [
              TextButton(onPressed: () {}, child: Text("Back")),
              TextButton(onPressed: () {}, child: Text("Next"))
            ],
          )
        ],
      )),
    );
  }
}
