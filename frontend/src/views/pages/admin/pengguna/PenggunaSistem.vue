<template>
  <PenggunaSistemLayout>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item active>Pengguna Sistem</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      page-content
    </template>
  </PenggunaSistemLayout>
</template>
<script>
  import PenggunaSistemLayout from '@/views/layouts/PenggunaSistemLayout'
  export default {
    name: 'PenggunaSistem',
    components: {
      PenggunaSistemLayout,
    }
  }
</script>
