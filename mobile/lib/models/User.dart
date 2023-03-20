import 'dart:convert';

class User {
  late final int userid;
  late final int idbank;
  late final String username;
  late final String page;
  late final int groupId;
  late final int kjur;
  late final String nama;
  late final String email;
  late final int active;
  late final int isdeleted;
  late final String theme;
  late final String foto;
  late final String token;
  late final String ipaddress;
  late final String logintime;
  late final String dateAdded;
  late final String roleName;
  late final String detail;
  late final String role;
  late final String issuperadmin;
  late final String permission;

  User(
      {required this.userid,
      required this.idbank,
      required this.username,
      required this.page,
      required this.groupId,
      required this.kjur,
      required this.nama,
      required this.email,
      required this.active,
      required this.isdeleted,
      required this.theme,
      required this.foto,
      required this.token,
      required this.ipaddress,
      required this.logintime,
      required this.dateAdded,
      required this.roleName,
      required this.detail,
      required this.role,
      required this.issuperadmin,
      required this.permission});

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
        userid: json['userid'],
        idbank: json['idbank'],
        username: json['username'],
        page: json['page'],
        groupId: json['group_id'],
        kjur: json['kjur'],
        nama: json['nama'],
        email: json['email'],
        active: json['active'],
        isdeleted: json['isdeleted'],
        theme: json['theme'],
        foto: json['foto'],
        token: json['token'],
        ipaddress: json['ipaddress'],
        logintime: json['logintime'],
        dateAdded: json['date_added'],
        roleName: json['role_name'],
        detail: json['detail'],
        role: json['role'],
        issuperadmin: json['issuperadmin'],
        permission: json['permission']);
  }
}
