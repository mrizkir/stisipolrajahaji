<template>
	<SystemUserLayout>
		<ModuleHeader>
			<template v-slot:icon>
				{{ icons.mdiAccount }}
			</template>
			<template v-slot:name>USER AKADEMIK</template>
			<template v-slot:breadcrumbs>
				<v-breadcrumbs :items="breadcrumbs" class="pa-0">
					<template v-slot:divider>
						<v-icon>{{icons.mdiChevronRight}}</v-icon>
					</template>
				</v-breadcrumbs>
			</template>
			<template v-slot:desc>
				<v-alert color="cyan" border="left" colored-border type="info">
					Halaman untuk mengelola pengguna dengan role akademik
				</v-alert>
			</template>
		</ModuleHeader>
		<v-container fluid>
			<v-row class="mb-4" no-gutters>
				<v-col cols="12">
					<v-card>
						<v-card-text>
							<v-text-field
								v-model="search"
								append-icon="mdi-database-search"
								label="Search"
								single-line
								hide-details
							>
							</v-text-field>
						</v-card-text>
					</v-card>
				</v-col>
			</v-row>
			<v-row class="mb-4" no-gutters>
				<v-col cols="12">
					<v-data-table
						:headers="headers"
						:items="datatable"
						:options.sync="datatableOptions"
						:server-items-length="totalUser"
						class="elevation-1"
						loading="datatableLoading"
					>

					</v-data-table>
				</v-col>
			</v-row>
		</v-container>	
	</SystemUserLayout>
</template>
<script>
	import { mdiChevronRight, mdiChevronLeft, mdiPlus, mdiEye, mdiDelete, mdiPencil, mdiAccount, } from "@mdi/js";
	import SystemUserLayout from "@/views/layouts/SystemUserLayout";
	import ModuleHeader from "@/components/ModuleHeader";
	export default {
		name: "UsersAkademik",
		setup() {
			return {
				icons: {
					mdiChevronRight,
					mdiChevronLeft,
					mdiPlus,
					mdiDelete,
					mdiEye,
					mdiPencil,
					mdiAccount,
				}
			};
		},
		created() {
			this.breadcrumbs = [
				{
					text: "HOME",
					disabled: false,
					href: "/dashboard/" + this.$store.getters["auth/AccessToken"],
				},
				{
					text: 'USER SISTEM',
					disabled: false,
					href: '/system-users',
				},
				{
					text: 'USER AKADEMIK',
					disabled: true,
					href: "#",
				}
			];
			this.initialize();
		},
		data: () => ({
			breadcrumbs: null,
			btnLoading: false,
			datatableLoading: false,
			expanded: [],
			datatable: [],
			datatableOptions: {},
			totalUser: 0,
			headers: [				
				{ text: 'USERNAME', value: 'username', sortable: true },				
				{ text: 'EMAIL', value: 'email', sortable: true },				
				{ text: "AKSI", value: "actions", sortable: false, width: 100 },
			],
			search: null,
		}),
		methods: {
			async initialize() {
				const { page } = this.datatableOptions;
				var currentpage = typeof page === "undefined" ? 1 : page;				
				this.datatableLoading = true;				
				await this.$ajax
					.get("/system/usersakademik?page=" + currentpage, {
						headers: {
							Authorization: this.$store.getters["auth/Token"],
						},
					})
					.then(({ data }) => {
						setTimeout(() => {
							this.datatable = data.result.data;
							this.totalUser = data.result.total;
							this.role_id= data.role.id;
							this.datatableLoading = false;
						}, 1000);
					})
					.catch(() => {
						this.datatableLoading = false;
					});
			},
			dataTableRowClicked(item) {
				if (item === this.expanded[0]) {
					this.expanded = [];
				} else {
					this.expanded = [item];
				}
			},
		},
		watch: {
			datatableOptions: {
				handler() {
					this.initialize();
				},
				deep: true,
			},
		},
		components: {
			SystemUserLayout,
			ModuleHeader
		},
	};
</script>
