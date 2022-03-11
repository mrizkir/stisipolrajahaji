//state
const getDefaultState = () => {
  return {
		loaded: false,
		//page
		default_dashboard: null,
		pages: [],
		//data master
		daftar_ta: [],
		tahun_pendaftaran: null,
		tahun_akademik: null,

		daftar_semester: [],
		semester_pendaftaran: null,
		semester_akademik: null,

		daftar_prodi: [],
		prodi_id: null,
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
	//data master
	setDaftarTA(state, daftar) {
		state.daftar_ta = daftar;
	},
	setTahunPendaftaran(state, tahun) {
		state.tahun_pendaftaran = tahun;
	},
	setTahunAkademik(state, tahun) {
		state.tahun_akademik = tahun;
	},

	setDaftarSemester(state, daftar) {
		state.daftar_semester = daftar;
	},
	setSemesterPendaftaran(state, semester) {
		state.semester_pendaftaran = semester;
	},
	setSemesterAkademik(state, semester) {
		state.semester_akademik = semester;
	},

	setDaftarProdi(state, daftar) {
		state.daftar_prodi = daftar;
	},
	setProdiID(state, id) {
		state.prodi_id = id;
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
	//data master
	getDaftarProdi: state => (prepend) => {
		var daftar_prodi = state.daftar_prodi		
		if(prepend) {
			daftar_prodi[0] = {
				value: 0,
				text: 'KESELURUHAN PRODI',
			}
		}
		return daftar_prodi.filter(el => el != null);
	},
	getProdiID: state => {
		return parseInt(state.prodi_id);
	},
	getProdiName: state => key => {
		if(state.daftar_prodi == null || state.daftar_prodi[key] == null) {
			return 'N.A'
		} else if(key == 0) {
			return 'KESELURUHAN'
		} else {
			return state.daftar_prodi[key].nama_prodi
		}
	},
	getDaftarTA: state => {
		return state.daftar_ta;
	},
	getDaftarTABefore: state => ta => {
		let daftar_ta = state.daftar_ta;
		var daftar = [];
		daftar_ta.forEach(element => {
			if (element.value <= ta) {
				daftar.push(element);
			}
		});
		return daftar;
	},
	getTahunPendaftaran: state => {
		return parseInt(state.tahun_pendaftaran);
	},
	getTahunAkademik: state => {
		return parseInt(state.tahun_akademik);
	},
	getDaftarSemester: state => {
		return state.daftar_semester;
	},
	getNamaSemester: state => key => {
		var nama_semester = "";
		let found = state.daftar_semester.find(semester => semester.value == key);
		if (typeof found !== "undefined") {
			nama_semester = found.text;
		}
		return nama_semester;
	},
	getSemesterPendaftaran: state => {
		return parseInt(state.semester_pendaftaran);
	},
	getSemesterAkademik: state => {
		return parseInt(state.semester_akademik);
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
					commit('setDaftarTA', data.daftar_ta)
					commit('setTahunPendaftaran', data.tahun_pendaftaran)
					commit('setTahunAkademik', data.tahun_akademik)
					commit('setDaftarSemester', data.daftar_semester)
					commit('setSemesterAkademik', data.semester_akademik)
					
					let daftar_prodi = data.daftar_prodi
					var prodi = []
					daftar_prodi.forEach(element => {
						prodi[element.kjur] = {
							value: element.kjur,
							text:
								element.nama_ps_alias + ' (' + element.nama_jenjang + ')',
							nama_prodi:
								element.nama_ps + ' (' + element.nama_jenjang + ')',
						}
					})					
					commit('setDaftarProdi', prodi)
					commit('setProdiID', data.prodi_id)

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
	//data master
	updateProdi({ commit },id) {
		commit('setProdiID', id);
	},
	updateTahunAkademik({ commit },tahun) {
		commit('setTahunAkademik', tahun);
	},
	updateSemesterAkademik({ commit },semester) {
		commit('setSemesterAkademik', semester);
	},
	reinit({ commit }) {
		commit('resetState');
	},
}
export default {
	namespaced: true,
	state,
	mutations,
	getters,
	actions,
}