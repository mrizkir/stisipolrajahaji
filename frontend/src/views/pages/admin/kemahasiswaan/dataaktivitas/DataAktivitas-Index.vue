<template>
  <KemahasiswaanLayout :showsubheader="true">
    <template v-slot:page-header>Data Aktivitas</template>
    <template v-slot:page-sub-header>
      Program Studi <strong>{{ nama_prodi }}</strong> T.A
      <strong>{{ tahun_akademik }}</strong>
      Semester
      <strong>
        {{ $store.getters['uiadmin/getNamaSemester'](semester_akademik) }}
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
              <b-card-body>
                <div class="input-group input-group-sm">
                  <b-form-input class="float-right" placeholder="Cari" v-model="search" />
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-default" @click.stop="handleSearch" :disabled="btnLoading">
                      <b-icon icon="search" />
                    </button>
                  </div>
                </div>
              </b-card-body>
              <b-card-body class="p-0">
                <b-table
                  id="datatable"
                  primary-key="id"
                  :fields="fields"
                  :items="datatable"
                  :sort-by.sync="sortBy"
                  :sort-desc.sync="sortDesc"
                  :current-page="currentPage"
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
                  <template #cell(tanggal_sk_tugas)="{ item }">
                    {{ $date(item.tanggal_sk_tugas).format("DD.MM.YYYY") }}
                  </template>
                  <template #cell(jenis_anggota)="{ item }">
                    {{ item.jenis_anggota == 1 ? 'Personal' : 'Kelompok' }}
                  </template>
                  <template #cell(aksi)="{ item }">
                    <b-button
                      :id="'btDetail' + item.id" variant="outline-primary p-1 mr-1"
                      size="xs"
                      :to="url_da + '/' + item.id + '/detail'"
                      :disabled="btnLoading"
                      v-if="$store.getters['auth/can']('KEMAHASISWAAN-AKTIVITAS_SHOW')"
                    >
                      <b-icon icon="eye" class="p-0 m-0"></b-icon>
                    </b-button>
                    <b-button
                      :id="'btEdit' + item.id" variant="outline-primary p-1 mr-1"
                      size="xs"
                      :to="url_da + '/' + item.id + '/edit'"
                      :disabled="btnLoading"
                      v-if="$store.getters['auth/can']('KEMAHASISWAAN-AKTIVITAS_UPDATE')"
                    >
                      <b-icon icon="pencil-square" class="p-0 m-0"></b-icon>
                    </b-button>
                    <b-tooltip :target="'btEdit' + item.id" variant="primary">Ubah Data Aktivitas</b-tooltip>
                    <b-button
                      :id="'btDelete' + item.id"
                      variant="outline-danger p-1"
                      size="xs"
                      @click.stop="showModalDelete(item)"
                      :disabled="btnLoading"
                      v-if="$store.getters['auth/can']('KEMAHASISWAAN-AKTIVITAS_DESTROY')"
                    >
                      <b-icon icon="trash" class="p-0 m-0"></b-icon>
                    </b-button>
                    <b-tooltip :target="'btDelete' + item.id" variant="danger">Hapus Data Aktivitas</b-tooltip>
                  </template>
                  <template #emptytext>
                    tidak ada data yang bisa ditampilkan
                  </template>
                </b-table>
              </b-card-body>
              <template #footer>
                Total {{ totalRows }} data
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
        <b-modal
          id="modal-delete"
          header-bg-variant="danger"
          centered
          @hidden="resetModal"
          @ok="handleDelete"
        >
          <template #modal-title>Hapus Data</template>
          <div class="d-block">
            Data aktivitas dengan judul "{{ dataItem.judul_aktivitas }}" akan dihapus ?
          </div>
        </b-modal>
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
        url_da: '/kemahasiswaan/dataaktivitas',
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
        indexTab: 0,
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
          label: 'No.',
          key: 'no',
          thStyle: 'width: 50px',
        },
        {
          key: 'nama_aktivitas',
          label: 'Jenis Kegiatan',
        },
        {
          key: 'judul_aktivitas',
          label: 'Judul',
        },
        {
          key: 'no_sk_tugas',
          label: 'Nomor SK',
        },
        {
          key: 'tanggal_sk_tugas',
          label: 'Tanggal SK',
        },
        
        {
          key: 'jenis_anggota',
          label: 'Jenis Anggota',
        },
        {
          label: 'Aksi',
          key: 'aksi',
          thStyle: 'width: 150px',
        },
      ],
      sortBy: 'judul_aktivitas',
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
        page.sortBy = 'judul_aktivitas'
        page.sortDesc = false
        page.search = null
        page.prodi_id = this.$store.getters['uiadmin/getProdiID']
        page.tahun_akademik = this.$store.getters['uiadmin/getTahunAkademik']
        page.semester_akademik =
          this.$store.getters['uiadmin/getSemesterAkademik']
        this.$store.dispatch('uiadmin/updatePage', page)
        page.indexTab = 0
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
      async initialize() {
        this.datatableLoading = true
        var page = this.$store.getters['uiadmin/Page']('dataaktivitas')
        var url_da = this.url_da + '?page=' + page.currentPage + '&sortby=' + page.sortBy + '&sortdesc=' + page.sortDesc

        if (page.loaded && page.search != null) {
          this.search = page.search
          url_da = page.search.length > 0 ? url_da + '&search=' + page.search : url_da
        }
        
        await this.$ajax.get(url_da, {
          headers: {
            Authorization: this.$store.getters['auth/Token'],
          },
        })
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
      addAktivitas() {
        if (this.prodi_id > 0) {
          this.$router.push(this.url_da + '/create')
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
      showModalDelete(item) {
        this.dataItem = item
        this.$bvModal.show('modal-delete')
      },
      resetModal() {
        this.dataItem = {}
      },
      handleDelete(event) {
        event.preventDefault()
        this.btnLoading = true
        this.$ajax
          .post(
            this.url_da + '/' + this.dataItem.id,
            {
              _method: 'DELETE',
            },
            {
              headers: {
                Authorization: this.$store.getters['auth/Token'],
              },
            }
          )
          .then(() => {
            this.initialize()
            this.btnLoading = false
          })
          .catch(() => {
            this.btnLoading = false
          })
        // Hide the modal manually
        this.$nextTick(() => {
          this.dataItem = {}
          this.$bvModal.hide('modal-delete')
        })
      },
    },
    components: {
      KemahasiswaanLayout,
      Filter6,
    },
  }
</script>
