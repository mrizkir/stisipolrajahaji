<template>
  <b-card
    no-body
    class="card-primary card-outline"
  >
    <template #header>
      <h3 class="card-title">Data Hak ({{ selectedPermissions.length }}) </h3>
      <div class="card-tools" v-if="$store.getters['auth/can']('USER_STOREPERMISSIONS')">
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
          class="mr-1"
        >
          Kosongkan
        </b-btn>
        <b-btn
          size="xs"
          variant="outline-primary"          
          @click="setAllSelected"
          v-b-tooltip.hover
          title="Pilih semua permission"          
        >
          Pilih Semua
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
        Silahkan pilih permission untuk user {{data_user.nama}} dengan cara mengklik baris dalam tabel di bawah ini.
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
        <template v-slot:cell(selected)="{ item, field: { key } }">
          <b-form-checkbox
            v-model="item[key]"
            v-if="$store.getters['auth/can']('USER_STOREPERMISSIONS') && item.selected2 === null"
            switch
          />
          <span v-else>
            N.A
          </span>
        </template>
        <template #cell(aksi)="{ item }">
          <b-button
            :id="'btDelete' + item.userid"
            variant="outline-danger p-1"
            size="xs"
            @click.stop="revokePermission(item)"
            :disabled="btnLoading"
            v-if="$store.getters['auth/can']('USER_REVOKEPERMISSIONS') && item.selected == 'true'"
          >
            <b-icon icon="trash" class="p-0 m-0"></b-icon>
            <b-tooltip
              :target="'btDelete' + item.userid"
              variant="danger"
              v-if="$store.getters['auth/can']('USER_REVOKEPERMISSIONS') && item.selected == 'true'"
            >
              Hapus Permission Pengguna
            </b-tooltip>
          </b-button>
          <span v-else>
            N.A
          </span>  
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
    },
    mounted() {
      this.initialize()
    },
    data: () => ({
      datatableLoading: false,
      btnLoading: false,
      data_user: {},
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
        {
          label: 'Aksi',
          key: 'aksi',
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
      setAllSelected() {
        this.datatable.forEach((item) => {
          if (item.selected2 == null) {
            this.$set(item, 'selected', true)
          }
        })
      },
      clearSelected() {
        this.selectedPermissions.forEach((item) => {
          if (item.selected2 == null) {
            this.$delete(item, 'selected')
          }
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
          if (item.selected2 == null) {   
            if (item.selected) {
              this.$set(item, 'selected', false)
            } else {
              this.$set(item, 'selected', true)
            }
          }
        }
      },
      onFiltered(filteredItems) {  
        this.totalRows = filteredItems.length
        this.currentPage = 1
      },
      async initialize() {
        this.datatableLoading = true        
        var url = '/system/users/' + this.user_id + '/rolepermission'

        //load data role beserta permissions-nya
        await this.$ajax.get(url, {
          headers: {
            Authorization: this.$store.getters['auth/Token'],
          }
        })
        .then(({ data }) => {
          this.data_user = data.user
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
              "/system/users/storeuserpermissions",
              {
                userid: this.user_id,
                chkpermission: this.selectedPermissions
              },
              {
                headers: {
                  Authorization: this.$store.getters['auth/Token'],
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
          this.$bvToast.toast('Simpan permission dari user ' + this.data_user.nama + ' gagal karena jumlah permissionnya 0' , {
            title: 'Pesan Sistem',
            variant: 'warning',
            autoHideDelay: 5000,
            appendToast: false
          })
        }
      },
      async revokePermission(item) {
        this.btnLoading = true
        await this.$ajax
          .post('/system/users/revokeuserpermissions',
            {
              user_id: this.user_id,
              name: item.name,
            },
            {
              headers: {
                Authorization: this.$store.getters['auth/Token'],
              }
            }
        )
        .then(() => {
          this.$router.go()
        })
        .catch(() => {
          this.btnLoading = false
        })
      },
    },
    computed: {
      selectedPermissions() {
        return this.datatable.filter(item => item.selected)
      }
    },
  }
</script>