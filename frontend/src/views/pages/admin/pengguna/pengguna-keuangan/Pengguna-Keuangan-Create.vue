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
      <b-breadcrumb-item active>Tambah</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      <b-container
        fluid
        v-if="$store.getters['auth/can']('SYSTEM-USERS-KEUANGAN_STORE')"
      >
        <b-row>
          <b-col>
            <CreateForm
              urlfront="/sistem-pengguna/keuangan"
              urlbackend="/system/userskeuangan/store"
            />
          </b-col>
        </b-row>
      </b-container>
    </template>
  </PenggunaSistemLayout>
</template>
<script>
  import PenggunaSistemLayout from '@/views/layouts/PenggunaSistemLayout'
  import CreateForm from '@/components/pengguna/user-create'
  export default {
    name: 'PenggunaKeuanganCreate',
    components: {
      PenggunaSistemLayout,
      CreateForm,
    },
  }
</script>
