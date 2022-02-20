<template>  
  <PenggunaSistemLayout>
    <template v-slot:page-header>
      Roles
    </template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item to="/sistem-pengguna">Pengguna Sistem</b-breadcrumb-item>      
      <b-breadcrumb-item active>Roles</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      <b-container fluid v-if="$store.getters['auth/can']('SYSTEM-SETTING-ROLES_BROWSE')">
        <b-row>
          <b-col>
            <b-card
              no-body
              class="card-primary card-outline"
            >
              <template #header>
                <h3 class="card-title">Daftar Roles</h3>  
                <div class="card-tools">
                  <b-button
                    size="xs"
                    variant="outline-primary"
                    @click.stop="clearsettingpage"                    
                    v-b-tooltip.hover
                    title="Hapus Setting Halaman"
                  >
                    <b-icon icon="trash2" />
                  </b-button>
                </div>              
              </template>              
              <b-card-body class="p-0">                
                <b-table
                  id="datatable"
                  primary-key="id"
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
                >
                  <template #table-busy>
                    <div class="text-center text-danger my-2">
                      &nbsp;
                    </div>
                  </template>
                  <template #cell(No)="data">                    
                    {{ data.index + 1 }}
                  </template>
                  <template #cell(aksi)="{ item }">
                    <b-button
                      variant="outline-primary p-1"
                      size="xs"
                      :to="'/sistem-pengguna/roles/' + item.id + '/detail'"
                      v-b-tooltip.hover                      
                      v-if="$store.getters['auth/can']('SYSTEM-SETTING-ROLES_SHOW')"
                      title="detail permission dari role"
                    >
                      <b-icon icon="shield-lock" class="p-0 m-0"></b-icon>
                    </b-button>
                  </template>
                  <template #emptytext>
                    tidak ada data yang bisa ditampilkan
                  </template>
                </b-table>
              </b-card-body>              
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
				name: 'role',
        role_permission: [],
        loaded: false,        
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
      datatable: [],
      fields: [
        'No',
        {
          key: 'name',
          label: 'Nama Role',
          sortable: true,
        },        
        {
          key: 'guard_name',
          label: 'Guard',
          thStyle: 'width: 100px',
        },
        {
          key: 'jumlah_pengguna',
          label: 'Jumlah Pengguna',
          thStyle: 'width: 100px',
        },
        {
          key: 'jumlah_permission',
          label: 'Jumlah Permission',
          thStyle: 'width: 100px',
        },
        {
          label: 'Aksi',
          key: 'aksi',
          thStyle: 'width: 100px',
        },
      ],
      sortBy: 'name',
      sortDesc: false,
    }),    
    methods: {
      updatesettingpage() {
        var page = this.$store.getters['uiadmin/Page']('role')
        page.sortBy = this.sortBy
        page.sortDesc = this.sortDesc        
        this.$store.dispatch('uiadmin/updatePage', page)
      },
      clearsettingpage() {
        var page = this.$store.getters['uiadmin/Page']('role')
        page.role_permission = []
        page.loaded = false
        page.sortBy = 'name'
        page.sortDesc = false
        this.$store.dispatch('uiadmin/updatePage', page)

        this.$bvToast.toast('Setting halaman sudah kembali ke default, silahkan refresh', {
          title: 'Pesan Sistem',
          variant: 'info',
          autoHideDelay: 5000,
          appendToast: false
        })
      },
      async initialize() {
        this.datatableLoading = true
        var page = this.$store.getters['uiadmin/Page']('role')
        var url = '/system/setting/roles'
        
        await this.$ajax.get(url, {
          headers: {
            Authorization: 'Bearer ' + this.$store.getters['auth/AccessToken'],
          }
        })
        .then(({ data }) => {          
          this.datatable = data.roles
          page.loaded = true
          this.$store.dispatch('uiadmin/updatePage', page)
          this.datatableLoading = false
        })             
      },      
    },
    watch: {
      sortDesc() {
        this.updatesettingpage()
        this.initialize()        
      }
    },
    components: {
			PenggunaSistemLayout,
		},
  }
</script>
