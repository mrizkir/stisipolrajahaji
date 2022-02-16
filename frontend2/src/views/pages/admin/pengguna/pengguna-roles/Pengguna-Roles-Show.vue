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
              </template>
              <b-card-body class="p-0">
                <b-alert class="m-3 font-italic" show>
                  Silahkan pilih permission untuk role {{data_role.name}} dengan cara mengklik baris dalam tabel di bawah ini.
                </b-alert>
                <b-table
                  ref="datatable"
                  primary-key="id"
                  :fields="fields"
                  :items="datatable"
                  :sort-by.sync="sortBy"
                  :sort-desc.sync="sortDesc"
                  :busy="datatableLoading"
                  @row-selected="onRowSelected"
                  select-mode="multi"
                  outlined                  
                  hover
                  show-empty
                  responsive
                  selectable
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

      //setting table
      datatable: [],
      role_permission: [],

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
      sortBy: 'name',
      sortDesc: false,
    }),
    methods: {
      async initialize() {
        this.datatableLoading = true
        var url = '/system/setting/roles/' + this.role_id
        
        await this.$ajax.get(url, {
          headers: {
            Authorization: 'Bearer ' + this.$store.getters['auth/AccessToken'],
          }
        })
        .then(({ data }) => {          
          this.data_role = data.role
          this.role_permssion = data.role.permissions;
          this.datatableLoading = false
        })

        this.$ajax
					.get("/system/setting/permissions/all", {
						headers: {
							Authorization: 'Bearer ' + this.$store.getters['auth/AccessToken'],
						},
					})
					.then(({ data, status }) => {
						if (status == 200) {
							this.datatable = data.permissions
						}
					})
				// this.$ajax
				// 	.get("/system/setting/roles/" + item.id + "/permission", {
				// 		headers: {
				// 			Authorization: this.TOKEN,
				// 		},
				// 	})
				// 	.then(({ data, status }) => {
				// 		if (status == 200) {
				// 			this.permissions_selected = data.permissions;
				// 		}
				// 	});
        
      },
      onRowSelected(items) {
        this.role_permission = items
      },
    },
    components: {
			PenggunaSistemLayout,
		},
  }
</script>
