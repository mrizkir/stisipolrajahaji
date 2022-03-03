<template>
  <div class="wrapper">
    <navbar>
      <template v-slot:sidebartoggle>
        <b-button
          variant="link"
          @click.stop="sidebar_visible = !sidebar_visible"
        >
          <font-awesome-icon :icon="['fas', 'bars']" />
        </b-button>
      </template>
    </navbar>

    <b-sidebar
      id="left-sidebar"
      aria-labelledby="sidebar-header-title"
      :visible="sidebar_visible"
      width="250px"
    >
      <template #header>
        <div class="d-flex align-items-center flex-column">
          <b-avatar variant="primary" text="BV" class="mb-3 mt-2" size="5rem" />
          <h5 id="sidebar-header-title">ADMIN</h5>
          <div class="divider"></div>
        </div>
      </template>
      <template #default>
        <nav>
          <b-nav class="nav-pills nav-sidebar" vertical>
            <b-nav-item
              to="/sistem-pengguna"
              v-if="$store.getters['auth/can']('SYSTEM-USERS-GROUP')"
            >
              <b-icon icon="arrow-right" />
              DASHBOARD
            </b-nav-item>
            <li class="nav-header">KONFIGURASI</li>
            <b-nav-item
              to="/sistem-pengguna/permission"
              v-if="
                $store.getters['auth/can']('SYSTEM-SETTING-PERMISSIONS_BROWSE')
              "
            >
              <b-icon icon="arrow-right" />
              PERMISSION
            </b-nav-item>
            <b-nav-item
              to="/sistem-pengguna/roles"
              v-if="$store.getters['auth/can']('SYSTEM-SETTING-ROLES_BROWSE')"
            >
              <b-icon icon="arrow-right" />
              ROLE
            </b-nav-item>
            <li class="nav-header">PENGGUNA</li>
            <b-nav-item
              to="/sistem-pengguna/superadmin"
              v-if="
                $store.getters['auth/can']('SYSTEM-USERS-SUPERADMIN_BROWSE')
              "
            >
              <b-icon icon="arrow-right" />
              SUPERADMIN
            </b-nav-item>
            <b-nav-item
              to="/sistem-pengguna/manajemen"
              v-if="$store.getters['auth/can']('SYSTEM-USERS-AKADEMIK_BROWSE')"
            >
              <b-icon icon="arrow-right" />
              MANAJEMEN
            </b-nav-item>
            <b-nav-item
              to="/sistem-pengguna/dosen"
              v-if="$store.getters['auth/can']('SYSTEM-USERS-DOSEN_BROWSE')"
            >
              <b-icon icon="arrow-right" />
              DOSEN
            </b-nav-item>
          </b-nav>
        </nav>
      </template>
    </b-sidebar>
    <!-- main content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <b-container fluid>
          <b-row class="mb-2">
            <b-col sm="6">
              <h1><slot name="page-header" /></h1>
            </b-col>
            <b-col sm="6">
              <b-breadcrumb class="float-sm-right">
                <b-breadcrumb-item
                  :to="'/dashboard/' + $store.getters['auth/AccessToken']"
                >
                  <b-icon
                    icon="house-fill"
                    scale="1.25"
                    shift-v="1.25"
                    aria-hidden="true"
                  />
                  Home
                </b-breadcrumb-item>
                <slot name="page-breadcrumb" />
              </b-breadcrumb>
            </b-col>
          </b-row>
        </b-container>
      </section>
      <section class="content">
        <slot name="page-content" />
      </section>
    </div>
    <footerportal />
    <div v-b-visible.once="handleVisible" class="d-xs-none"></div>
  </div>
</template>
<script>
  import navbar from '@/components/panels/navbaradmin.vue'
  import footerportal from '@/components/panels/footeradmin.vue'
  export default {
    name: 'PenggunaSistemLayout',
    data: () => ({
      //sidebar
      sidebar_visible: true,
    }),
    methods: {
      handleVisible(isVisible) {
        this.sidebar_visible = isVisible
      },
    },
    watch: {
      sidebar_visible(val) {
        const el = document.body
        if (val) {
          el.classList.remove('sidebar-collapse')
        } else {
          el.classList.add('sidebar-collapse')
        }
      },
    },
    components: {
      navbar,
      footerportal,
    },
  }
</script>
