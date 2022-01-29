<template>
  <b-navbar toggleable="lg" type="dark" variant="info">
    <b-navbar-brand to="#">PortalEkampusV2</b-navbar-brand>
    <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

    <b-collapse id="nav-collapse" is-nav>
      <b-navbar-nav>
        
      </b-navbar-nav>

      <!-- geser ke kanan -->
      <b-navbar-nav class="ml-auto">
         <b-navbar-nav>
           <b-nav-item @click="logout">Logout</b-nav-item>
         </b-navbar-nav>
      </b-navbar-nav>
    </b-collapse>
  </b-navbar>
</template>
<script>
  export default {
    name: 'navbaradmin',		
    methods: {
      logout() {
				this.$ajax
					.post(
						"/auth/logout",
						{},
						{
							headers: {
								Authorization: this.$store.getters["auth/Token"],
							},
						}
					)
					.then(() => {
						this.$store.dispatch("auth/logout")
						this.$store.dispatch("uifront/reinit")
						// this.$store.dispatch("uiadmin/reinit")
						this.$router.push("/")
					})
					.catch(() => {
						this.$store.dispatch("auth/logout")
						this.$store.dispatch("uifront/reinit")
						// this.$store.dispatch("uiadmin/reinit")
						this.$router.push("/")
					})
      },
    },
  }
</script>