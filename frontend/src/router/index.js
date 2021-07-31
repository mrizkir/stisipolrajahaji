import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

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
		path: "/dashboard/:token",
		name: "AdminDashboard",
		meta: {
			title: "DASHBOARD",
		},
		component: () => import("../views/pages/admin/Dashboard.vue"),
	},
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes,
});

router.beforeEach((to, from, next) => {
  document.title = to.meta.title;
  next();
});
export default router;
