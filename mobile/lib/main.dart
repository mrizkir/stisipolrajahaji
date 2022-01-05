import 'package:flutter/material.dart';

void main() {
  runApp(MainWidget());
}

class MainWidget extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      home: Scaffold(
        appBar: AppBar(
          title: Text('PortalEkampus'),
        ),
        body: Card(
            child: Column(
          children: <Widget>[Image(), Text("Test 123")],
        )),
      ),
    );
  }
}
