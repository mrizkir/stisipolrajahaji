<template>
  <div class="wrapper">
    <navbar>
      <template v-slot:sidebartoggle>
        <b-button variant="dark" v-b-toggle.left-sidebar>          
          <b-icon icon="layout-text-sidebar"></b-icon>
        </b-button>
      </template>
    </navbar>
    <b-sidebar
      id="left-sidebar"
      aria-labelledby="sidebar-header-title"
      :visible="sidebar_visible"
      bg-variant="dark"
      text-variant="light"
      backdrop-variant="success"
      backdrop
      no-header
    >
      <template #default>
        <div class="p-3">
          <div class="d-flex align-items-center flex-column">
            <b-avatar variant="primary" text="BV" class="mb-3 mt-2" size="5rem"></b-avatar>
            <h5 id="sidebar-header-title">ADMIN</h5>
            <div class="divider"></div>
          </div>
          <nav class="mb-3">
            <b-nav vertical>
              <b-nav-item
                to="/sistem-pengguna"
                v-if="$store.getters['auth/can']('SYSTEM-USERS-GROUP')"
              >
                <b-icon icon="arrow-right" />
                DASHBOARD
              </b-nav-item>
              <b-nav-item href="#" disabled>KONFIGURASI</b-nav-item>
              <b-nav-item
                to="/sistem-pengguna/permission"
                v-if="$store.getters['auth/can']('SYSTEM-SETTING-PERMISSIONS')"
              >
                <b-icon icon="arrow-right" />
                PERMISSION
              </b-nav-item>              
              <b-nav-item>
                <b-icon icon="arrow-right" />
                ROLE
              </b-nav-item>
            </b-nav>
          </nav>
        </div>
      </template>
    </b-sidebar>
    <b-container fluid>      
      <b-row>
        <b-col>
          <b-breadcrumb>
            <b-breadcrumb-item
              :to="'/dashboard/' + $store.getters['auth/AccessToken']"
            >
              <b-icon icon="house-fill" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
              Home
            </b-breadcrumb-item>            
            <slot name="page-breadcrumb" />          
          </b-breadcrumb>
        </b-col>
      </b-row>
      <div>
        <h4><slot name="page-header" /></h4>
      </div>
    </b-container>
    <main>
      <slot name="page-content" />
    </main>

  </div>
</template>
<script>
  import navbar from '@/components/panels/navbaradmin.vue'
  export default {
    name: 'PenggunaSistemLayout',
     data: () => ({
      //sidebar
			sidebar_visible: false,
		}),
    components: {
      navbar
    },
  }
</script>
