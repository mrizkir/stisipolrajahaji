import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import SecureLS from 'secure-ls'
import Auth from './modules/auth'
import Uifront from './modules/uifront'
import Uiadmin from './modules/uiadmin'

Vue.use(Vuex)

const ls = new SecureLS({ isCompression: false })

export default new Vuex.Store({  
  modules: {
		uifront: Uifront,
		auth: Auth,
		uiadmin: Uiadmin,
	},
  plugins: [
		createPersistedState({
			key: 'stisipolrh-pe2',
			storage: {
				getItem: key => ls.get(key),
				setItem: (key, value) => ls.set(key, value),
				removeItem: key => ls.remove(key),
			},
		}),
	],
})
