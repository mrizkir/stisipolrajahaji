<template>
	<div>
		<v-app-bar app elevation="0">
			<v-app-bar-nav-icon
				@click.stop="drawer = !drawer"				
			>
			</v-app-bar-nav-icon>
		</v-app-bar>
		<v-navigation-drawer
			v-model="drawer"
			width="300"
			dark			
			:temporary="temporaryleftsidebar"
			app
		>
			
		</v-navigation-drawer>
		<v-main class="mx-4 mb-4">
			<slot />
		</v-main>
	</div>
</template>
<script>
	export default {
		name: "AkademikLayout",
		props: {
			showrightsidebar: {
				type: Boolean,
				default: true,
			},
			temporaryleftsidebar: {
				type: Boolean,
				default: false,
			},
		},
		data: () => ({
			loginTime: 0,
			drawer: null,
			drawerRight: null,
			dashboard: null,
		}),
	}
</script>
