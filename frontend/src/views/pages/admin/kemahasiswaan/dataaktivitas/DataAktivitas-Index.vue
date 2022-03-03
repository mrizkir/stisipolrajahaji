<template>
  <KemahasiswaanLayout>
    <template v-slot:page-header>Data Aktivitas</template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item to="/kemahasiswaan">Kemahasiswaan</b-breadcrumb-item>
      <b-breadcrumb-item active>Data Aktivitas</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      <b-container
        fluid
        v-if="
          $store.getters['auth/can']('KEMAHASISWAAN-AKTIVITAS_BROWSE')
        "
      >
        <b-row>
          <b-col>
            <b-card no-body class="card-primary card-outline">
              <template #header>
                <h3 class="card-title">Daftar Data Aktivitas</h3>
                <div class="card-tools">
                  <b-button
                    size="xs"
                    variant="outline-primary"
                    @click.stop="clearsettingpage"                    
                    v-b-tooltip.hover
                    title="Hapus Setting Halaman"
                    class="mr-1"
                  >
                    <b-icon icon="trash2" />
                  </b-button>
                  <b-button
                    size="xs"
                    variant="outline-primary"
                    @click.stop="$router.push(url + '/create')"
                    v-if="$store.getters['auth/can']('KEMAHASISWAAN-AKTIVITAS_STORE')"
                    v-b-tooltip.hover
                    title="Tambah Data Aktivitas"
                  >
                    <b-icon icon="plus-circle" />
                  </b-button>
                </div>
              </template>
            </b-card>
          </b-col>
        </b-row>
      </b-container>
    </template>
  </KemahasiswaanLayout>
</template>
<script>
  import KemahasiswaanLayout from '@/views/layouts/KemahasiswaanLayout'
  export default {
    name: 'DataAktivitasIndex',
    created() {
      this.$store.dispatch('uiadmin/addToPages', {
				name: 'dataaktivitas',
        loaded: false,
        perPage: this.perPage,
        currentPage: this.currentPage,
        sortBy: this.sortBy,
        sortDesc: this.sortDesc,
        search: this.search,
			})
    },
    setup() {
      return {  
        url: '/kemahasiswaan/dataaktivitas',
      }
    },
    methods: {
      updatesettingpage() {
        var page = this.$store.getters['uiadmin/Page']('dataaktivitas')
        page.perPage = this.perPage
        page.currentPage = this.currentPage
        page.sortBy = this.sortBy
        page.sortDesc = this.sortDesc
        page.search = this.search
        this.$store.dispatch('uiadmin/updatePage', page)
      },
      clearsettingpage() {
        var page = this.$store.getters['uiadmin/Page']('dataaktivitas')
        page.loaded = false
        page.perPage = 10
        page.currentPage = 1
        page.sortBy = 'nama_aktivitas'
        page.sortDesc = false
        page.search = null
        this.$store.dispatch('uiadmin/updatePage', page)

        this.$bvToast.toast('Setting halaman sudah kembali ke default, silahkan refresh', {
          title: 'Pesan Sistem',
          variant: 'info',
          autoHideDelay: 5000,
          appendToast: false
        })
      },
    },
    components: {
      KemahasiswaanLayout,
    },
  }
</script>
