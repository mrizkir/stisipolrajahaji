<template>
  <DMasterLayout>
    <template v-slot:page-header>
      Kategori Kegiatan Dosen
    </template>
    <template v-slot:page-breadcrumb>      
      <b-breadcrumb-item to="/dmaster">Data Master</b-breadcrumb-item>
      <b-breadcrumb-item active>Kategori Kegiatan Dosen</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      <b-container fluid v-if="$store.getters['auth/can']('DMASTER-DOSEN-KATEGORI-KEGIATAN_BROWSE')">
        <b-row>
          <b-col>
            <b-card no-body class="card-primary card-outline">
              <template #header>
                <h3 class="card-title">Daftar Kategori Kegiatan</h3>
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
                    v-if="$store.getters['auth/can']('DMASTER-DOSEN-KATEGORI-KEGIATAN_STORE')"
                    v-b-tooltip.hover
                    title="Tambah Kategori Kegiatan"
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
                  primary-key="idkategori"
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
                  <template #cell(active)="{ item }">
                    <b-badge :variant="item.active == 1 ? 'primary' : 'secondary'">{{ item.active == 1 ? 'aktif' : 'tidak aktif' }}</b-badge>
                  </template>
                  <template #cell(aksi)="{ item, index }">
                    <b-button
                      :id="'btEdit' + index" variant="outline-primary p-1 mr-1"
                      size="xs"
                      :to="url + '/' + item.idkategori + '/edit'"
                      :disabled="btnLoading"
                      v-if="$store.getters['auth/can']('DMASTER-DOSEN-KATEGORI-KEGIATAN_UPDATE')"
                    >
                      <b-icon icon="pencil-square" class="p-0 m-0"></b-icon>          
                      <b-tooltip :target="'btEdit' + index" variant="primary" placement="rightbottom">Ubah Kategori Kegiatan</b-tooltip>
                    </b-button>        
                    <b-button
                      :id="'btDelete' + index"
                      variant="outline-danger p-1"
                      size="xs"
                      @click.stop="showModalDelete(item)"
                      :disabled="btnLoading"
                      v-if="$store.getters['auth/can']('DMASTER-DOSEN-KATEGORI-KEGIATAN_DESTROY')"
                    >
                      <b-icon icon="trash" class="p-0 m-0"></b-icon>
                      <b-tooltip :target="'btDelete' + index" variant="danger" placement="rightbottom">Hapus Kategori Kegiatan</b-tooltip>
                    </b-button>
                  </template>
                  <template #emptytext>
                    tidak ada data yang bisa ditampilkan
                  </template>
                </b-table>
              </b-card-body>
              <template #footer>
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
          <template #modal-title>
            Hapus Data
          </template>
          <div class="d-block">
            Nama kategori kegiatan dosen "{{dataItem.nama_kategori}}" akan dihapus ?
          </div>
        </b-modal>
      </b-container>
    </template>
  </DMasterLayout>
</template>
<script>
  import DMasterLayout from '@/views/layouts/DMasterLayout'
  export default {
    name: 'KategoriKegiatanDosenIndex',
    created() {
      this.$store.dispatch('uiadmin/addToPages', {
      	name: 'kategorikegiatan',
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
        url: '/dmaster/dosen/kategorikegiatan',
      }
    },
    mounted() {
      this.initialize()
    },
    data: () => ({
      datatableLoading: false,
      btnLoading: false,
      
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
          key: 'kode_kategori',
          label: 'Kode',
        },
        {
          key: 'nama_kategori',
          label: 'Nama',
        },
        {
          label: 'Aksi',
          key: 'aksi',
          thStyle: 'width: 150px',
        },
      ],
      sortBy: 'nama_kategori',
      sortDesc: false,
      search: null,
      dataItem: {},
    }),
    methods: {
       updatesettingpage() {
        var page = this.$store.getters['uiadmin/Page']('kategorikegiatan')
        page.perPage = this.perPage
        page.currentPage = this.currentPage
        page.sortBy = this.sortBy
        page.sortDesc = this.sortDesc
        page.search = this.search
        this.$store.dispatch('uiadmin/updatePage', page)
      },
      clearsettingpage() {
        var page = this.$store.getters['uiadmin/Page']('kategorikegiatan')
        page.loaded = false
        page.perPage = 10
        page.currentPage = 1
        page.sortBy = 'nama_kategori'
        page.sortDesc = false
        page.search = null
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
      async initialize() {
        this.datatableLoading = true
        var page = this.$store.getters['uiadmin/Page']('kategorikegiatan')
        var url = '/dmaster/dosen/kategorikegiatan?page=' + page.currentPage + '&sortby=' + page.sortBy + '&sortdesc=' + page.sortDesc

        if (page.loaded && page.search != null) {
          this.search = page.search
          url = page.search.length > 0 ? url + '&search=' + page.search : url
        }
        await this.$ajax
          .get(url, {
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
            '/dmaster/dosen/kategorikegiatan/' + this.dataItem.idkategori,
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
      DMasterLayout,
		},
  }
</script>