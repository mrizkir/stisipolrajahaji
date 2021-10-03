import Vue from "vue"
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
})

export default router
