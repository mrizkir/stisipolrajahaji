<template>
  <b-navbar
		toggleable="lg"
		:class="classBNavbar"		
	>
    <b-navbar-toggle target="nav-left"></b-navbar-toggle>

    <b-collapse id="nav-left" is-nav>
      <b-navbar-nav>
        <slot name="sidebartoggle" />
      </b-navbar-nav>

      <!-- geser ke kanan -->
      <b-navbar-nav class="ml-auto">
				<b-nav-item-dropdown right>
					<template #button-content>
						<b-avatar :src="photoUser" size="sm"></b-avatar>
					</template>
					<b-dropdown-item @click.stop="logout">Logout</b-dropdown-item>
				</b-nav-item-dropdown>				
      </b-navbar-nav>
    </b-collapse>
  </b-navbar>
</template>
<script>
  export default {
    name: 'navbaradmin',
		props: {
			classBNavbar: {
				type: String,
				default: 'main-header navbar-white',
			}
		},
    methods: {			
      logout() {
				this.$ajax
					.post(
						'/auth/logout',
						{},
						{
							headers: {
								Authorization: this.$store.getters['auth/Token'],
							},
						}
					)
					.then(() => {
            this.$store.dispatch('auth/logout')
            this.$store.dispatch('uifront/reinit')
            this.$store.dispatch('uiadmin/reinit')
            this.$router.push('/')
					})
					.catch(() => {
            this.$store.dispatch('auth/logout')
            this.$store.dispatch('uifront/reinit')
            this.$store.dispatch('uiadmin/reinit')
            this.$router.push('/')
					})
      },
    },
		computed: {
			photoUser() {
				let img = this.$store.getters['auth/DetailUser']('foto')
				var photo
				if (img == "") {
					photo = this.$backend.storageURL + "/storage/images/users/no_photo.png"
				} else {
					photo = this.$backend.storageURL + "/" + img
				}
				return photo				
			},
		},
  }
</script>