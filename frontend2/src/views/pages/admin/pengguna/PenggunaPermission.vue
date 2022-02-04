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
            <b-card>
              <template #header>
                <h3 class="card-title">Daftar Permission</h3>
                <div class="card-tools">
                  <div class="input-group input-group-sm" style="width: 250;">
                    <b-form-input class="float-right" placeholder="Cari" />                    
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default">
                        <b-icon icon="search" />
                      </button>
                    </div>
                  </div>
                </div>
              </template>
              <b-card-text class="p-0">
                <b-table
                  id="datatable"
                  :fields="fields"
                  :items="datatable"
                  :sort-by.sync="sortBy"
                  :sort-desc.sync="sortDesc"
                  striped
                  hover
                  show-empty
                >
                  <template #cell(aksi)="{ item }">                    
                    <b-button variant="outline-danger p-1" size="xs" @click.stop="showModalDelete(item)" :disabled="btnLoading">
                      <b-icon icon="trash" class="p-0 m-0"></b-icon>
                    </b-button>
                  </template>
                  <template #emptytext>
                    tidak ada data yang bisa ditampilkan
                  </template>
                </b-table>
              </b-card-text>
              <template #footer>
                <b-pagination
                  v-model="currentPage"
                  :total-rows="totalRows"
                  :per-page="perPage"
                  aria-controls="datatable"
                  class="pagination-sm m-0 float-right"
                  primary-key="id"
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
      this.$store.dispatch("uiadmin/addToPages", {
				name: "permission",				
			});
      this.initialize(this.currentPage)
    },
    data: () => ({
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
      
      dataItem: {},
    }),
    methods: {
      initialize(currentPage) {
        this.datatableLoading = true
        this.$ajax.get('/system/setting/permissions?page=' + currentPage, {
          headers: {
            Authorization: 'Bearer ' + this.$store.getters['auth/AccessToken'],
          }
        })
        .then(({ data }) => {
          this.totalRows = data.permissions.total
          this.datatable = data.permissions.data
          this.datatableLoading = false
        })             
      },
      handlePageChange(value) {
        this.currentPage = value
        this.initialize(this.currentPage)
      },
      showModalDelete(item) {
        this.dataItem = item;
        this.$bvModal.show('modal-delete')
      },
      resetModal() {
        this.dataItem = {} 
      },
      handleDelete(event) {
        event.preventDefault();
        this.btnLoading = true;
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
            this.initialize();
            this.btnLoading = false;
          })
          .catch(() => {
            this.btnLoading = false;
          });
          
        // Hide the modal manually
        this.$nextTick(() => {
          this.dataItem = {}
          this.$bvModal.hide('modal-delete')
        })
      },
    },
    components: {
			PenggunaSistemLayout,
		},
  }
</script>
