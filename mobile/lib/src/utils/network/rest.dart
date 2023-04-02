import 'dart:convert';

import 'package:http/http.dart' as http;
import 'package:mobile/src/utils/Environment.dart';
import 'package:mobile/src/features/authentication/data/user_repository.dart';

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

  /*
  * digunakna untuk melakukan request tipe post tanpa token
  */
  static Future httPostWithToken(
      String endpoint, Map<String, String> body) async {
    var token = await UserRepository.getToken();
    final config = await Environment.forEnvironment();
    final response = await http.post(
      Uri.parse(config.appApiUrl + endpoint),
      headers: <String, String>{
        'Content-Type': 'application/json; charset=UTF-8',
        'Authorization': 'Bearer $token',
      },
      body: jsonEncode(body),
    );
    return response;
  }

  /*
  * digunakna untuk melakukan request tipe get dengan token
  */
  static Future<http.Response> httGetWithToken(String endpoint) async {
    var token = await UserRepository.getToken();
    final config = await Environment.forEnvironment();

    final response = await http.get(
      Uri.parse(config.appApiUrl + endpoint),
      headers: <String, String>{
        'Content-Type': 'application/json; charset=UTF-8',
        'Authorization': 'Bearer $token',
      },
    );
    return response;
  }
}
