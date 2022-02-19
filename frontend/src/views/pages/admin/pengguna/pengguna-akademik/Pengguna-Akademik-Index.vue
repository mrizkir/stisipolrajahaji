<template>
  <PenggunaSistemLayout>
    <template v-slot:page-header>
      Pengguna Akademik
    </template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item to="/sistem-pengguna">Pengguna Sistem</b-breadcrumb-item>      
      <b-breadcrumb-item active>Pengguna Akademik</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      <b-container fluid>
        <b-row>
          <b-col>
            <b-card
              no-body
              class="card card-primary card-outline"
            >
              <template #header>
                <h3 class="card-title">Daftar Pengguna</h3>
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
                    @click.stop="$router.push('/sistem-pengguna/akademik/create')"
                    v-if="$store.getters['auth/can']('SYSTEM-USERS-AKADEMIK_STORE')"
                    v-b-tooltip.hover
                    title="Tambah Pengguna"
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
                >
                  <template #table-busy>
                    <div class="text-center text-danger my-2">
                      <b-spinner class="align-middle"></b-spinner>
                      <strong>Loading...</strong>
                    </div>
                  </template>
                  <template #cell(aksi)="{ item }">
                    <b-button :id="'btDelete' + item.id" variant="outline-danger p-1" size="xs" @click.stop="showModalDelete(item)" :disabled="btnLoading">
                      <b-icon icon="trash" class="p-0 m-0"></b-icon>
                    </b-button>
                    <b-tooltip :target="'btDelete' + item.id" variant="danger">Hapus Pengguna</b-tooltip>
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
      </b-container>
    </template>
  </PenggunaSistemLayout>
</template>
<script>
  import PenggunaSistemLayout from '@/views/layouts/PenggunaSistemLayout'  
  export default {
    name: 'PenggunaRolesIndex',
    created() {
      this.$store.dispatch('uiadmin/addToPages', {
				name: 'pengguna-akademik',
        loaded: false,
        perPage: this.perPage,
        currentPage: this.currentPage,
        sortBy: this.sortBy,
        sortDesc: this.sortDesc,
        search: this.search,
			})
    },
    mounted() {
      this.initialize()      
    },
    data: () => ({
      datatableLoading: false,
      btnLoading: false,
      
      //setting table
      currentPage: 1,
      perPage: 10,
      totalRows: 0,
      datatable: [],
      fields: [
        {
          key: 'username',
          label: 'Username',
          sortable: true,
          thStyle: 'width: 150px',
        },        
        {
          key: 'nama',
          label: 'Nama',
          thStyle: 'width: 250px',
        },
        {
          key: 'email',
          label: 'Email',
        },
        {
          key: 'active',
          label: 'Status',
        },
        {
          label: 'Aksi',
          key: 'aksi',
          thStyle: 'width: 100px',
        },
      ],
      sortBy: 'nama',
      sortDesc: false,
      search: null,
      dataItem: {},
    }),
    methods: {
      updatesettingpage() {
        var page = this.$store.getters['uiadmin/Page']('pengguna-akademik')
        page.perPage = this.perPage
        page.currentPage = this.currentPage
        page.sortBy = this.sortBy
        page.sortDesc = this.sortDesc
        page.search = this.search
        this.$store.dispatch('uiadmin/updatePage', page)
      },
       async initialize() {
        this.datatableLoading = true
        var page = this.$store.getters['uiadmin/Page']('pengguna-akademik')
        var url = '/system/usersakademik?page=' + page.currentPage + '&sortby=' + page.sortBy + '&sortdesc=' + page.sortDesc

        if (page.loaded && page.search != null) {
          this.search = page.search
          url = page.search.length > 0 ? url + '&search=' + page.search : url
        }
        
        await this.$ajax.get(url, {
          headers: {
            Authorization: 'Bearer ' + this.$store.getters['auth/AccessToken'],
          }
        })
        .then(({ data }) => {
          console.log(data);
          this.totalRows = data.result.total
          this.datatable = data.result.data
          page.loaded = true
          this.$store.dispatch('uiadmin/updatePage', page)
          this.$nextTick(() => {            
            this.currentPage = page.currentPage        
          });
          this.datatableLoading = false
        })             
      },
      handleSearch() {
        this.currentPage = 1
        this.updatesettingpage()
        this.initialize();
      },
      handlePageChange(value) {
        this.currentPage = value
        this.updatesettingpage()
        this.initialize()
      },
    },
    components: {
			PenggunaSistemLayout,
		},
  }
</script>


