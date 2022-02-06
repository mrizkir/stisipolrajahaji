<template>  
  <PenggunaSistemLayout>
    <template v-slot:page-header>
      Permission
    </template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item to="/sistem-pengguna">Pengguna Sistem</b-breadcrumb-item>      
      <b-breadcrumb-item active>Permission</b-breadcrumb-item>
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
                <h3 class="card-title">Daftar Permission</h3>
                <div class="card-tools">
                  <b-button size="xs" variant="outline-primary">
                    <b-icon icon="plus-circle" />
                  </b-button>                  
                </div>
              </template>
              <b-card-body class="p-0">
                <div class="input-group input-group-sm" style="width: 250">
                  <b-form-input class="float-right" placeholder="Cari" v-model="search" />                    
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-default" @click.stop="initialize()" :disabled="btnLoading">
                      <b-icon icon="search" />
                    </button>
                  </div>
                </div>
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
                >
                  <template #table-busy>
                    <div class="text-center text-danger my-2">
                      <b-spinner class="align-middle"></b-spinner>
                      <strong>Loading...</strong>
                    </div>
                  </template>
                  <template #cell(aksi)="{ item }">                    
                    <b-button variant="outline-danger p-1" size="xs" @click.stop="showModalDelete(item)" :disabled="btnLoading">
                      <b-icon icon="trash" class="p-0 m-0"></b-icon>
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
                ></b-pagination>
              </template>
            </b-card>
          </b-col>
        </b-row>
      </b-container>
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
          Nama permission "{{dataItem.name}}" akan dihapus ?
        </div>
      </b-modal>
    </template>
  </PenggunaSistemLayout>
</template>
<script>
  import PenggunaSistemLayout from '@/views/layouts/PenggunaSistemLayout'
  export default {
    name: 'PenggunaSistem',
    created() {
      this.$store.dispatch('uiadmin/addToPages', {
				name: 'permission',
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
          key: 'name',
          label: 'Nama Permission',
          sortable: true,
        },        
        {
          key: 'guard_name',
          label: 'Guard',
        },
        'aksi',        
      ],
      sortBy: 'name',
      sortDesc: false,
      search: null,
      dataItem: {},
    }),
    methods: {
      updatepage() {
        var page = this.$store.getters['uiadmin/Page']('permission')
        page.perPage = this.perPage
        page.currentPage = this.currentPage
        page.sortBy = this.sortBy
        page.sortDesc = this.sortDesc
        page.search = this.search
        this.$store.dispatch('uiadmin/updatePage', page)
      },
      async initialize() {
        this.datatableLoading = true
        var page = this.$store.getters['uiadmin/Page']('permission')
        var url = '/system/setting/permissions?page=' + page.currentPage + '&sortby=' + page.sortBy + '&sortdesc=' + page.sortDesc

        if (page.loaded && page.search != null) {
          url = page.search.length > 0 ? url + '&search=' + page.search : url
        }
        
        await this.$ajax.get(url, {
          headers: {
            Authorization: 'Bearer ' + this.$store.getters['auth/AccessToken'],
          }
        })
        .then(({ data }) => {
          this.totalRows = data.permissions.total
          this.datatable = data.permissions.data
          page.loaded = true
          this.$store.dispatch('uiadmin/updatePage', page)
          this.$nextTick(() => {            
            this.currentPage = page.currentPage        
          });
          this.datatableLoading = false
        })             
      },
      handlePageChange(value) {
        this.currentPage = value
        this.updatepage()
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
        this.$ajax.post(
          '/system/setting/permissions/' + this.dataItem.id,
            {
              _method: 'DELETE',
            },
            {
              headers: {
                Authorization: 'Bearer ' + this.$store.getters['auth/AccessToken'],
              }
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
    watch: {
      sortDesc() {
        this.updatepage()
        this.initialize()        
      }
    },
    components: {
			PenggunaSistemLayout,
		},
  }
</script>
