<template>
  <div class="wrapper">
    <navbar>
      <template v-slot:sidebartoggle>
        <b-button variant="link" @click.stop="sidebar_visible = !sidebar_visible">
          <font-awesome-icon :icon="['fas', 'bars']" />
        </b-button>        
      </template>
    </navbar>

    <b-sidebar
      id="left-sidebar"
      aria-labelledby="sidebar-header-title"
      v-model="sidebar_visible"      
      width="250px"
    >
      <template #header>
        <div class="d-flex align-items-center flex-column">
          <b-avatar variant="primary" text="BV" class="mb-3 mt-2" size="5rem"></b-avatar>
          <h5 id="sidebar-header-title">ADMIN</h5>
          <div class="divider"></div>
        </div>
      </template>
      <template #default>
        <nav>
          <b-nav
            class="nav-pills nav-sidebar"
            vertical
          >
            <b-nav-item
              to="/kemahasiswaan"
              v-if="$store.getters['auth/can']('KEMAHASISWAAN-GROUP')"
            >
              <b-icon icon="arrow-right" />
              DASHBOARD
            </b-nav-item>
            <li class="nav-header" v-if="$store.getters['auth/can']('KEMAHASISWAAN-AKTIVITAS_BROWSE')">AKTIVITAS MAHASISWA</li>
            <b-nav-item
              to="/kemahasiswaan/jenisaktivitas"
              v-if="$store.getters['auth/can']('KEMAHASISWAAN-JENIS-AKTIVITAS_BROWSE')"
            >
              <b-icon icon="arrow-right" />
              JENIS AKTIVITAS
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
                  <b-icon icon="house-fill" scale="1.25" shift-v="1.25" aria-hidden="true"></b-icon>
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
    name: 'KemahasiswaanLayout',
     data: () => ({
      //sidebar
			sidebar_visible: true,      
		}),
    components: {
      navbar,
      footerportal,
    },
    methods: {
      handleVisible(isVisible) {        
        this.sidebar_visible = isVisible
      }
    },
    watch: {
      sidebar_visible(val) {
        const el = document.body
        if(val) {
          el.classList.remove('sidebar-collapse')
        } else {
          el.classList.add('sidebar-collapse')
        }
      }
    },
  }
</script>
