<template>
  <b-card
    no-body
    class="card-primary card-outline"
  >
    <template #header>
      <h3 class="card-title">Data Hak Akses</h3>
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
      
    </b-card-body>
  </b-card>  
</template>
<script>
  export default {
    name: 'user-permissions',
    props: {
      urlfront: {
        type: String,
        default: null,
      }, 
      urlbackend: {
        type: String,
        default: null,
      },
      user_id: {        
        required: true,
      },
      role_id: {        
        required: true,
      },
    },
    mounted() {
      this.initialize()
    },
    data: () => ({
      datatableLoading: false,
      btnLoading: false,

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
        // await this.$ajax
        // .get('/system/setting/roles/' + this.role_id + '/allpermissions', {
        //   headers: {
        //     Authorization: 'Bearer ' + this.$store.getters['auth/AccessToken'],
        //   },
        // })
        // .then(({ data }) => {        
        //   this.datatable = data.permissions
        //   this.totalRows = this.datatable.length
        //   this.datatableLoading = false          
        // })
        // .catch(() => {
        //   this.datatableLoading = false
        // })                
      },
    },
    computed: {
      selectedPermissions() {
        return this.datatable.filter(item => item.selected)
      }
    },
  }
</script>