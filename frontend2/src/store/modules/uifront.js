//state
const getDefaultState = () => {
	return {
		loaded: false,
		daftar_page: [],
	}
}
const state = getDefaultState()
const mutations = {
  setLoaded(state, loaded) {
		state.loaded = loaded
	},
  setDaftarPage(state, pages) {
		state.daftar_page = pages
	},
  resetState(state) {
		Object.assign(state, getDefaultState())
	},
}
const getters = {
  isLoaded: state => {
		return state.loaded
	},
  getDaftarPage: state => {

		return state.daftar_page
	},
}
const actions = {
  init({ commit, state }, ajax) {
		//dipindahkan kesini karena ada beberapa kasus yang melaporkan ini membuat bermasalah.
		// commit("setLoaded", false)
    if (!state.loaded) {
			ajax.get('/system/setting/uifront').then(({ data }) => {				
				var daftar_page = [{
					value: '',
					text: 'Pilih Halaman'
				}]
				var daftar_role = data.daftar_role;
				for (var role_id in daftar_role) {
					daftar_page.push({
						value: role_id,
						text: daftar_role[role_id],
					})					
				}				
				commit('setDaftarPage', daftar_page)			
				commit('setLoaded', true)
			})
		}
  }
}
export default {
	namespaced: true,
	state,
	mutations,
	getters,
	actions,
}