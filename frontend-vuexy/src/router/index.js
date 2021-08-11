import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  scrollBehavior() {
    return { x: 0, y: 0 }
  },
  routes: [
    {
      path: '/',
      name: 'Dashboard',
      component: () => import('@/views/pages/front/FrontDashboard.vue'),
      meta: {
        pageTitle: 'Dashboard',
        breadcrumb: [
          {
            text: 'Dashboard',
            active: true,
          },
        ],
      },
    },
    {
      path: '/error-404',
      name: 'error-404',
      component: () => import('@/views/error/Error404.vue'),
      meta: {
        layout: 'full',
      },
    },
    {
      path: '*',
      redirect: 'error-404',
    },
  ],
})

router.afterEach(() => {
  const appLoading = document.getElementById('loading-bg')
  if (appLoading) {
    appLoading.style.display = 'none'
  }
})

export default router
