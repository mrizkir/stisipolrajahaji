<template>
  <FeederLayout :showsubheader="true">
    <template v-slot:page-header>Kelas Perkuliahan</template>
    <template v-slot:page-sub-header>
      Program Studi <strong>{{ nama_prodi }}</strong> T.A <strong>{{ tahun_akademik }}</strong>
      Semester <strong>{{ $store.getters["uiadmin/getNamaSemester"](semester_akademik) }}</strong>
    </template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item to="/feeder">Feeder</b-breadcrumb-item>
      <b-breadcrumb-item active>Kelas Perkuliahan</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      <b-container
        fluid
        v-if="$store.getters['auth/can']('FEEDER-PERKULIAHAN-KELAS_BROWSE')"
      >
        <b-row>
          <b-col>
            <b-card no-body class="card-primary card-outline">
              <template #header>
                <h3 class="card-title">Daftar Kelas Perkuliahan</h3>
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
                </div>
              </template>
              <b-card-body class="p-0">
                <b-table
                  id="datatable"
                  primary-key="idjenis"
                  :fields="fields"
                  :items="datatable"
                  :sort-by.sync="sortBy"
                  :sort-desc.sync="sortDesc"
                  :busy="datatableLoading"
                  outlined
                  striped
                  hover
                  show-empty
                  responsive
                  no-local-sorting
                  small
                >
                  <template #table-busy>
                    <div class="text-center text-danger my-2">
                      &nbsp;
                    </div>
                  </template>
                  <template #cell(no)="{ index }">
                    {{ index + parseInt(from) }}
                  </template>               
                  <template #emptytext>
                    tidak ada data yang bisa ditampilkan
                  </template>
                </b-table>
              </b-card-body>
            </b-card>
          </b-col>
        </b-row>
      </b-container>
    </template>
    <template v-slot:filtersidebar>
      <Filter6
        :prodi="prodi_id"
        :tahun_akademik="tahun_akademik"
        :semester_akademik="semester_akademik"
        v-on:changeTahunAkademik="changeTahunAkademik"
        v-on:changeProdi="changeProdi"
        v-on:changeSemesterAkademik="changeSemesterAkademik"
        ref="filter6"
      />
      <b-row class="m-2">
        <b-col>
          Tampilkan data per <b-form-input v-model="perPage" type="number" size="xs" /> baris
          pada halaman ke <b-form-input v-model="from" type="number" size="xs" />
          <b-button variant="primary" @click.stop="filterKRS">
            Filter
          </b-button>
        </b-col>
      </b-row>
    </template>
  </FeederLayout>
