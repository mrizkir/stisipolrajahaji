<template>
  <AkademikLayout>
    <template v-slot:page-header>
      Akademik
    </template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item active>Akademik</b-breadcrumb-item>      
    </template>
    <template v-slot:page-content>
      page-content
    </template>
  </AkademikLayout>
</template>
<script>
  import AkademikLayout from '@/views/layouts/AkademikLayout'
  export default {
    name: 'Akademik',
    components: {
			AkademikLayout,
		},    
  }
</script>