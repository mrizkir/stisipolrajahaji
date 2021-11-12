import Vue from "vue"
import store from "../store/index";
import VueRouter from "vue-router"

Vue.use(VueRouter)

const routes = [
  {
		path: "/",
		name: "FrontDashboard",
		meta: {
			title: "DASHBOARD",
		},
		component: () => import("../views/pages/front/Home.vue"),
	},
  {
		path: "/login",
		name: "FrontLogin",
		meta: {
			title: "LOGIN",
		},
		component: () => import("../views/pages/front/Login.vue"),
	},
  {
		path: "/dashboard/:token",
		name: "AdminDashboard",
		meta: {
			title: "DASHBOARD",
		},
		component: () => import("../views/pages/admin/Dashboard.vue"),
	},
	//dmaster	
	{
		path: "/dmaster",
		name: "DMaster",
		meta: {
			title: "DATA MASTER",
			requiresAuth: true,
				},
		component: () => import("../views/pages/admin/dmaster/DMaster.vue"),
	},
	//keuangan
	{
		path: "/keuangan",
		name: "Keuangan",
		meta: {
			title: "KEUANGAN",
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/keuangan/Keuangan.vue"),
	},
  {
    path: "*",
    redirect: "error-404",
  },
];
const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes,
  scrollBehavior() {
    return { x: 0, y: 0 }
  },
});

router.beforeEach((to, from, next) => {
	document.title = to.meta.title;
	if (to.matched.some(record => record.meta.requiresAuth)) {
		if (store.getters["auth/Authenticated"]) {
			next();
			return;
		}
		next("/login");
	} else {
		next();
	}
});

export default router
