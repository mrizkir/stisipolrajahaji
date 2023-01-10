<template>
  <SPMBLayout>
    <template v-slot:page-header>
      SPMB
    </template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item active>SPMB</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      page-content
    </template>
  </SPMBLayout>
</template>
<script>
  import SPMBLayout from '@/views/layouts/SPMBLayout'
  export default {
    name: 'SPMB',
    components: {
      SPMBLayout,
		},
  }
</script>