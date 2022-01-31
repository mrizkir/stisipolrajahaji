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
        page-content
      </b-container>
    </template>
  </PenggunaSistemLayout>
</template>
<script>
  import PenggunaSistemLayout from '@/views/layouts/PenggunaSistemLayout'
  export default {
    name: 'PenggunaSistem',
    components: {
			PenggunaSistemLayout,
		},    
  }
</script>
