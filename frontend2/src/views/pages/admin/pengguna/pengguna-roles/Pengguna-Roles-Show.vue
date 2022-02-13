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
  </PenggunaSistemLayout>
</template>
<script>
  import PenggunaSistemLayout from '@/views/layouts/PenggunaSistemLayout'  
  export default {
    name: 'PenggunaRolesShow',
    components: {
			PenggunaSistemLayout,
		},
  }
</script>
