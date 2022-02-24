import Vue from 'vue'
import VueRouter from 'vue-router'
import VueBodyClass from 'vue-body-class'
import store from '../store/index'

import NotFoundComponent from '../components/NotFoundComponent';

Vue.use(VueRouter)

const routes = [
	//front
  {
		path: '/',
		name: 'FrontDashboard',
		meta: {
			title: 'DASHBOARD',
		},
		component: () => import('../views/pages/front/Home.vue'),
	},
	{
		path: '/login',
		name: 'FrontLogin',
		meta: {
			title: 'LOGIN',
			bodyClass: 'login-page',
		},
		component: () => import('../views/pages/front/Login.vue'),
	},
	//admin
	{
		path: '/dashboard/:token',
		name: 'AdminDashboard',
		meta: {
			title: 'DASHBOARD',			
		},
		component: () => import('../views/pages/admin/Dashboard.vue'),
	},
	//akademik
	{
		path: '/akademik',
		name: 'Akademik',
		meta: {
			title: 'AKADEMIK',
			bodyClass: 'sidebar-mini layout-fixed',
			requiresAuth: true,
		},
		component: () => import('../views/pages/admin/akademik/Akademik.vue'),
	},
	//system - users
	{
		path: '/sistem-pengguna',
		name: 'PenggunaSistem',
		meta: {
			title: 'SISTEM - PENGGUNA',
			bodyClass: 'sidebar-mini layout-fixed',
			requiresAuth: true,
		},
		component: () => import('../views/pages/admin/pengguna/PenggunaSistem.vue'),
	},
	{
		path: "/sistem-pengguna/permission",
		name: "PenggunaPermission",
		meta: {
			title: "PENGGUNA - PERMISSION",
			bodyClass: 'sidebar-mini layout-fixed',
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/pengguna/PenggunaPermission.vue"),
	},
	{
		path: "/sistem-pengguna/roles",
		name: "PenggunaRoles",
		meta: {
			title: "PENGGUNA - ROLES",
			bodyClass: 'sidebar-mini layout-fixed',
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/pengguna/pengguna-roles/Pengguna-Roles-Index.vue"),
	},
	{
		path: "/sistem-pengguna/roles/:role_id/detail",
		name: "PenggunaRolesDetail",
		meta: {
			title: "PENGGUNA - ROLES",
			bodyClass: 'sidebar-mini layout-fixed',
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/pengguna/pengguna-roles/Pengguna-Roles-Show.vue"),
	},
	//pengguna superadmin
	{
		path: "/sistem-pengguna/superadmin",
		name: "PenggunaSuperadmin",
		meta: {
			title: "PENGGUNA - SUPERADMIN",
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/pengguna/pengguna-sa/Pengguna-Superadmin-Index.vue"),
	},
	{
		path: "/sistem-pengguna/superadmin/create",
		name: "PenggunaSuperadminCreate",
		meta: {
			title: "PENGGUNA - SUPERADMIN",
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/pengguna/pengguna-sa/Pengguna-Superadmin-Create.vue"),
	},
	{
		path: "/sistem-pengguna/superadmin/:user_id/edit",
		name: "PenggunaSuperadminEdit",
		meta: {
			title: "PENGGUNA - SUPERADMIN",
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/pengguna/pengguna-sa/Pengguna-Superadmin-Edit.vue"),
	},
	{
		path: "/sistem-pengguna/superadmin/:user_id/detail",
		name: "PenggunaSuperadminDetail",
		meta: {
			title: "PENGGUNA - SUPERADMIN",
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/pengguna/pengguna-sa/Pengguna-Superadmin-Detail.vue"),
	},
	//pengguna manajemen
	{
		path: "/sistem-pengguna/manajemen",
		name: "PenggunaManajemen",
		meta: {
			title: "PENGGUNA - MANAJEMEN",
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/pengguna/pengguna-manajemen/Pengguna-Manajemen-Index.vue"),
	},
	{
		path: "/sistem-pengguna/manajemen/create",
		name: "PenggunaManajemenCreate",
		meta: {
			title: "PENGGUNA - MANAJEMEN",
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/pengguna/pengguna-manajemen/Pengguna-Manajemen-Create.vue"),
	},
	{
		path: "/sistem-pengguna/manajemen/:user_id/edit",
		name: "PenggunaManajemenEdit",
		meta: {
			title: "PENGGUNA - MANAJEMEN",
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/pengguna/pengguna-manajemen/Pengguna-Manajemen-Edit.vue"),
	},
	{
		path: "/sistem-pengguna/manajemen/:user_id/detail",
		name: "PenggunaManajemenDetail",
		meta: {
			title: "PENGGUNA - MANAJEMEN",
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/pengguna/pengguna-manajemen/Pengguna-Manajemen-Detail.vue"),
	},
	
	{
		path: '/404',
		name: 'NotFoundComponent',
		meta: {
			title: 'PAGE NOT FOUND',
		},
		component: NotFoundComponent,
	},
	{
		path: '*',
		redirect: '/404',
	},
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes,
})
const vueBodyClass = new VueBodyClass(routes);
router.beforeEach((to, from, next) => {	
	vueBodyClass.guard(to, next)
	document.title = to.meta.title
	if (to.matched.some(record => record.meta.requiresAuth)) {
		if (store.getters['auth/Authenticated']) {
			next()
			return
		}
		next('/login')
	} else {
		next()
	}
})

export default router
