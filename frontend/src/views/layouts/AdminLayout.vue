<template>
  <div>
    <b-navbar toggleable="lg" type="dark" variant="dark" class="mb-2">
      <b-navbar-brand href="#">Portal Ekampus v3.0</b-navbar-brand>
      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
      <b-collapse id="nav-collapse" is-nav>
        <!-- Right aligned nav items -->
        <b-navbar-nav class="ml-auto">
          <b-nav-item-dropdown right>
            <!-- Using 'button-content' slot -->
            <template #button-content>
              <em>User</em>
            </template>
            <b-dropdown-item href="#">Profile</b-dropdown-item>
            <b-dropdown-item href="#" @click.prevent="logout">Logout</b-dropdown-item>
          </b-nav-item-dropdown>
        </b-navbar-nav>
      </b-collapse>
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
