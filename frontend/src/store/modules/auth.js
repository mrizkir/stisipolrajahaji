export default {
  namespaced: true,  
  defaultstate: {
    access_token: null,
		token_type: null,
		expires_in: null,
		user: null,
		daftar_role: [
			{
				text: "Super Admin",
				value: "sa",
			},
			{
				text: "Manajemen",
				value: "m",
			},
			{
				text: "PMB",
				value: "pmb",
			},
			{
				text: "Keuangan",
				value: "k",
			},
			{
				text: "Operator Nilai",
				value: "on",
			},
			{
				text: "Dosen",
				value: "d",
			},
			{
				text: "Dosen Wali",
				value: "dw",
			},
			{
				text: "Mahasiswa",
				value: "m",
			},
			{
				text: "Mahasiswa Baru",
				value: "mb",
			},
			{
				text: "Alumni",
				value: "al",
			},
			{
				text: "Orang Tua",
				value: "ot",
			},
		],
  },
	state: {
    access_token: null,
		token_type: null,
		expires_in: null,
		user: null,
		daftar_role: [
			{
				text: "Super Admin",
				value: "sa",
			},
			{
				text: "Manajemen",
				value: "m",
			},
			{
				text: "PMB",
				value: "pmb",
			},
			{
				text: "Keuangan",
				value: "k",
			},
			{
				text: "Operator Nilai",
				value: "on",
			},
			{
				text: "Dosen",
				value: "d",
			},
			{
				text: "Dosen Wali",
				value: "dw",
			},
			{
				text: "Mahasiswa",
				value: "m",
			},
			{
				text: "Mahasiswa Baru",
				value: "mb",
			},
			{
				text: "Alumni",
				value: "al",
			},
			{
				text: "Orang Tua",
				value: "ot",
			},
		],
  },
	getters: {
		Authenticated: state => {
			return state.access_token != null && state.user != null;
		},
		AccessToken: state => {
			return state.access_token;
		},
		Token: state => {
			return state.token_type + " " + state.access_token;
		},
		DaftarRoles: state => {
			return state.daftar_role;
		},
		Roles: state => {
			return state.user.role;
		},
		DefaultRole: state => {
			if (state.user === null || typeof state.user === "undefined") {
				return "N.A";
			} else {
				return state.user.default_role;
			}
		},
		Role: state => {
			var role = "";
			if (state.access_token != null && state.user != null) {
				let roles = state.user.role;
				for (var i = 0; i < roles.length; i++) {
					switch (roles[i]) {
						case "mb":
							role = role + "[mahasiswabaru] ";
							break;
						case "mh":
							role = role + "[mahasiswa] ";
							break;
						default:
							role = role + "[" + roles[i] + "] ";
					}
				}
			}
			return role;
		},
		User: state => {
			return state.user;
		},
		AttributeUser: state => key => {
			return state.user == null ? "" : state.user[key];
		},		
	}, 
  mutations: {
    setToken: (state, token) => {
			state.access_token = token.access_token;
			state.token_type = token.token_type;
			state.expires_in = token.expires_in;
		},
		setUser: (state, user) => {
			state.user = user;
		},
		resetState(state) {
			Object.assign(state, this.defaultstate);
		},
  },
	actions: {
		afterLoginSuccess({ commit },data) {
			commit("setToken", data.token);
			commit("setUser", data.user);
		},
		logout({ commit }) {
			commit("resetState");
		},
	},
};