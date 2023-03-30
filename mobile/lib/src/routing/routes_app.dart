import 'package:mobile/src/features/authentication/presentation/login_screen.dart';
import 'package:mobile/src/features/dashboard/dashboard_screen.dart';
//perkuliahan
import 'package:mobile/src/features/perkuliahan_dashboard/perkuliahan_dashboard_screen.dart';
import 'package:mobile/src/features/perkuliahan_khs/perkuliahan_khs_screen.dart';
//latihan
import 'package:mobile/src/latihan/latihan_statefull.dart';

var routesApp = {
  '/authentication/login': (context) => const LoginScreen(),
  '/dashboard': (context) => const DashboardScreen(),
  //perkuliahan
  '/perkuliahan': (context) => const PerkuliahanDashboardScreen(),
  '/perkuliahan/khs': (context) => const PerkuliahanKhsScreen(),
  //untuk latihan
  '/latihan/statefulll': (context) => const LatihanStatefull(),
};
