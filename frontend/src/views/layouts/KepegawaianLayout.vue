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
          v-if="showrightsidebar"
        >
          <font-awesome-icon :icon="['fas', 'gear']" />
        </b-button>
      </template>
    </navbar>

    <b-sidebar
      id="left-sidebar"
      aria-labelledby="sidebar-header-title"
      v-model="sidebar_left_visible"
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
              to="/kepegawaian"
              v-if="$store.getters['auth/can']('KEPEGAWAIAN-GROUP')"
            >
              <b-icon icon="arrow-right" />
              DASHBOARD
            </b-nav-item>
            <li
              class="nav-header"
              v-if="
                $store.getters['auth/can']('KEPEGAWAIAN-DOSEN_BROWSE')
              "
            >
              PROFIL
            </li>
            <b-nav-item
              to="/kemahasiswaan/datapribadi"
              v-if="
                $store.getters['auth/can'](
                  'KEPEGAWAIAN-DOSEN_BROWSE'
                )
              "
            >
              <b-icon icon="arrow-right" />
              DATA PRIBADI
            </b-nav-item>
          </b-nav>
        </nav>
      </template>
    </b-sidebar>
    <b-sidebar
      id="right-sidebar"
      aria-labelledby="sidebar-header-right-title"
      v-model="sidebar_right_visible"
      width="300px"
      title="Options"
      right
      v-if="showrightsidebar"
    >
      <template #default>
        <slot name="filtersidebar" />
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
                    ria-hidden="true"
                  />
                  Home
                </b-breadcrumb-item>
                <slot name="page-breadcrumb" />
              </b-breadcrumb>
            </b-col>
          </b-row>
          <b-row class="mb-2" v-if="showsubheader">
            <b-col sm="12">
              <slot name="page-sub-header" />
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
    name: 'KepegawaianLayout',
    data: () => ({
      //sidebar
      sidebar_left_visible: true,
      sidebar_right_visible: false,
    }),
    props: {
      showrightsidebar: {
      	type: Boolean,
      	default: true,
      },
      temporaryleftsidebar: {
      	type: Boolean,
      	default: false,
      },
      showsubheader: {
        type: Boolean,
        default: false,
      },
		},
    methods: {
      handleVisible(isVisible) {
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
    components: {
      navbar,
      footerportal,
    },
  }
</script>
