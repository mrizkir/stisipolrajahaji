class User {
  final int userid;
  final int idbank;
  final String username;
  final String page;
  final int groupId;
  final int kjur;
  final String nama;
  final String email;
  final int active;
  final int isdeleted;
  final String theme;
  final String foto;
  final String ipaddress;
  final String logintime;
  final String dateAdded;
  final String roleName;
  final String detail;
  final String role;
  final bool issuperadmin;
  final Map permission;

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
        ipaddress: json['ipaddress'] ?? '',
        logintime: json['logintime'],
        dateAdded: json['date_added'],
        roleName: json['role_name'],
        detail: json['detail'] ?? '',
        role: json['role'],
        issuperadmin: json['issuperadmin'],
        permission: json['permission'] ?? {});
  }
}
