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
    components: {
      navbar
    }
  }
</script>

