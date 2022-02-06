//state
const getDefaultState = () => {
  return {
		loaded: false,
		//page
		default_dashboard: null,
		pages: [],
  }
}
const state = getDefaultState()

//mutations
const mutations = {
	setLoaded(state, loaded) {
		state.loaded = loaded;
	},
	setNewPage(state, page) {
		state.pages.push(page)
	},
	replacePage(state, page, index) {
		state.pages[index] = page
	},
	removePage(state, name) {
		var i
		for (i = 0; i < state.pages.length; i++) {
			if (state.pages[i].name == name) {
				state.pages.splice(i, 1)
				break
			}
		}
	},
	resetState(state) {
		Object.assign(state, getDefaultState());
	},
}

//getters
const getters = {
	Page: state => name => {
		let page = state.pages.find(halaman => halaman.name == name);
		return page;
	},
	AtributeValueOfPage: state => (name, key) => {
		let page = state.pages.find(halaman => halaman.name == name);
		return page[key];
	},
}

//actions
const actions = {
  async init ({ commit, state, rootGetters }, ajax) {
		commit('setLoaded', false)
		if (!state.loaded && rootGetters['auth/Authenticated']) {
			// commit(
			// 	'setSemesterPendaftaran',
			// 	rootGetters['uifront/getSemesterPendaftaran']
			// )
			let token = rootGetters['auth/Token']
			await ajax
				.get('/system/setting/uiadmin', {
					headers: {
						Authorization: token,
					},
				})
				.then(({ data }) => {
					// commit('setDaftarTA', data.daftar_ta)
					// commit('setTahunPendaftaran', data.tahun_pendaftaran)
					// commit('setTahunAkademik', data.tahun_akademik)
					// commit('setDaftarSemester', data.daftar_semester)
					// commit('setSemesterAkademik', data.semester_akademik)
					
					let daftar_prodi = data.daftar_prodi
					var prodi = []
					daftar_prodi.forEach(element => {
						prodi[element.id] = {
							id: element.id,
							text:
								element.nama_prodi_alias + ' (' + element.nama_jenjang + ')',
							nama_prodi:
								element.nama_prodi + ' (' + element.nama_jenjang + ')',
						}
					})
					// commit('setDaftarProdi', prodi)
					// commit('setProdiID', data.prodi_id)

					// commit('setDaftarKelas', data.daftar_kelas)
					// commit('setIDKelas', data.idkelas)

					// commit('setDaftarStatusMahasiswa', data.daftar_status_mhs)
					// commit('setStatusMahasiswa', data.k_status)

					// commit('setTheme', data.theme)

					// commit('setLoaded', true)
				})
		}
	},
	addToPages({ commit, state }, page) {
		let found = state.pages.find(halaman => halaman.name == page.name)
		if (!found) {
			commit('setNewPage', page)
		}
	},
	updatePage({ commit, state },page) {
		var i
		for (i = 0; i < state.pages.length; i++) {
			if (state.pages[i].name == page.name) {
				break
			}
		}
		commit('replacePage', page, i)
	},
	deletePage({ commit },name) {
		commit('removePage', name)
	},
	reinit({ commit }) {
		commit("resetState");
	},
}
export default {
	namespaced: true,
	state,
	mutations,
	getters,
	actions,
}