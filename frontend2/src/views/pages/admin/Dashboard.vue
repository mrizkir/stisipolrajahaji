<template>
  <div>
    <navbar />
    <b-container fluid>
      <b-row class="mt-2">
				<b-col md="3" sm="6" xs="12">
					<b-card
						bg-variant="dark"
						text-variant="white"
						title="Pengguna Sistem"
						v-if="$store.getters['auth/can']('SYSTEM-USERS-GROUP')"
					>
						<b-card-text>
							Mengatur roles, permission, dan pengguna
						</b-card-text>
						<b-button variant="primary" to="/sistem-pengguna">GO</b-button>
					</b-card>
				</b-col>
			</b-row>
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
					.get('/auth/me', {
						headers: {
							Authorization: 'Bearer ' + this.token,
						},
					})
					.then(({ data }) => {						
						this.dashboard = data.role;
						// this.$store.dispatch('uiadmin/changeDashboard', this.dashboard);
					})
					.catch(error => {
						if (error.response.status == 401) {
              this.$router.push('/login');
						}
					});
				// this.$store.dispatch('uiadmin/init', this.$ajax);
			},
    },
    components: {
      navbar
    },
  }
</script>
<style lang="scss" scoped>
@import 'src/scss/dashboard-menu.scss';
</style>