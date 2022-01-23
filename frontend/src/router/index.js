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
	//akademik
	{
		path: "/akademik",
		name: "Akademik",
		meta: {
			title: "AKADEMIK",
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/akademik/Akademik.vue"),
	},
	{
		path: "/akademik/perkuliahan/aktivitasmahasiswa/jenisaktivitas",
		name: "PerkuliahanJenisAktivitas",
		meta: {
			title: "PERKULIAHAN - JENIS AKTIVITAS",
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/akademik/perkuliahan/aktivitasmahasiswa/JenisAktivitas.vue"),
	},
	//kemahasiswaaan
	{
		path: "/kemahasiswaan",
		name: "Kemahasiswaan",
		meta: {
			title: "KEMAHASISWAAN",
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/kemahasiswaan/Kemahasiswaan.vue"),
	},
	//kepegawaian
	{
		path: "/kepegawaian",
		name: "Kepegawaian",
		meta: {
			title: "KEPEGAWAIAN",
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/kepegawaian/Kepegawaian.vue"),
	},
	//system - users
	{
		path: "/system-users",
		name: "SystemUsers",
		meta: {
			title: "SYSTEM - USERS",
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/system/SystemUsers.vue"),
	},
	{
		path: "/system-users/permissions",
		name: "UsersPermissions",
		meta: {
			title: "USERS - PERMISSIONS",
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/system/Permissions.vue"),
	},
	{
		path: "/system-users/mypermission",
		name: "MyPermissions",
		meta: {
			title: "USERS - MY PERMISSION",
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/system/MyPermission.vue"),
	},
	{
		path: "/system-users/roles",
		name: "UsersRoles",
		meta: {
			title: "USERS - ROLES",
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/system/Roles.vue"),
	},
	{
		path: "/system-users/superadmin",
		name: "UsersSuperadmin",
		meta: {
			title: "USERS - SUPER ADMIN",
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/system/UsersSuperadmin.vue"),
	},
	{
		path: "/system-users/pmb",
		name: "UsersPMB",
		meta: {
			title: "USERS - PMB",
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/system/UsersPMB.vue"),
	},
	{
		path: "/system-users/akademik",
		name: "UsersAkademik",
		meta: {
			title: "USERS - AKADEMIK",
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/system/UsersAkademik.vue"),
	},
	{
		path: "/system-users/prodi",
		name: "UsersProdi",
		meta: {
			title: "USERS - PROGRAM STUDI",
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/system/UsersProdi.vue"),
	},
	{
		path: "/system-users/puslahta",
		name: "UsersPuslahta",
		meta: {
			title: "USERS - PUSLAHTA",
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/system/UsersPuslahta.vue"),
	},
	{
		path: "/system-users/dosen",
		name: "UsersDosen",
		meta: {
			title: "USERS - DOSEN",
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/system/UsersDosen.vue"),
	},
	{
		path: "/system-users/keuangan",
		name: "UsersKeuangan",
		meta: {
			title: "USERS - KEUANGAN",
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/system/UsersKeuangan.vue"),
	},
	{
		path: "/system-users/profil",
		name: "UsersProfil",
		meta: {
			title: "USERS - PROFILE",
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/system/UsersProfile.vue"),
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
