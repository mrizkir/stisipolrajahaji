<template>
  <div class="wrapper">
    <navbar>
      <template v-slot:sidebartoggle>
        <b-button
          variant="link"
          @click.stop="sidebar_left_visible = !sidebar_left_visible"
        >
          <font-awesome-icon :icon="['fas', 'bars']" />
        </b-button>
      </template>
      <template v-slot:sidebartoggleright>
         <b-button
          variant="link"
          @click.stop="sidebar_right_visible = !sidebar_right_visible"
        >
          <font-awesome-icon :icon="['fas', 'gear']" />
        </b-button>
      </template>
    </navbar>

    <b-sidebar
      id="left-sidebar"
      aria-labelledby="sidebar-header-left-title"
      v-model="sidebar_left_visible"
      width="250px"
    >
      <template #header>
        <div class="d-flex align-items-center flex-column">
          <b-avatar variant="primary" text="BV" class="mb-3 mt-2" size="5rem" />
          <h5 id="sidebar-header-left-title">ADMIN</h5>
          <div class="divider"></div>
        </div>
      </template>
      <template #default>
        <nav>
          <b-nav class="nav-pills nav-sidebar" vertical>
            <b-nav-item
              to="/spmb"
              v-if="$store.getters['auth/can']('SPMB-GROUP')"
            >
              <b-icon icon="arrow-right" />
              DASHBOARD
            </b-nav-item>            
          </b-nav>
        </nav>
      </template>
    </b-sidebar>
    <b-sidebar
      id="right-sidebar"
      aria-labelledby="sidebar-header-right-title"
      v-model="sidebar_right_visible"
      width="250px"
      right
    >
      <template #header>
        <div class="d-flex align-items-center flex-column">          
          <h5 id="sidebar-header-right-title">OPTIONS</h5>
          <div class="divider"></div>
        </div>
      </template>
      <template #default>
        test
      </template>
    </b-sidebar>
    <!-- main content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <b-container fluid>
          <b-row class="mb-2">
            <b-col sm="4">
              <h1><slot name="page-header" /></h1>
            </b-col>
            <b-col sm="8">
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
    <div v-b-visible.once="handleVisibleLeft" class="d-xs-none"></div>
  </div>
</template>
<script>
  import navbar from '@/components/panels/navbaradmin.vue'
  import footerportal from '@/components/panels/footeradmin.vue'
  export default {
    name: 'SPMBLayout',
    data: () => ({
      //sidebar
      sidebar_left_visible: true,
      sidebar_right_visible: false,
    }),
    components: {
      navbar,
      footerportal,
    },
    methods: {
      handleVisibleLeft(isVisible) {
        this.sidebar_left_visible = isVisible
      },
    },
    watch: {
      sidebar_left_visible(val) {
        const el = document.body
        if (val) {
          el.classList.remove('sidebar-collapse')
        } else {
          el.classList.add('sidebar-collapse')
        }
      },
    },
  }
</script>
