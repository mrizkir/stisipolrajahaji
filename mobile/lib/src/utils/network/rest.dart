import 'package:global_configuration/global_configuration.dart';

class Rest {
  Future<Uri> getUri(String endpoint) async {
    return Uri.parse(GlobalConfiguration().getString("BASE_URL_API"));
  }
}
