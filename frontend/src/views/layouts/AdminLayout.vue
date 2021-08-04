<template>
  <div>
    <b-navbar toggleable="lg" type="dark" variant="dark" class="mb-2">
      <b-navbar-brand href="#">Portal Ekampus v3.0</b-navbar-brand>
    </b-navbar>
    <b-container fluid>
      <slot />
    </b-container>
  </div>
</template>
<script>
export default {
  name: "AdminLayout",
};
</script>
