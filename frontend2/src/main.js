import Vue from 'vue'
import VueCompositionAPI from '@vue/composition-api'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

import router from './router'
import store from './store'

import api from "./plugins/api"

import './scss/app.scss'

import App from './App.vue'

Vue.config.productionTip = false

Vue.use(VueCompositionAPI)
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(api);

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
