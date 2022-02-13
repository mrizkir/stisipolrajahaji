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
		path: "/sistem-pengguna/roles/:id/detail",
		name: "PenggunaRolesDetail",
		meta: {
			title: "PENGGUNA - ROLES",
			bodyClass: 'sidebar-mini layout-fixed',
			requiresAuth: true,
		},
		component: () => import("../views/pages/admin/pengguna/pengguna-roles/Pengguna-Roles-Show.vue"),
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
