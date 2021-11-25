import axios from "axios";
export default {
	install(Vue) {
		let ajax = axios.create({
			baseURL: process.env.VUE_APP_API,
		});

		Vue.prototype.$api = {
			url: process.env.VUE_APP_HOST,
			storageURL: process.env.VUE_APP_STORAGE,
			post: async function(path) {
				return await ajax.post(path);
			},
		};
		Vue.prototype.$ajax = ajax;
	},
};