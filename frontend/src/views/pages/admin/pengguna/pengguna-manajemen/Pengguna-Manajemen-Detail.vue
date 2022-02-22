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
      <b-container fluid v-if="$store.getters['auth/can']('SYSTEM-USERS-AKADEMIK_SHOW')">
        <b-row>
          <b-col>
            <DetailUser :data_user="data_user" urlfront="/sistem-pengguna/manajemen" />
          </b-col>
        </b-row>
        <b-row>
          <b-col>
            <UserPermissions :user_id="user_id" :role_id="role_id" urlfront="/sistem-pengguna/manajemen" v-if="role_id" />
          </b-col>
        </b-row>
      </b-container>
    </template>
  </PenggunaSistemLayout>
</template>
<script>
  import PenggunaSistemLayout from '@/views/layouts/PenggunaSistemLayout'  
  import DetailUser from '@/components/pengguna/user-detail'
  import UserPermissions from '@/components/pengguna/user-permissions'
  export default {
    name: 'PenggunaManajemenDetail',
    created() {
      this.user_id =this.$route.params.user_id
      this.initialize()
    },
    data: () => ({
      user_id: null,
      role_id: null,
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
          this.data_user = data.user
          this.role_id = data.role_id
        })
      },
    },
    components: {
			PenggunaSistemLayout,
      DetailUser,
      UserPermissions,
		},
  }
</script>


