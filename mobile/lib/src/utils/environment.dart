import 'dart:convert';

import 'package:flutter/services.dart';

class Environment {
  final String appName;
  final String appEnv;
  final String appDebug;
  final String appUrl;
  final String appApiUrl;
  final String appTimeZone;

  Environment(
      {required this.appName,
      required this.appEnv,
      required this.appDebug,
      required this.appUrl,
      required this.appApiUrl,
      required this.appTimeZone});

  static Future<Environment> forEnvironment() async {
    // load the json file
    final contents = await rootBundle.loadString(
      'assets/config/env.json',
    );

    // decode our json
    final json = jsonDecode(contents);

    // convert our JSON into an instance of our AppConfig class
    return Environment(
        appName: json['APP_NAME'],
        appEnv: json['APP_ENV'],
        appDebug: json['APP_DEBUG'],
        appUrl: json['APP_URL'],
        appApiUrl: json['APP_API_URL'],
        appTimeZone: json['APP_TIMEZONE']);
  }
}
