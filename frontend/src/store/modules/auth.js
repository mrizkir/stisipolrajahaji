//state
const getDefaultState = () => {
	return {
		access_token: null,
		token_type: null,
		expires_in: null,
		user: null,
  }
}
const state = getDefaultState()

//mutations
const mutations = {
	setToken: (state, token) => {
		state.access_token = token.access_token
		state.token_type = token.token_type
		state.expires_in = token.expires_in
	},
	setUser: (state, user) => {
		state.user = user
	},
	resetState(state) {
		Object.assign(state, getDefaultState())
	},
}

// getters
const getters = {
	Authenticated: state => {
		return state.access_token != null && state.user != null
	},
	AccessToken: state => {
		return state.access_token
	},
	Token: state => {
		return state.token_type + " " + state.access_token
	},
  Roles: state => {
		return state.user.role
	},
	DefaultRole: state => {
		if (state.user === null || typeof state.user === "undefined") {
			return "N.A"
		} else {
			return state.user.role
		}
	},
	Role: state => {
		return state.user.role
	},
	User: state => {
		return state.user
	},
	AttributeUser: state => key => {
		return state.user == null ? "" : state.user[key]
	},
	DetailUser: state => key => {
		let user = state.user
		if (user !== null) {
			let detail = state.user.detail
			return (typeof detail === 'undefined' || detail == null) ? "" : detail[key]
		} else {
			return ""
		}
	},
	can: state => name => {
		if (state.user == null) {
			return false
		} else if (state.user.issuperadmin) {
			return true
		} else {
			let permissions = state.user.permissions
			return name in permissions ? true : false
		}
	},
}

// actions
const actions = {
	afterLoginSuccess({ commit }, data) {
		commit("setToken", data.token)
		commit("setUser", data.user)		
	},
	logout({ commit }) {
		commit("resetState")
	},
}

export default {
	namespaced: true,
	state,
	mutations,
	getters,
	actions,
}
