import '../../admin/dashboard.dart';
import '../../admin/perkuliahan/khs.dart';
import '../features/authentication/presentation/login_screen.dart';

//latihan
import '../../latihan/latihan_statefull.dart';

var routesApp = {
  '/authentication/login': (context) => const LoginScreen(),
  '/admin/dashboard': (context) => const Dashboard(),
  '/admin/perkuliahan/khs': (context) => const KHS(),

  //untuk latihan
  '/latihan/statefulll': (context) => const LatihanStatefull(),
};
