import Vue from 'vue'
import VueCompositionAPI from '@vue/composition-api'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

import router from './router'
import store from './store'

import api from "./plugins/api"
import '@/plugins/dayjs'

import './scss/app.scss'

import App from './App.vue'

Vue.config.productionTip = false

Vue.use(VueCompositionAPI)
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(api);

//icon font awesome
import { faBars } from '@fortawesome/free-solid-svg-icons'
library.add(faBars)

Vue.component('font-awesome-icon', FontAwesomeIcon)

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
