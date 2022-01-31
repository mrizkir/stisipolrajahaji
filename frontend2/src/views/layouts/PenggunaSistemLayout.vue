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
              <b-nav-item active>
                <b-icon icon="arrow-right" />
                DASHBOARD
              </b-nav-item>
              <b-nav-item href="#" disabled>KONFIGURASI</b-nav-item>
              <b-nav-item>
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
    <main>
      <slot />
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
