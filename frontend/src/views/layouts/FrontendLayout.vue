<template>
  <div>
    <navbar />
    <b-container fluid>
      <slot />
    </b-container>
  </div>
</template>
<script>
  import navbar from '@/components/panels/navbarfront.vue'
  export default {
    name: 'FrontendLayout',
    components: {
      navbar
    },
  }
</script>
