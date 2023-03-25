import 'dart:convert';

import 'package:http/http.dart' as http;
import 'package:mobile/src/utils/Environment.dart';

class Rest {
  /*
  * digunakna untuk melakukan request tipe post tanpa token
  */
  static Future httPostWithoutToken(
      String endpoint, Map<String, String> body) async {
    final config = await Environment.forEnvironment();
    final response = await http.post(
      Uri.parse(config.appApiUrl + endpoint),
      headers: <String, String>{
        'Content-Type': 'application/json; charset=UTF-8',
      },
      body: jsonEncode(body),
    );
    return response;
  }
}
