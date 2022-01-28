<template>
  <div>
    <navbar />
    <router-view />
  </div>
</template>
<script>
  import navbar from '@/components/panels/navbar.vue'
  export default {
    name: "PortalEkampus2",
    created() {
      this.$store.dispatch("uifront/init", this.$ajax);
    },
    components: {
      navbar
    }
  }
</script>

