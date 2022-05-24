<template>
  <DMasterLayout>
    <template v-slot:page-header>
      Data Master
    </template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item active>Data Master</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      page-content
    </template>
  </DMasterLayout>
</template>
<script>
  import DMasterLayout from '@/views/layouts/DMasterLayout'
  export default {
    name: 'DMaster',
    components: {
      DMasterLayout,
		},
  }
</script>