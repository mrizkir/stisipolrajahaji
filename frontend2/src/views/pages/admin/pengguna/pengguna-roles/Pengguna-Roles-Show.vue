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
                      <dt class="col-sm-4">Nama Role</dt>
                      <dd class="col-sm-8">{{data_role.name}}</dd>
                    </dl>
                  </b-col>
                </b-row>                
                <b-row>
                  <b-col>
                    <dl class="row">
                      <dt class="col-sm-3">Guard</dt>
                      <dd class="col-sm-9">{{data_role.guard_name}}</dd>
                    </dl>
                  </b-col>
                  <b-col>
                    <dl class="row">
                      <dt class="col-sm-4">Created/Updated</dt>
                      <dd class="col-sm-8">{{data_role.created_at}} / {{data_role.updated_at}}</dd>
                    </dl>
                  </b-col>
                </b-row>                
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
          this.datatable = data.role.permissions;
          this.datatableLoading = false
        })             
      },
    },
    components: {
			PenggunaSistemLayout,
		},
  }
</script>
