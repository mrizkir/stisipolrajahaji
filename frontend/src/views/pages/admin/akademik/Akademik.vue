<template>  
  <AkademikLayout>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item active>Pengguna Sistem</b-breadcrumb-item>      
    </template>
    <template v-slot:page-content>
      page-content
    </template>
  </AkademikLayout>
</template>
<script>
  import AkademikLayout from '@/views/layouts/AkademikLayout'
  export default {
    name: 'AkademikLayout',
    components: {
			AkademikLayout,
		},    
  }
</script>
