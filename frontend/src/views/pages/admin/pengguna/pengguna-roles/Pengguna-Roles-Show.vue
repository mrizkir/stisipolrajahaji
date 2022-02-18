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
                <h3 class="card-title">Daftar Permission ({{ selectedPermissions.length }})</h3>  
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
              <b-card-body class="p-0">
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
                  :total-rows="datatable.length"
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
        .get('/system/setting/permissions/all', {
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
      }
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