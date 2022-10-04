import Vue from 'vue'
import VueRouter from 'vue-router'
import VueBodyClass from 'vue-body-class'
import store from '../store/index'

import NotFoundComponent from '../components/NotFoundComponent'

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
  //dmaster
  {
    path: '/dmaster',
    name: 'DMaster',
    meta: {
      title: 'DATA MASTER',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () => import('../views/pages/admin/dmaster/DMaster.vue'),
  },
  {
    path: '/dmaster/dosen/kategorikegiatan',
    name: 'DMasterKategoriKegiatanDosen',
    meta: {
      title: 'DOSEN - KATEGORI KEGIATAN',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () => import('../views/pages/admin/dmaster/kategorikegiatandosen/KategoriKegiatanDosen-Index.vue'),
  },
  {
    path: '/dmaster/dosen/kategorikegiatan/create',
    name: 'DMasterKategoriKegiatanDosenCreate',
    meta: {
      title: 'DOSEN - KATEGORI KEGIATAN',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () =>
      import(
        '../views/pages/admin/dmaster/kategorikegiatandosen/KategoriKegiatanDosen-Create.vue'
      ),
  },
  {
    path: '/dmaster/dosen/kategorikegiatan/:idkategori/edit',
    name: 'DMasterKategoriKegiatanDosenEdit',
    meta: {
      title: 'DOSEN - KATEGORI KEGIATAN',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () =>
      import(
        '../views/pages/admin/dmaster/kategorikegiatandosen/KategoriKegiatanDosen-Edit.vue'
      ),
  },
  //feeder
  {
    path: '/feeder',
    name: 'Feeder',
    meta: {
      title: 'Feeder',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () => import('../views/pages/admin/feeder/Feeder.vue'),
  },
  //feeder - perkuliahan - kelas (trakd)
  {
    path: '/feeder/perkuliahan/kelas',
    name: 'FeederPerkuliahanKelas',
    meta: {
      title: 'Feeder - Perkuliahan - Kelas',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () => import('../views/pages/admin/feeder/perkuliahan/Feeder-Kelas-Perkuliahan-Index.vue'),
  },

  //feeder - perkuliahan - aktivitas kuliah mahasiswa
  {
    path: '/feeder/perkuliahan/trakm',
    name: 'FeederPerkuliahanTRAKM',
    meta: {
      title: 'Feeder - Perkuliahan - Aktivitas Kuliah',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () => import('../views/pages/admin/feeder/perkuliahan/Feeder-TRAKM-Index.vue'),
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
  //akademik - perkuliahan - laporan status mahasiswa
  {
    path: '/akademik/laporan/statusmahasiswa',
    name: 'AkademikLaporanStatusMahasiswa',
    meta: {
      title: 'AKADEMIK - LAPORAN - STATUS MAHASISWA',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () => import('../views/pages/admin/akademik/perkuliahan/ReportStatusMahasiswa.vue'),
  },
  //kemahasiswaan
  {
    path: '/kemahasiswaan',
    name: 'Kemahasiswaan',
    meta: {
      title: 'KEMAHASISWAAN',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () =>
      import('../views/pages/admin/kemahasiswaan/Kemahasiswaan.vue'),
  },
  //kemahasiswaan - jenis aktivitas
  {
    path: '/kemahasiswaan/jenisaktivitas',
    name: 'AkademikJenisAktivitas',
    meta: {
      title: 'AKTIVITAS MAHASISWA - JENIS AKTIVITAS',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () =>
      import(
        '../views/pages/admin/kemahasiswaan/jenisaktivitas/JenisAktivitas-Index.vue'
      ),
  },
  {
    path: '/kemahasiswaan/jenisaktivitas/create',
    name: 'AkademikJenisAktivitasCreate',
    meta: {
      title: 'AKTIVITAS MAHASISWA - JENIS AKTIVITAS',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () =>
      import(
        '../views/pages/admin/kemahasiswaan/jenisaktivitas/JenisAktivitas-Create.vue'
      ),
  },
  {
    path: '/kemahasiswaan/jenisaktivitas/:idjenis/edit',
    name: 'AkademikJenisAktivitasEdit',
    meta: {
      title: 'AKTIVITAS MAHASISWA - JENIS AKTIVITAS',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () =>
      import(
        '../views/pages/admin/kemahasiswaan/jenisaktivitas/JenisAktivitas-Edit.vue'
      ),
  },
  //kemahasiswaan - data aktivitas
  {
    path: '/kemahasiswaan/dataaktivitas',
    name: 'AkademikDataAktivitas',
    meta: {
      title: 'AKTIVITAS MAHASISWA - DATA AKTIVITAS',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () =>
      import(
        '../views/pages/admin/kemahasiswaan/dataaktivitas/DataAktivitas-Index.vue'
      ),
  },
  {
    path: '/kemahasiswaan/dataaktivitas/create',
    name: 'AkademikDataAktivitasCreate',
    meta: {
      title: 'AKTIVITAS MAHASISWA - DATA AKTIVITAS',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () =>
      import(
        '../views/pages/admin/kemahasiswaan/dataaktivitas/DataAktivitas-Create.vue'
      ),
  },
  {
    path: '/kemahasiswaan/dataaktivitas/:id/edit',
    name: 'AkademikDataAktivitasEdit',
    meta: {
      title: 'AKTIVITAS MAHASISWA - DATA AKTIVITAS',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () =>
      import(
        '../views/pages/admin/kemahasiswaan/dataaktivitas/DataAktivitas-Edit.vue'
      ),
  },
  {
    path: '/kemahasiswaan/dataaktivitas/:id/detail',
    name: 'AkademikDataAktivitasDetail',
    meta: {
      title: 'AKTIVITAS MAHASISWA - DATA AKTIVITAS',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () =>
      import(
        '../views/pages/admin/kemahasiswaan/dataaktivitas/DataAktivitas-Detail.vue'
      ),
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
    path: '/sistem-pengguna/permission',
    name: 'PenggunaPermission',
    meta: {
      title: 'PENGGUNA - PERMISSION',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () =>
      import('../views/pages/admin/pengguna/PenggunaPermission.vue'),
  },
  {
    path: '/sistem-pengguna/roles',
    name: 'PenggunaRoles',
    meta: {
      title: 'PENGGUNA - ROLES',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () =>
      import(
        '../views/pages/admin/pengguna/pengguna-roles/Pengguna-Roles-Index.vue'
      ),
  },
  {
    path: '/sistem-pengguna/roles/:role_id/detail',
    name: 'PenggunaRolesDetail',
    meta: {
      title: 'PENGGUNA - ROLES',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () =>
      import(
        '../views/pages/admin/pengguna/pengguna-roles/Pengguna-Roles-Show.vue'
      ),
  },
  //pengguna superadmin
  {
    path: '/sistem-pengguna/superadmin',
    name: 'PenggunaSuperadmin',
    meta: {
      title: 'PENGGUNA - SUPERADMIN',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () =>
      import(
        '../views/pages/admin/pengguna/pengguna-sa/Pengguna-Superadmin-Index.vue'
      ),
  },
  {
    path: '/sistem-pengguna/superadmin/create',
    name: 'PenggunaSuperadminCreate',
    meta: {
      title: 'PENGGUNA - SUPERADMIN',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () =>
      import(
        '../views/pages/admin/pengguna/pengguna-sa/Pengguna-Superadmin-Create.vue'
      ),
  },
  {
    path: '/sistem-pengguna/superadmin/:user_id/edit',
    name: 'PenggunaSuperadminEdit',
    meta: {
      title: 'PENGGUNA - SUPERADMIN',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () =>
      import(
        '../views/pages/admin/pengguna/pengguna-sa/Pengguna-Superadmin-Edit.vue'
      ),
  },
  {
    path: '/sistem-pengguna/superadmin/:user_id/detail',
    name: 'PenggunaSuperadminDetail',
    meta: {
      title: 'PENGGUNA - SUPERADMIN',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () =>
      import(
        '../views/pages/admin/pengguna/pengguna-sa/Pengguna-Superadmin-Detail.vue'
      ),
  },
  //pengguna manajemen
  {
    path: '/sistem-pengguna/manajemen',
    name: 'PenggunaManajemen',
    meta: {
      title: 'PENGGUNA - MANAJEMEN',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () =>
      import(
        '../views/pages/admin/pengguna/pengguna-manajemen/Pengguna-Manajemen-Index.vue'
      ),
  },
  {
    path: '/sistem-pengguna/manajemen/create',
    name: 'PenggunaManajemenCreate',
    meta: {
      title: 'PENGGUNA - MANAJEMEN',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () =>
      import(
        '../views/pages/admin/pengguna/pengguna-manajemen/Pengguna-Manajemen-Create.vue'
      ),
  },
  {
    path: '/sistem-pengguna/manajemen/:user_id/edit',
    name: 'PenggunaManajemenEdit',
    meta: {
      title: 'PENGGUNA - MANAJEMEN',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () =>
      import(
        '../views/pages/admin/pengguna/pengguna-manajemen/Pengguna-Manajemen-Edit.vue'
      ),
  },
  {
    path: '/sistem-pengguna/manajemen/:user_id/detail',
    name: 'PenggunaManajemenDetail',
    meta: {
      title: 'PENGGUNA - MANAJEMEN',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () =>
      import(
        '../views/pages/admin/pengguna/pengguna-manajemen/Pengguna-Manajemen-Detail.vue'
      ),
  },
  //pengguna dosen
  {
    path: '/sistem-pengguna/dosen',
    name: 'PenggunaDosen',
    meta: {
      title: 'PENGGUNA - DOSEN',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () =>
      import(
        '../views/pages/admin/pengguna/pengguna-dosen/Pengguna-Dosen-Index.vue'
      ),
  },
  {
    path: '/sistem-pengguna/dosen/:user_id/edit',
    name: 'PenggunaDosenEdit',
    meta: {
      title: 'PENGGUNA - DOSEN',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () =>
      import(
        '../views/pages/admin/pengguna/pengguna-dosen/Pengguna-Dosen-Edit.vue'
      ),
  },
  {
    path: '/sistem-pengguna/dosen/:user_id/detail',
    name: 'PenggunaDosenDetail',
    meta: {
      title: 'PENGGUNA - DOSEN',
      bodyClass: 'sidebar-mini layout-fixed',
      requiresAuth: true,
    },
    component: () =>
      import(
        '../views/pages/admin/pengguna/pengguna-dosen/Pengguna-Dosen-Detail.vue'
      ),
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
const vueBodyClass = new VueBodyClass(routes)
router.beforeEach((to, from, next) => {
  vueBodyClass.guard(to, next)
  document.title = to.meta.title
  if (to.matched.some((record) => record.meta.requiresAuth)) {
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