</template>
<script>
  import FeederLayout from '@/views/layouts/FeederLayout'
  import Filter6 from '@/components/widgets/FilterMode6'
  export default {
    name: 'FeederKelasPerkuliahanIndex',
    setup() {
      return {
        url: '/feeder/perkuliahan/kelas',
      }
    },
    created() {
      this.$store.dispatch('uiadmin/addToPages', {
        name: 'feeder-kelas-perkuliahan',
        loaded: false,
        from: this.from,
        perPage: this.perPage,
        currentPage: this.currentPage,
        sortBy: this.sortBy,
        sortDesc: this.sortDesc,
        search: this.search,
        prodi_id: this.$store.getters['uiadmin/getProdiID'],
        tahun_akademik: this.$store.getters['uiadmin/getTahunAkademik'],
        semester_akademik: this.$store.getters['uiadmin/getSemesterAkademik'],
      })
      this.prodi_id = this.$store.getters['uiadmin/AtributeValueOfPage'](
        'feeder-kelas-perkuliahan',
        'prodi_id'
      )
      this.nama_prodi = this.$store.getters['uiadmin/getProdiName'](
        this.prodi_id
      )
      this.tahun_akademik = this.$store.getters['uiadmin/AtributeValueOfPage'](
        'feeder-kelas-perkuliahan',
        'tahun_akademik'
      )
      this.semester_akademik =
        this.$store.getters['uiadmin/AtributeValueOfPage'](
          'feeder-kelas-perkuliahan',
          'semester_akademik'
        )
    },
    mounted() {
      this.firstloading = false
      this.$refs.filter6.setFirstTimeLoading(this.firstloading)      
      this.initialize()
    },
    data: () => ({
      datatableLoading: false,
      firstloading: true,
      prodi_id: null,
      nama_prodi: null,
      tahun_akademik: null,
      semester_akademik: null,

      //setting table
      from: 1,
      currentPage: 1,
      perPage: 10,
      totalRows: 0,
      datatable: [],
      fields: [
        {
          label: 'No.',
          key: 'no',
          thStyle: 'width: 50px',
        },
        {
          key: 'nim',
          label: 'NIRM',
          thStyle: 'width: 100px',
        },
        {
          key: 'nama_mahasiswa',
          label: 'Nama Mahasiswa',
          thStyle: 'width: 250px',
        },
        {
          key: 'angkatan',
          label: 'Angk.',
          thStyle: 'width: 70px',
        },
        {
          key: 'kode_mata_kuliah',
          label: 'Kode',
          thStyle: 'width: 70px',
        },
        {
          key: 'nama_mata_kuliah',
          label: 'Nama Matakuliah',
          thStyle: 'width: 200px',
        },
        {
          key: 'sks_mata_kuliah',
          label: 'SKS',
          thStyle: 'width: 50px',
        },
        {
          key: 'nama_kelas_kuliah',
          label: 'Kelas',
          thStyle: 'width: 50px',
        },
      ],
      sortBy: 'nama_aktivitas',
      sortDesc: false,
      search: null,
      dataItem: {},
    }),
    methods: {
      updatesettingpage() {
        var page = this.$store.getters['uiadmin/Page']('feeder-kelas-perkuliahan')
        page.from = this.from
        page.perPage = this.perPage
        page.currentPage = this.currentPage
        page.sortBy = this.sortBy
        page.sortDesc = this.sortDesc
        page.search = this.search
        this.$store.dispatch('uiadmin/updatePage', page)
      },
      clearsettingpage() {
        var page = this.$store.getters['uiadmin/Page']('feeder-kelas-perkuliahan')      
        page.loaded = false
        page.from = 1
        page.perPage = 10
        page.currentPage = 1
        page.sortBy = 'nama_mahasiswa'
        page.sortDesc = false
        page.search = null
        page.prodi_id = this.$store.getters['uiadmin/getProdiID']
        page.tahun_akademik = this.$store.getters['uiadmin/getTahunAkademik']
        page.semester_akademik =
          this.$store.getters['uiadmin/getSemesterAkademik']
        this.$store.dispatch('uiadmin/updatePage', page)

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
        var page = this.$store.getters['uiadmin/Page']('feeder-kelas-perkuliahan')
        page.prodi_id = this.prodi_id
        this.$store.dispatch('uiadmin/updatePage', page)
        this.nama_prodi = this.$store.getters['uiadmin/getProdiName'](
          this.prodi_id
        )
      },
      changeTahunAkademik(val) {
        this.tahun_akademik = val
        var page = this.$store.getters['uiadmin/Page']('feeder-kelas-perkuliahan')
        page.tahun_akademik = this.tahun_akademik
        this.$store.dispatch('uiadmin/updatePage', page)
      },
      changeSemesterAkademik(val) {
        this.semester_akademik = val
        var page = this.$store.getters['uiadmin/Page']('feeder-kelas-perkuliahan')
        page.semester_akademik = this.semester_akademik
        this.$store.dispatch('uiadmin/updatePage', page)
      },
      async initialize() {
        this.datatableLoading = true
        var page = this.$store.getters['uiadmin/Page']('feeder-kelas-perkuliahan')
        await this.$ajax
          .post(this.url, 
          {
            token: this.$store.getters['uiadmin/getFeederToken'],
            perPage: page.perPage,
            currentPage: page.currentPage,
            sortBy: page.sortBy,
            sortDesc: page.sortDesc,
            search: page.search,
            prodi_id: page.prodi_id,
            nama_prodi: this.$store.getters['uiadmin/getProdiNameForFeeder'](page.prodi_id),
            semester_akademik: this.semester_akademik,
            tahun_akademik: this.tahun_akademik,
          },
          {
            headers: {
              Authorization: this.$store.getters['auth/Token'],
            }
          })
          .then(({ data }) => {
            this.datatable = data.data            
            this.datatableLoading = false
          })
      },
      filterKRS() {
        this.updatesettingpage()
        this.initialize()
      }
    },
    components: {
      FeederLayout,
      Filter6,
    },
  }
</script>
