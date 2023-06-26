<template>
  <FeederLayout :showsubheader="true">
    <template v-slot:page-header>Aktivitas Kuliah MHS</template>
    <template v-slot:page-sub-header>
      Program Studi <strong>{{ nama_prodi }}</strong> T.A <strong>{{ tahun_akademik }}</strong>
      Semester <strong>{{ $store.getters["uiadmin/getNamaSemester"](semester_akademik) }}</strong>
    </template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item to="/feeder">Feeder</b-breadcrumb-item>
      <b-breadcrumb-item active>Aktivitas Kuliah Mahasiswa</b-breadcrumb-item>
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
              <b-card-body class="p-0">
                <b-table
                  id="datatable"
                  primary-key="nim"
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
                    {{ index + from }}
                  </template>
                  <template #cell(k_status)="{ item }">
                    {{
                      $store.getters['uiadmin/getStatusMahasiswa'](
                        item.k_status
                      )
                    }}
                  </template>
                  <template #emptytext>
                    tidak ada data yang bisa ditampilkan
                  </template>
                </b-table>
              </b-card-body>
              <template #footer>
                <strong>Total Record:</strong> {{ totalRows }}
                <b-pagination
                  v-model="currentPage"
                  :total-rows="totalRows"
                  :per-page="perPage"
                  aria-controls="datatable"
                  class="pagination-sm m-0 float-right"
                  @change="handlePageChange"
                  responsive
                  pills
                >
                </b-pagination>
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
      <b-row class="m-2">
        <b-col>
          Tampilkan data per <b-form-input v-model="perPage" type="number" size="xs" /> baris
          pada halaman ke <b-form-input v-model="from" type="number" size="xs" />
          <b-button variant="primary" @click.stop="filterKelasKuliah">
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
    name: 'FeederTRAKMIndex',
    setup() {
      return {
        url: '/feeder/perkuliahan/trakm',
      }
    },
    created() {
      this.$store.dispatch('uiadmin/addToPages', {
        name: 'feeder-perkuliahan-trakm',
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
        'feeder-perkuliahan-trakm',
        'prodi_id'
      )
      this.nama_prodi = this.$store.getters['uiadmin/getProdiName'](
        this.prodi_id
      )
      this.tahun_akademik = this.$store.getters['uiadmin/AtributeValueOfPage'](
        'feeder-perkuliahan-trakm',
        'tahun_akademik'
      )
      this.semester_akademik = this.$store.getters[
        'uiadmin/AtributeValueOfPage'
      ]('feeder-perkuliahan-trakm', 'semester_akademik')
    },
    mounted() {
      this.firstloading = false
      this.$refs.filter6.setFirstTimeLoading(this.firstloading)
      this.initialize()
    },
    data: () => ({
      datatableLoading: false,
      btnLoading: false,
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
          key: 'no',
          label: 'No.',
          thStyle: 'width: 50px',
        },
        {
          key: 'nim',
          label: 'NIM',
          thStyle: 'width: 100px',
        },
        {
          key: 'nama_mhs',
          label: 'Nama Mahasiswa',
          thStyle: 'width: 250px',
        },
        {
          key: 'tahun_masuk',
          label: 'Angkatan',
          thStyle: 'width: 100px',
        },
        {
          key: 'k_status',
          label: 'Status',
          thStyle: 'width: 90px',
        },
        {
          key: 'sks',
          label: 'SKS',
          thStyle: 'width: 50px',
        },
        {
          key: 'ips',
          label: 'IPS',
          thStyle: 'width: 50px',
        },
        {
          key: 'ipk',
          label: 'IPK',
          thStyle: 'width: 50px',
        },
        {
          key: 'spp',
          label: 'SPP',
          thStyle: 'width: 100px',
        },
        {
          key: 'nama_pembiayaan',
          label: 'PEMBIAYAAN',
          thStyle: 'width: 100px',
        },
      ],
      sortBy: 'nama_mhs',
      sortDesc: false,
      search: null,
      dataItem: {},
    }),
    methods: {
      updatesettingpage() {
        var page = this.$store.getters['uiadmin/Page'](
          'feeder-perkuliahan-trakm'
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
          'feeder-perkuliahan-trakm'
        )
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
        var page = this.$store.getters['uiadmin/Page'](
          'feeder-perkuliahan-trakm'
        )
        page.prodi_id = this.prodi_id
        this.$store.dispatch('uiadmin/updatePage', page)
        this.nama_prodi = this.$store.getters['uiadmin/getProdiName'](
          this.prodi_id
        )
      },
      changeTahunAkademik(val) {
        this.tahun_akademik = val
        var page = this.$store.getters['uiadmin/Page'](
          'feeder-perkuliahan-trakm'
        )
        page.tahun_akademik = this.tahun_akademik
        this.$store.dispatch('uiadmin/updatePage', page)
      },
      changeSemesterAkademik(val) {
        this.semester_akademik = val
        var page = this.$store.getters['uiadmin/Page'](
          'feeder-perkuliahan-trakm'
        )
        page.semester_akademik = this.semester_akademik
        this.$store.dispatch('uiadmin/updatePage', page)
      },
      async initialize() {
        this.datatableLoading = true
        var page = this.$store.getters['uiadmin/Page'](
          'feeder-perkuliahan-trakm'
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
      filterKelasKuliah() {
        this.updatesettingpage()
        this.initialize()
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
      async printtoexcel() {
				this.btnLoading = true
        var page = this.$store.getters['uiadmin/Page'](
          'feeder-perkuliahan-trakm'
        )
        this.$ajax
					.post(
						'/feeder/perkuliahan/trakm/printtoexcel1',
						{
              prodi_id: page.prodi_id,
              nama_prodi: this.$store.getters['uiadmin/getProdiNameForFeeder'](
                page.prodi_id
              ),
              semester_akademik: this.semester_akademik,
              tahun_akademik: this.tahun_akademik,
              pid: 'fake',
            },
						{
							headers: {
								Authorization: this.$store.getters['auth/Token'],
							},							
						}
					)
					.then(() => {						
						this.btnLoading = false;
					})
					.catch(() => {
						this.btnLoading = false;
					})
				await this.$ajax
					.post(
						'/feeder/perkuliahan/trakm/printtoexcel1',
						{
              prodi_id: page.prodi_id,
              nama_prodi: this.$store.getters['uiadmin/getProdiNameForFeeder'](
                page.prodi_id
              ),
              semester_akademik: this.semester_akademik,
              tahun_akademik: this.tahun_akademik,
              pid: 'real',
            },
						{
							headers: {
								Authorization: this.$store.getters['auth/Token'],
							},
							responseType: 'arraybuffer',
						}
					)
					.then(({ data, status }) => {
						if (status == 200) {
							const url = window.URL.createObjectURL(new Blob([data]))
							const link = document.createElement('a')
							link.href = url;
							link.setAttribute('download', 'daftar_trakm_' + Date.now() + '.xlsx')
							link.setAttribute('id', 'download_laporan')
							document.body.appendChild(link)
							link.click()
							document.body.removeChild(link)
						}
						this.btnLoading = false;
					})
					.catch(() => {
						this.btnLoading = false;
					})
			},
    },
    components: {
      FeederLayout,
      Filter6,
    },
  }
</script>
