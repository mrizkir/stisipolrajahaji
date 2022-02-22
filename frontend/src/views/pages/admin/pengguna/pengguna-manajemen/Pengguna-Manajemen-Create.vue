<template>
  <PenggunaSistemLayout>
    <template v-slot:page-header>
      Pengguna Manajemen
    </template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item to="/sistem-pengguna">Pengguna Sistem</b-breadcrumb-item>      
      <b-breadcrumb-item to="/sistem-pengguna/manajemen">Pengguna Manajemen</b-breadcrumb-item>      
      <b-breadcrumb-item active>Tambah</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      <b-container fluid v-if="$store.getters['auth/can']('SYSTEM-USERS-AKADEMIK_UPDATE')">
        <b-row>
          <b-col>
            <CreateForm urlfront="/sistem-pengguna/manajemen" urlbackend="/system/usersmanajemen/store" />
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
    name: 'PenggunaManajemenCreate',   
    components: {
			PenggunaSistemLayout,
      CreateForm,
		},
  }
</script>


