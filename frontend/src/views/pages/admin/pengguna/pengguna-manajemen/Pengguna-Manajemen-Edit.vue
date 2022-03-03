<template>
  <PenggunaSistemLayout>
    <template v-slot:page-header>
      Pengguna Manajemen
    </template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item to="/sistem-pengguna">Pengguna Sistem</b-breadcrumb-item>
      <b-breadcrumb-item to="/sistem-pengguna/manajemen">Pengguna Manajemen</b-breadcrumb-item>
      <b-breadcrumb-item active>Ubah</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      <b-container fluid v-if="$store.getters['auth/can']('SYSTEM-USERS-AKADEMIK_UPDATE')">
        <b-row>
          <b-col>
            <EditForm urlfront="/sistem-pengguna/manajemen" :urlbackend="'/system/usersmanajemen/' + user_id" :datauser="formdata" />
          </b-col>
        </b-row>
      </b-container>
    </template>
  </PenggunaSistemLayout>
</template>
<script>
  import PenggunaSistemLayout from '@/views/layouts/PenggunaSistemLayout'
  import EditForm from '@/components/pengguna/user-edit'
  export default {
    name: 'PenggunaManajemenEdit',
    
    created() {
      this.user_id = this.$route.params.user_id
      this.initialize()
    },

    data: () => ({
      user_id: null,
      formdata: {},
    }),

    methods: {
      async initialize() {
        var url = '/system/usersmanajemen/' + this.user_id
        await this.$ajax.get(url, {
          headers: {
            Authorization: this.$store.getters['auth/Token'],
          }
        })
        .then(({ data }) => {
          this.formdata = data.user
        })
      },
    },
    components: {
      PenggunaSistemLayout,
      EditForm,
    },
  }
</script>
