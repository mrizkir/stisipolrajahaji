
const getDefaultState = () => {
  return {};
};
const state = getDefaultState();
const mutations = {
  resetState(state) {
		Object.assign(state, getDefaultState());
	},
};
const getters = {
  
};
const actions = {
  async init() {
    
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