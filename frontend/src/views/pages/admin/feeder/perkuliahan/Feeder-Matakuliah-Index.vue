<template>
  <FeederLayout :showsubheader="true">
    <template v-slot:page-header>Mata Kuliah</template>
    <template v-slot:page-sub-header>
      Program Studi <strong>{{ nama_prodi }}</strong>
    </template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item to="/feeder">Feeder</b-breadcrumb-item>
      <b-breadcrumb-item active>Mata Kuliah</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      <b-container
        fluid
        v-if="$store.getters['auth/can']('FEEDER-PERKULIAHAN-TRAKM_BROWSE')"
      >
        <b-row>
          <b-col>
            <b-card no-body class="card-primary card-outline">
              <template #header>
                <h3 class="card-title">Daftar Akivitas Mahasiswa</h3>
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
                    @click.stop="printtoexcel"
                    v-b-tooltip.hover
                    title="Cetak"
                    :disabled="btnLoading"
                  >
                    <b-icon icon="printer" />
                  </b-button>
                </div>
              </template>
            </b-card>
          </b-col>
        </b-row>
      </b-container>
    </template>
    <template v-slot:filtersidebar>
      <Filter4
        :prodi="prodi_id"
        v-on:changeProdi="changeProdi"
        ref="filter4"
      />
    </template>
  </FeederLayout>
</template>
<script>
  import FeederLayout from '@/views/layouts/FeederLayout'
  import Filter4 from '@/components/widgets/FilterMode4'
   export default {
    name: 'FeederMatakuliahIndex',
    setup() {
      return {
        url: '/feeder/perkuliahan/makul',
      }
    },
    created() {
      this.$store.dispatch('uiadmin/addToPages', {
        name: 'feeder-perkuliahan-makul',
        loaded: false,
        from: this.from,
        perPage: this.perPage,
        currentPage: this.currentPage,
        sortBy: this.sortBy,
        sortDesc: this.sortDesc,
        search: this.search,
        prodi_id: this.$store.getters['uiadmin/getProdiID'],        
      })
      this.prodi_id = this.$store.getters['uiadmin/AtributeValueOfPage'](
        'feeder-perkuliahan-makul',
        'prodi_id'
      )
      this.nama_prodi = this.$store.getters['uiadmin/getProdiName'](
        this.prodi_id
      )
    },
    mounted() {
      this.firstloading = false
      this.$refs.filter4.setFirstTimeLoading(this.firstloading)
      this.initialize()
    },
    data: () => ({
      datatableLoading: false,
      btnLoading: false,
      firstloading: true,
      prodi_id: null,
      nama_prodi: null,

      //setting table
      from: 1,
      currentPage: 1,
      perPage: 10,
      totalRows: 0,
      datatable: [],

      sortBy: 'nmatkul',
      sortDesc: false,
      search: null,
      dataItem: {},
    }),
    methods: {
      updatesettingpage() {
        var page = this.$store.getters['uiadmin/Page'](
          'feeder-perkuliahan-makul'
        )
        page.from = this.from
        page.perPage = this.perPage
        page.currentPage = this.currentPage
        page.sortBy = this.sortBy
        page.sortDesc = this.sortDesc
        page.search = this.search
        this.$store.dispatch('uiadmin/updatePage', page)
      },
      clearsettingpage() {
        var page = this.$store.getters['uiadmin/Page'](
          'feeder-perkuliahan-makul'
        )
        page.loaded = false
        page.from = 1
        page.perPage = 10
        page.currentPage = 1
        page.sortBy = 'nmatkul'
        page.sortDesc = false
        page.search = null
        page.prodi_id = this.$store.getters['uiadmin/getProdiID']
        
        this.$bvToast.toast(
          'Setting halaman sudah kembali ke default, silahkan refresh',
          {
            title: 'Pesan Sistem',
            variant: 'info',
            autoHideDelay: 5000,
            appendToast: false,
          }
        )
      },
      changeProdi(val) {
        this.prodi_id = val
        var page = this.$store.getters['uiadmin/Page'](
          'feeder-perkuliahan-makul'
        )
        page.prodi_id = this.prodi_id
        this.$store.dispatch('uiadmin/updatePage', page)
        this.nama_prodi = this.$store.getters['uiadmin/getProdiName'](
          this.prodi_id
        )
      },
      async initialize() {
        this.datatableLoading = true
        var page = this.$store.getters['uiadmin/Page'](
          'feeder-perkuliahan-makul'
        )
        await this.$ajax
          .post(
            this.url,
            {
              token: this.$store.getters['uiadmin/getFeederToken'],
              perPage: page.perPage,
              currentPage: page.currentPage,
              sortBy: page.sortBy,
              sortDesc: page.sortDesc,
              search: page.search,
              prodi_id: page.prodi_id,
              nama_prodi: this.$store.getters['uiadmin/getProdiNameForFeeder'](
                page.prodi_id
              ),
              semester_akademik: this.semester_akademik,
              tahun_akademik: this.tahun_akademik,
            },
            {
              headers: {
                Authorization: this.$store.getters['auth/Token'],
              },
            }
          )
          .then(({ data }) => {
            this.from = data.result.from
            this.totalRows = data.result.total
            this.datatable = data.result.data
            page.loaded = true
            this.$store.dispatch('uiadmin/updatePage', page)
            this.$nextTick(() => {
              this.currentPage = page.currentPage
            })
            this.datatableLoading = false
          })
      },
      handleSearch() {
        this.currentPage = 1
        this.updatesettingpage()
        this.initialize()
      },
      handlePageChange(value) {
        this.currentPage = value
        this.updatesettingpage()
        this.initialize()
      },
    },
    components: {
      FeederLayout,
      Filter4,
    },
  }
</script>

    