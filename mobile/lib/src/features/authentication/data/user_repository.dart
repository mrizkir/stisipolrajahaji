import 'dart:convert';

import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:mobile/src/features/authentication/model/user.dart';

class UserRepository {
  static const _storage = FlutterSecureStorage();

  static Future setToken(String token) async =>
      await _storage.write(key: 'token', value: token);

  static Future<String?> getToken() async => await _storage.read(key: 'token');

  static Future setUser(String user) async =>
      await _storage.write(key: 'user', value: user);

  static Future<User> getUser() async {
    var dataUser = await _storage.read(key: 'user');
    User user = User.fromJson(jsonDecode(dataUser.toString()));
    return user;
  }
}
