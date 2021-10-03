import "@/plugins/vue-composition-api";
import "@/styles/styles.scss";
import Vue from "vue";
import App from "./App.vue";
import vuetify from "./plugins/vuetify";
import router from "./router";
import store from "./store";
import api from "./plugins/api"
import "@/plugins/Dayjs";
import { abilitiesPlugin } from '@casl/vue';
import { Ability } from '@casl/ability'

Vue.use(api);
Vue.use(abilitiesPlugin, new Ability());

Vue.config.productionTip = false;

new Vue({
  router,
  store,
  vuetify,
  render: h => h(App),
}).$mount("#app");
