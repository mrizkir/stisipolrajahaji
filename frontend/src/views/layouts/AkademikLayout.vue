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
			<v-divider></v-divider>
			<v-subheader
				style="color:#f0935c"				
			>
				PERKULIAHAN
			</v-subheader>
			<v-list-group
				group="/akademik/perkuliahan/aktivitasmahasiswa"
				no-action
			>
				<template v-slot:activator>
					<v-list-item-icon class="mr-2">
						<v-icon>mdi-chart-timeline</v-icon>
					</v-list-item-icon>
					<v-list-item-content>
						<v-list-item-title>AKTIVITAS MAHASISWA</v-list-item-title>
					</v-list-item-content>
				</template>
				<div>
					<v-list-item
						link						
						to="/akademik/perkuliahan/aktivitasmahasiswa/jenisaktivitas"
					>
						<v-list-item-icon class="mr-2">
							<v-icon>mdi-arrow-right-bold-hexagon-outline</v-icon>
						</v-list-item-icon>
						<v-list-item-content>
							<v-list-item-title>
								JENIS AKTIVITAS
							</v-list-item-title>
						</v-list-item-content>
					</v-list-item>
				</div>
			</v-list-group>
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
