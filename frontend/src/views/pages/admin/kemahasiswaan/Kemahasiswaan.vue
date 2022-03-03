<template>
  <KemahasiswaanLayout>
    <template v-slot:page-header>
      Kemahasiswaan
    </template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item active>Kemahasiswaan</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      page-content
    </template>
  </KemahasiswaanLayout>
</template>
<script>
  import KemahasiswaanLayout from '@/views/layouts/KemahasiswaanLayout'
  export default {
    name: 'Kemahasiswaan',
    components: {
      KemahasiswaanLayout,
    },
  }
</script>