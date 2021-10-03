import appConfigStoreModule from "@core/@app-config/appConfigStoreModule"
import Vue from "vue";
import Vuex from "vuex";
import createPersistedState from "vuex-persistedstate";
import SecureLS from "secure-ls";
import app from "./app"
import Auth from "./modules/auth";
import Uifront from "./modules/uifront";
import Uiadmin from "./modules/uiadmin";

const ls = new SecureLS({ isCompression: false });

Vue.use(Vuex)

export default new Vuex.Store({
  state: {},
  mutations: {},
  actions: {},
  modules: {
    appConfig: appConfigStoreModule,
    app,
    auth: Auth,
    uifront: Uifront,
    uiadmin: Uiadmin,
  },
  plugins: [
		createPersistedState({
			key: "stisipolrh-pe2",
			storage: {
				getItem: key => ls.get(key),
				setItem: (key, value) => ls.set(key, value),
				removeItem: key => ls.remove(key),
			},
		}),
	],
})
