import 'package:flutter/material.dart';

class LatihanStatefull extends StatefulWidget {
  const LatihanStatefull({super.key});

  @override
  State<LatihanStatefull> createState() => _LatihanStatefullState();
}

class _LatihanStatefullState extends State<LatihanStatefull> {
  int counter = 1;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        appBar: AppBar(
          title: const Text("Belajar Statefull"),
        ),
        body: Column(
          mainAxisAlignment: MainAxisAlignment.spaceEvenly,
          children: [
            Text(counter.toString()),
            Row(mainAxisAlignment: MainAxisAlignment.spaceEvenly, children: [
              ElevatedButton(
                  onPressed: () {
                    setState(() {
                      counter++;
                    });
                  },
                  child: const Icon(Icons.add)),
              ElevatedButton(
                  onPressed: () {
                    setState(() {
                      counter--;
                    });
                  },
                  child: const Icon(Icons.remove)),
            ])
          ],
        ));
  }
}
