import preset from '@/@core/preset/preset'
import Vue from 'vue'
import Vuetify from 'vuetify/lib/framework'

Vue.use(Vuetify)

export default new Vuetify({
  preset,  
  theme: {
    options: {
      customProperties: true,
      variations: false,
    },
  },
  icons: {
    iconfont: 'mdi'
  }
})
