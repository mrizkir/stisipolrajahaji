<template>
  <div>
    <navbar />
    <b-container fluid>
      <slot />
    </b-container>
  </div>
</template>
<script>
  import navbar from '@/components/panels/navbaradmin.vue'
  export default {
    name: 'Dashboard',
    created() {
			this.token = this.$route.params.token;
			this.initialize();			
		},
    data: () => ({
			token: null,
		}),
    methods: {
			initialize() {
				this.$ajax
					.get("/auth/me", {
						headers: {
							Authorization: "Bearer " + this.token,
						},
					})
					.then(({ data }) => {						
						this.dashboard = data.role;
						// this.$store.dispatch("uiadmin/changeDashboard", this.dashboard);
					})
					.catch(error => {
						if (error.response.status == 401) {
              this.$router.push('/login');
						}
					});
				// this.$store.dispatch("uiadmin/init", this.$ajax);
			},
    },
    components: {
      navbar
    },
  }
</script>