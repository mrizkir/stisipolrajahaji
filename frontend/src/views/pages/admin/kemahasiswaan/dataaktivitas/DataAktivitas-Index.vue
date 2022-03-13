<template>
  <KemahasiswaanLayout :showsubheader="true">
    <template v-slot:page-header>Data Aktivitas</template>
    <template v-slot:page-sub-header>
      Program Studi <strong>{{ nama_prodi }}</strong> T.A
      <strong>{{ tahun_akademik }}</strong>
      Semester
      <strong>
        {{ store.getters['uiadmin/getNamaSemester'](semester_akademik) }}
      </strong>
    </template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item to="/kemahasiswaan">Kemahasiswaan</b-breadcrumb-item>
      <b-breadcrumb-item active>Data Aktivitas</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      <b-container
        fluid
        v-if="$store.getters['auth/can']('KEMAHASISWAAN-AKTIVITAS_BROWSE')"
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
                    @click.stop="addAktivitas"
                    v-if="
                      $store.getters['auth/can'](
                        'KEMAHASISWAAN-AKTIVITAS_STORE'
                      )
                    "
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
    </template>
  </KemahasiswaanLayout>
</template>
<script>
  import KemahasiswaanLayout from '@/views/layouts/KemahasiswaanLayout'
  import Filter6 from '@/components/widgets/FilterMode6'
  export default {
    name: 'DataAktivitasIndex',
    setup() {
      return {
        url: '/kemahasiswaan/dataaktivitas',
      }
    },
    created() {
      this.$store.dispatch('uiadmin/addToPages', {
        name: 'dataaktivitas',
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
        'dataaktivitas',
        'prodi_id'
      )
      this.nama_prodi = this.$store.getters['uiadmin/getProdiName'](
        this.prodi_id
      )
      this.tahun_akademik = this.$store.getters['uiadmin/AtributeValueOfPage'](
        'dataaktivitas',
        'tahun_akademik'
      )
      this.semester_akademik = this.$store.getters[
        'uiadmin/AtributeValueOfPage'
      ]('dataaktivitas', 'semester_akademik')
    },
    mounted() {
      this.firstloading = false
      this.$refs.filter6.setFirstTimeLoading(this.firstloading)
    },
    data: () => ({
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
          key: 'nama_aktivitas',
          label: 'Nama',
        },
        {
          label: 'Aksi',
          key: 'aksi',
          thStyle: 'width: 150px',
        },
      ],
      sortBy: 'nama_aktivitas',
      sortDesc: false,
      search: null,
      dataItem: {},
    }),
    methods: {
      updatesettingpage() {
        var page = this.$store.getters['uiadmin/Page']('dataaktivitas')
        page.from = this.from
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
        page.from = 1
        page.perPage = 10
        page.currentPage = 1
        page.sortBy = 'nama_aktivitas'
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
        var page = this.$store.getters['uiadmin/Page']('dataaktivitas')
        page.prodi_id = this.prodi_id
        this.$store.dispatch('uiadmin/updatePage', page)
        this.nama_prodi = this.$store.getters['uiadmin/getProdiName'](
          this.prodi_id
        )
      },
      changeTahunAkademik(val) {
        this.tahun_akademik = val
        var page = this.$store.getters['uiadmin/Page']('dataaktivitas')
        page.tahun_akademik = this.tahun_akademik
        this.$store.dispatch('uiadmin/updatePage', page)
      },
      changeSemesterAkademik(val) {
        this.semester_akademik = val
        var page = this.$store.getters['uiadmin/Page']('dataaktivitas')
        page.semester_akademik = this.semester_akademik
        this.$store.dispatch('uiadmin/updatePage', page)
      },
      addAktivitas() {
        if (this.prodi_id > 0) {
          this.$router.push(this.url + '/create')
        } else {
          this.$bvToast.toast(
            'Tidak bisa menambah, silahkan pilih Prodi, Tahun Akademik, dan Semester',
            {
              title: 'Pesan Sistem',
              variant: 'warning',
              autoHideDelay: 5000,
              appendToast: false,
            }
          )
        }
      },
    },
    components: {
      KemahasiswaanLayout,
      Filter6,
    },
  }
</script>
