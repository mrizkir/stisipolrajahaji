<template>
  <PenggunaSistemLayout>
    <template v-slot:page-header>
      Pengguna Manajemen
    </template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item to="/sistem-pengguna">Pengguna Sistem</b-breadcrumb-item>      
      <b-breadcrumb-item to="/sistem-pengguna/manajemen">Pengguna Manajemen</b-breadcrumb-item>      
      <b-breadcrumb-item active>Detail</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      <b-container fluid v-if="$store.getters['auth/can']('SYSTEM-USERS-AKADEMIK_UPDATE')">
        <b-col>
          
        </b-col>
      </b-container>
    </template>
  </PenggunaSistemLayout>
</template>
<script>
  import PenggunaSistemLayout from '@/views/layouts/PenggunaSistemLayout'  
  export default {
    name: 'PenggunaManajemenCreate',
    created() {
      this.user_id =this.$route.params.user_id
      this.initialize()
    },
    data: () => ({
      user_id: null,
      data_user: {},
    }),
    methods: {      
      async initialize() {
        var url = '/system/usersmanajemen/' + this.user_id;
        await this.$ajax.get(url, {
          headers: {
            Authorization: 'Bearer ' + this.$store.getters['auth/AccessToken'],
          }
        })
        .then(({ data }) => {
          this.formdata = data.user
        })
      },
    },
    components: {
			PenggunaSistemLayout,      
		},
  }
</script>


