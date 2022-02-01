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
                Daftar Permission
              </template>
              <b-card-text class="p-0">
                <b-table
                  id="datatable"
                  :fields="fields"
                  :items="datatable"
                >
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
    </template>
  </PenggunaSistemLayout>
</template>
<script>
  import PenggunaSistemLayout from '@/views/layouts/PenggunaSistemLayout'
  export default {
    name: 'PenggunaSistem',
    created() {
      this.initialize(this.currentPage)
    },
    data: () => ({
      //setting table
      currentPage: 1,
      perPage: 10,
      totalRows: 0,
      datatable: [],
      fields: [
        {
          key: 'name',
          label: 'Nama Permission',
        },        
        {
          key: 'guard_name',
          label: 'Guard',
        },        
        {
          key: 'actions',
          label: 'Aksi',
        },        
      ],
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
          console.log(data)
          this.totalRows = data.permissions.total
          this.datatable = data.permissions.data
          this.datatableLoading = false
        })             
      },
      handlePageChange(value) {
        this.currentPage = value
        this.initialize(this.currentPage)
      },
    },
    components: {
			PenggunaSistemLayout,
		},
  }
</script>
