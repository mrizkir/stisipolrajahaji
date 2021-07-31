
import axios from "axios";

const getDefaultState = () => {
  return {
    //page
		default_dashboard: null,
  };
};
const state = getDefaultState();
const mutations = {
  setDashboard(state, name) {
		state.default_dashboard = name;
	},
  resetState(state) {
		Object.assign(state, getDefaultState());
	},
};
const getters = {
  
};
const actions = {
  async init({ rootGetters }) {    
    let token = rootGetters["auth/Token"];    
    await axios
      .get(process.env.VUE_APP_APIUrlV2 + "/system/setting/uiadmin", {
        headers: {
          Authorization: token,
        },
      })
      .then(({ data }) => {
        console.log(data);
      });
  },
  changeDashboard({ commit }, name) {
		commit("setDashboard", name);
	},
  reinit({ commit }) {
		commit("resetState");
	},
};
export default {
	namespaced: true,
	state,
	mutations,
	getters,
	actions,
};