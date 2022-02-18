import "@/plugins/vue-composition-api";
import "@/styles/styles.scss";
import Vue from "vue";
import App from "./App.vue";
import vuetify from "./plugins/vuetify";
import router from "./router";
import store from "./store";
import api from "./plugins/api"
import ability from "./plugins/ability";
import "@/plugins/Dayjs";
import { abilitiesPlugin } from "@casl/vue";

Vue.use(api);
Vue.use(abilitiesPlugin, ability);

Vue.config.productionTip = false;

new Vue({
  router,
  store,
  vuetify,
  render: h => h(App),
}).$mount("#app");
