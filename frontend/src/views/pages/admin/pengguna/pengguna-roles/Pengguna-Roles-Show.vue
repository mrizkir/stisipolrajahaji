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
      <b-container fluid v-if="$store.getters['auth/can']('SYSTEM-SETTING-ROLES_SHOW')">
        <b-row>
          <b-col>
            <b-card
              no-body
              class="card-primary card-outline"
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
              class="card-primary card-outline"
            >
              <template #header>
                <h3 class="card-title">Daftar Permission ({{ selectedPermissions.length }})</h3>  
                <div class="card-tools">
                  <b-button
                    size="xs"
                    variant="outline-primary"
                    :disabled="selectedPermissions.length === 0 || btnLoading"
                    @click.stop="save"                    
                    v-b-tooltip.hover
                    title="Simpan Permission"
                    class="mr-1"
                  >
                    save
                  </b-button>
                  <b-btn
                    size="xs"
                    variant="outline-primary"
                    :disabled="selectedPermissions.length === 0"
                    @click="clearSelected"
                    v-b-tooltip.hover
                    title="Kosongkan pilihan permission"
                  >
                    Kosongkan
                  </b-btn>               
                </div>
              </template>
              <b-card-body>
                <b-form-group
                  label="Filter"
                  label-for="filter-input"
                  label-cols-sm="2"
                  label-align-sm="right"
                  label-size="sm"
                  class="mb-0"
                >
                  <b-input-group>
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
                  primary-key="id"
                  :items="datatable"
                  :fields="fields"
                  :per-page="perPage"
                  :current-page="currentPage"
                  @row-clicked="rowClicked"
                  :tbody-tr-class="tbodyRowClass"
                  :busy="datatableLoading"
                  :filter="filter"
                  :filter-included-fields="filterOn"
                  @filtered="onFiltered"
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
                  <template v-slot:cell(selected)="{ item, field: { key } }" >
                    <b-checkbox v-model="item[key]"></b-checkbox>
                  </template>
                </b-table>              
              </b-card-body>
              <template #footer>
                <b-pagination
                  aria-controls="datatable"
                  :per-page="perPage"
                  v-model="currentPage"
                  :total-rows="totalRows"
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
    created() {
      this.role_id =this.$route.params.role_id
      this.initialize()
    },    
    data: () => ({
      role_id: null,
      datatableLoading: false,
      btnLoading: false,

      data_role : {},

      //setting table 
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
          key: 'selected',
          label: 'Pilihan',          
          thStyle: 'width: 100px',
        },
      ],
      totalRows: 1,  
      perPage: 10,
      currentPage: 1,
      sortBy: 'name',
      sortDesc: false,
      datatable: [],
      filter: null,
      filterOn: [],
    }),
    methods: {
      clearSelected() {
        this.selectedPermissions.forEach((item) => {
          this.$delete(item, 'selected')
        })
      },
      tbodyRowClass(item) {
        if (typeof item !== 'undefined' && item !== null) {
          if (item.selected) {
            return ['b-table-row-selected', 'table-primary', 'cursor-pointer']
          } else {
            return ['cursor-pointer']
          }
        }
      },
      rowClicked(item) {
        if (typeof item !== 'undefined' && item !== null) {
          if (item.selected) {      
            this.$set(item, 'selected', false)
          } else {
            this.$set(item, 'selected', true)
          }
        }
      },
      onFiltered(filteredItems) {        
        this.totalRows = filteredItems.length
        this.currentPage = 1
      },
      async initialize() {
        this.datatableLoading = true
        var url = '/system/setting/roles/' + this.role_id

        //load data role beserta permissions-nya
        await this.$ajax.get(url, {
          headers: {
            Authorization: 'Bearer ' + this.$store.getters['auth/AccessToken'],
          }
        })
        .then(({ data }) => {          
          this.data_role = data.role
        })
        
        //load data permissions secara keseluruhan
        await this.$ajax
        .get('/system/setting/roles/' + this.role_id + '/allpermissions', {
          headers: {
            Authorization: 'Bearer ' + this.$store.getters['auth/AccessToken'],
          },
        })
        .then(({ data }) => {        
          this.datatable = data.permissions
          this.totalRows = this.datatable.length
          this.datatableLoading = false          
        })
        .catch(() => {
          this.datatableLoading = false
        })
      },
      async save() {        
        if (this.selectedPermissions.length > 0) {
          this.btnLoading = true
          await this.$ajax
            .post(
              "/system/setting/roles/storerolepermissions",
              {
                role_id: this.data_role.id,
                chkpermission: this.selectedPermissions
              },
              {
                headers: {
                  Authorization: 'Bearer ' + this.$store.getters['auth/AccessToken'],
                }
              }
            )
            .then(() => {
              this.btnLoading = false
              this.$router.go()
            })
            .catch(() => {
              this.btnLoading = false
            })
        } else {
          this.$bvToast.toast('Simpan permission dari role ' + this.data_role.name + ' gagal karena jumlah permissionnya 0' , {
            title: 'Pesan Sistem',
            variant: 'warning',
            autoHideDelay: 5000,
            appendToast: false
          })
        }        
      },
    },
    computed: {
      selectedPermissions() {
        return this.datatable.filter(item => item.selected)
      }
    },
    components: {
			PenggunaSistemLayout,
		},
  }
</script>