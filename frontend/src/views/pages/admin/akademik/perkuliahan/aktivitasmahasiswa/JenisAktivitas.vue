<template>
	<AkademikLayout>
		<ModuleHeader>
			<template v-slot:icon>
				mdi-book
			</template>
			<template v-slot:name>
				JENIS AKTIVITAS
			</template>
			<template v-slot:breadcrumbs>
				<v-breadcrumbs :items="breadcrumbs" class="pa-0">
					<template v-slot:divider>
						<v-icon>{{icons.mdiChevronRight}}</v-icon>
					</template>
				</v-breadcrumbs>
			</template>
			<template v-slot:desc>
				<v-alert color="cyan" border="left" colored-border type="info">
					Halaman untuk mengelola berbagai macam jenis aktivitas mahasiswa
				</v-alert>
			</template>
		</ModuleHeader>
		<v-container fluid>
			<v-row class="mb-4" no-gutters>
				<v-col cols="12">
					<v-data-table
						:headers="headers"
						:items="datatable"						
						item-key="id"
						show-expand
						:expanded.sync="expanded"
						:single-expand="true"
						:disable-pagination="true"
						:hide-default-footer="true"
						@click:row="dataTableRowClicked"
						class="elevation-1"
						:loading="datatableLoading"
						loading-text="Loading... Please wait"
					>
						<template v-slot:top>
							<v-toolbar flat>
								<v-toolbar-title>DAFTAR JENIS AKTIVITAS</v-toolbar-title>
								<v-divider class="mx-4" inset vertical />
								<v-spacer></v-spacer>
								<v-dialog v-model="dialogfrm" max-width="500px" persistent>
									<template v-slot:activator="{ on: dialog }">
										<v-tooltip bottom>
											<template v-slot:activator="{ on: tooltip }">
												<v-btn
													color="primary"
													icon
													outlined
													small
													class="ma-2"
													v-on="{ ...tooltip, ...dialog }"
													:disabled="false"
												>
													<v-icon>{{icons.mdiPlus}}</v-icon>
												</v-btn>
											</template>
											<span>Tambah Jenis Kegiatan</span>
										</v-tooltip>
									</template>
								</v-dialog>
							</v-toolbar>
						</template>
						<template v-slot:no-data>
							Data belum tersedia
						</template>
					</v-data-table>
				</v-col>
			</v-row>
		</v-container>
	</AkademikLayout>
</template>
<script>
	import { mdiChevronRight, mdiPlus } from "@mdi/js";
	import AkademikLayout from "@/views/layouts/AkademikLayout";
	import ModuleHeader from "@/components/ModuleHeader";
	export default {
		name: "JenisAktivitas",
		created() {
			this.breadcrumbs = [
				{
					text: "HOME",
					disabled: false,
					href: "/dashboard/" + this.$store.getters["auth/AccessToken"],
				},
				{
					text: "AKADEMIK",
					disabled: false,
					href: "/akademik",
				},
				{
					text: "PERKULIAHAN",
					disabled: false,
					href: "#",
				},
				{
					text: "AKTIVITAS MAHASISWA",
					disabled: false,
					href: "#",
				},
				{
					text: "JENIS AKTIVITAS",
					disabled: true,
					href: "#",
				},
			];
			this.initialize();
		},
		setup() {
			return {
				icons: {
					mdiChevronRight,
					mdiPlus					
				}
			};
		},
		data: () => ({
			breadcrumbs: null,
			btnLoading: false,
			datatableLoading: false,
			expanded: [],
			datatable: [],
			headers: [
				{ text: "ID", value: "idjenis", width: 100, sortable: false },
				{ text: "NAMA JENIS", value: "nama_jenis" },
				{ text: "AKSI", value: "actions", sortable: false, width: 100 },
			],

			//dialog
			dialogfrm: false,
			dialogdetailitem: false,
		}),
		methods: {
			initialize: async function() {
				this.datatableLoading = true;
				await this.$ajax
					.get("/akademik/perkuliahan/jenisaktivitas", {
						headers: {
							Authorization: this.$store.getters["auth/Token"],
						},
					})
					.then(({ data }) => {
						this.datatable = data.jenisaktivitas;
						this.datatableLoading = false;
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
		components: {
			AkademikLayout,
			ModuleHeader,			
		},
	};
</script>
