import 'package:mobile/src/latihan/latihan_statefull.dart';
import 'package:mobile/src/features/authentication/presentation/login_screen.dart';

//latihan
import 'package:mobile/src/features/dashboard/dashboard_screen.dart';

var routesApp = {
  '/authentication/login': (context) => const LoginScreen(),
  '/admin/dashboard': (context) => const DashboardScreen(),
  //untuk latihan
  '/latihan/statefulll': (context) => const LatihanStatefull(),
};
