<template>
  <PenggunaSistemLayout>
    <template v-slot:page-header>Pengguna Keuangan</template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item to="/sistem-pengguna">
        Pengguna Sistem
      </b-breadcrumb-item>
      <b-breadcrumb-item to="/sistem-pengguna/keuangan">
        Pengguna Keuangan
      </b-breadcrumb-item>
      <b-breadcrumb-item active>Detail</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      <b-container
        fluid
        v-if="$store.getters['auth/can']('SYSTEM-USERS-KEUANGAN_SHOW')"
      >
        <b-row>
          <b-col>
            <DetailUser
              :data_user="data_user"
              urlfront="/sistem-pengguna/keuangan"
            />
          </b-col>
        </b-row>
        <b-row>
          <b-col>
            <UserPermissions
              :user_id="user_id"
              urlfront="/sistem-pengguna/keuangan"
              v-if="data_user.hasOwnProperty('userid')"
            />
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
    name: 'PenggunaKeuanganDetail',
    created() {
      this.user_id = this.$route.params.user_id
      this.initialize()
    },
    data: () => ({
      user_id: null,
      data_user: {},
    }),
    methods: {
      async initialize() {
        var url = '/system/userskeuangan/' + this.user_id
        await this.$ajax
          .get(url, {
            headers: {
              Authorization: this.$store.getters['auth/Token'],
            },
          })
          .then(({ data }) => {
            this.data_user = data.user
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
