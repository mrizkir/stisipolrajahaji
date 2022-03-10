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
    mounted() {
      this.$store.dispatch('uifront/init', this.$ajax)
    },
    components: {
      navbar
    },
  }
</script>
