<template>
  <PenggunaSistemLayout>
    <template v-slot:page-header>
      Roles
    </template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item to="/sistem-pengguna">Pengguna Sistem</b-breadcrumb-item>      
      <b-breadcrumb-item to="/sistem-pengguna/roles">Roles</b-breadcrumb-item>      
      <b-breadcrumb-item active>Detail</b-breadcrumb-item>
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
                <h3 class="card-title">Data Role</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" v-b-tooltip.hover title="Keluar" @click.stop="$router.push('/sistem-pengguna/roles')">
                    <b-icon icon="x-square"></b-icon>
                  </button>
                </div>               
              </template>
              <b-card-body>
                <b-row>
                  <b-col>
                    <dl class="row">
                      <dt class="col-sm-3">ID</dt>
                      <dd class="col-sm-9">{{data_role.id}}</dd>
                    </dl>
                  </b-col>                
                  <b-col>
                    <dl class="row">
                      <dt class="col-sm-4">Guard</dt>
                      <dd class="col-sm-8">{{data_role.guard_name}}</dd>
                    </dl>
                  </b-col>
                </b-row>                
                <b-row>
                  <b-col>
                    <dl class="row">
                      <dt class="col-sm-3">Nama Role</dt>
                      <dd class="col-sm-9">{{data_role.name}}</dd>
                    </dl>
                  </b-col>                  
                  <b-col>
                    <dl class="row">
                      <dt class="col-sm-4">Created/Updated</dt>
                      <dd class="col-sm-8">
                        {{$date(data_role.created_at).format("DD.MM.YYYY HH:mm")}} / {{$date(data_role.updated_at).format('DD.MM.YYYY HH:mm')}}</dd>
                    </dl>
                  </b-col>
                </b-row>                
              </b-card-body>
            </b-card>
          </b-col>
        </b-row>
        <b-row>
          <b-col>
            <b-card
              no-body
              class="card card-primary card-outline"
            >
              <template #header>
                <h3 class="card-title">Daftar Permission</h3>  
                <div class="card-tools">
                  <b-button
                    size="xs"
                    variant="outline-primary"
                    @click.stop="save"                    
                    v-b-tooltip.hover
                    title="Simpan Permission"
                    class="mr-1"
                  >
                    save
                  </b-button>
                </div>
              </template>
              <b-card-body>
                <b-form-group
                  label="Filter"
                  label-for="filter-input"
                  label-cols-sm="3"
                  label-align-sm="right"
                  label-size="sm"
                  class="mb-0"
                >
                  <b-input-group size="sm">
                    <b-form-input
                      id="filter-input"
                      v-model="filter"
                      type="search"
                      placeholder="Type to Search"
                    ></b-form-input>

                    <b-input-group-append>
                      <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
                    </b-input-group-append>
                  </b-input-group>
                </b-form-group>
              </b-card-body>
              <b-card-body class="p-0">
                <b-alert class="m-3 font-italic" show>
                  Silahkan pilih permission untuk role {{data_role.name}} dengan cara mengklik baris dalam tabel di bawah ini.
                </b-alert>                
                <b-table
                  id="datatable"
                  ref="datatable"
                  primary-key="id"
                  :fields="fields"
                  :items="datatable"
                  :per-page="perPage"
                  :current-page="currentPage"
                  @row-selected="onRowSelected"                  
                  select-mode="multi"
                  :sort-by.sync="sortBy"
                  :sort-desc.sync="sortDesc"
                  :busy="datatableLoading"
                  :filter="filter"
                  :filter-included-fields="filterOn"
                  @filtered="onFiltered"
                  selectable
                  outlined                  
                  hover
                  show-empty
                  responsive
                >
                  <template #table-busy>
                    <div class="text-center text-danger my-2">
                      <b-spinner class="align-middle"></b-spinner>
                      <strong>Loading...</strong>
                    </div>
                  </template>
                  <template #cell(pilihan)="{ rowSelected }">
                    <template v-if="rowSelected">
                      <span aria-hidden="true">&check;</span>
                      <span class="sr-only">Selected</span>
                    </template>
                    <template v-else>
                      <span aria-hidden="true">&nbsp;</span>
                      <span class="sr-only">Not selected</span>
                    </template>
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
                  @change="handlePageChange"
                  aria-controls="datatable"
                  class="pagination-sm m-0 float-right"  
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
    name: 'PenggunaRolesShow',
    created() {
      this.role_id =this.$route.params.role_id;
      this.initialize();
    },
    data: () => ({
      role_id: null,
      datatableLoading: false,
      btnLoading: false,
      
      data_role : {},

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
        {
          label: 'Pilihan',
          key: 'pilihan',
          thStyle: 'width: 100px',
        },
      ],

      //setting table  
      totalRows: 1,  
      perPage: 10,
      currentPage: 1,
      sortBy: 'name',
      sortDesc: false,
      datatable: [],
      filter: null,
      filterOn: [],
      role_permission: [],
    }),
    methods: {
      async initialize() {
        this.datatableLoading = true
        var url = '/system/setting/roles/' + this.role_id
        var page = this.$store.getters['uiadmin/Page']('role')

        //load data role beserta permissions-nya
        await this.$ajax.get(url, {
          headers: {
            Authorization: 'Bearer ' + this.$store.getters['auth/AccessToken'],
          }
        })
        .then(({ data }) => {          
          this.data_role = data.role
          this.role_permission = data.permissions
                    
          page.loaded = true
          page.role_permission = this.role_permission
          this.$store.dispatch('uiadmin/updatePage', page)
        })

        //load data permissions secara keseluruhan
        await this.$ajax
        .get("/system/setting/permissions/all", {
          headers: {
            Authorization: 'Bearer ' + this.$store.getters['auth/AccessToken'],
          },
        })
        .then(({ data, status }) => {
          if (status == 200) {
            this.datatable = data.permissions
            this.totalRows = this.datatable.length
            this.datatableLoading = false
          }
        })
        .catch(() => {
          this.datatableLoading = false
        })
      },
      onRowSelected(items) {
        if (items.length > 0) {
          var page = this.$store.getters['uiadmin/Page']('role')
          // var role_permissions = page.role_permission
          this.role_permission = items
          page.role_permission = this.role_permission
          // console.log('onRowSelected', items)
          this.$store.dispatch('uiadmin/updatePage', page)
        }
      },
      onFiltered(filteredItems) {        
        this.totalRows = filteredItems.length
        this.currentPage = 1
      },
      handlePageChange() {
        var page = this.$store.getters['uiadmin/Page']('role')
        this.role_permission = page.role_permission
        console.log('handle page changed', this.role_permission)
      },
      save() {
        var page = this.$store.getters['uiadmin/Page']('role')
        if (typeof page.role_permission !== 'undefined') {
          if (page.role_permission.length > 0) {
            console.log(page)
          } else {
            this.$bvToast.toast('Simpan permission dari role ' + this.data_role.name + ' gagal karena jumlah permissionnya 0' , {
              title: 'Pesan Sistem',
              variant: 'warning',
              autoHideDelay: 5000,
              appendToast: false
            })
          }
        }
      },
    },
    components: {
			PenggunaSistemLayout,
		},
  }
</script>