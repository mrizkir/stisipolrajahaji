<template>
	<AkademikLayout>
		<ModuleHeader>
			<template v-slot:icon>
				{{ icons.mdiBook }}
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
								<v-dialog v-model="dialogfrm" max-width="750px" persistent>
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
									<v-form ref="frmdata" v-model="form_valid" lazy-validation>
										<v-card>
											<v-card-title>
												<span class="headline">{{ formTitle }}</span>
											</v-card-title>
											<v-card-text>
												<v-text-field
													v-model="formdata.nama_aktivitas"
													label="NAMA JENIS AKTIVITAS"
													outlined
													:rules="rule_nama_aktivitas"
												>
												</v-text-field>
											</v-card-text>
											<v-card-actions>
												<v-spacer></v-spacer>
												<v-btn
													color="blue darken-1"
													text
													@click.stop="closedialogfrm"
												>
													TUTUP
												</v-btn>
												<v-btn
													color="blue darken-1"
													text
													@click.stop="save"
													:disabled="!form_valid || btnLoading"
												>
													SIMPAN
												</v-btn>
											</v-card-actions>
										</v-card>
									</v-form>
								</v-dialog>
								<v-dialog
									v-model="dialogdetailitem"
									max-width="750px"
									persistent
								>
									<v-card>
										<v-card-title>
											<span class="headline">DETAIL DATA</span>
										</v-card-title>
										<v-card-text>
											<v-row no-gutters>
												<v-col xs="12" sm="6" md="6">
													<v-card flat>
														<v-card-title>ID :</v-card-title>
														<v-card-subtitle>
															{{ formdata.idjenis }}
														</v-card-subtitle>
													</v-card>
												</v-col>
												<v-responsive
													width="100%"
													v-if="$vuetify.breakpoint.xsOnly"
												/>
												<v-col xs="12" sm="6" md="6">
													<v-card flat>
														<v-card-title>CREATED :</v-card-title>
														<v-card-subtitle>
															{{
																$date(formdata.created_at).format(
																	"DD/MM/YYYY HH:mm"
																)
															}}
														</v-card-subtitle>
													</v-card>
												</v-col>
												<v-responsive
													width="100%"
													v-if="$vuetify.breakpoint.xsOnly"
												/>
											</v-row>
											<v-row no-gutters>
												<v-col xs="12" sm="6" md="6">
													<v-card flat>
														<v-card-title>NAMA AKTIVITAS :</v-card-title>
														<v-card-subtitle>
															{{ formdata.nama_aktivitas }}
														</v-card-subtitle>
													</v-card>
												</v-col>
												<v-responsive
													width="100%"
													v-if="$vuetify.breakpoint.xsOnly"
												/>
												<v-col xs="12" sm="6" md="6">
													<v-card flat>
														<v-card-title>UPDATED :</v-card-title>
														<v-card-subtitle>
															{{
																$date(formdata.updated_at).format(
																	"DD/MM/YYYY HH:mm"
																)
															}}
														</v-card-subtitle>
													</v-card>
												</v-col>
												<v-responsive
													width="100%"
													v-if="$vuetify.breakpoint.xsOnly"
												/>
											</v-row>
										</v-card-text>
										<v-card-actions>
											<v-spacer></v-spacer>
											<v-btn
												color="blue darken-1"
												text
												@click.stop="closedialogdetailitem"
											>
												TUTUP
											</v-btn>
										</v-card-actions>
									</v-card>
								</v-dialog>
							</v-toolbar>
						</template>
						<template v-slot:item.actions="{ item }">
							<v-tooltip bottom>
								<template v-slot:activator="{ on, attrs }">
									<v-icon
										v-bind="attrs"
										v-on="on"
										small
										class="mr-2"
										@click.stop="viewItem(item)"
									>
										{{icons.mdiEye}}
									</v-icon>
								</template>
								<span>Detail Tooltip</span>
							</v-tooltip>
							<v-tooltip bottom>
								<template v-slot:activator="{ on, attrs }">
									<v-icon
										v-bind="attrs"
										v-on="on"
										small
										class="mr-2"
										@click.stop="editItem(item)"										
									>
										{{ icons.mdiPencil }}
									</v-icon>
								</template>
								<span>Ubah Jenis Aktivitas</span>
							</v-tooltip>
							<v-tooltip bottom>
								<template v-slot:activator="{ on, attrs }">
									<v-icon
										v-bind="attrs"
										v-on="on"
										small
										color="red darken-1"
										:disabled="btnLoading"
										@click.stop="deleteItem(item)"
									>
										{{icons.mdiDelete}}
									</v-icon>
								</template>
								<span>Hapus Jenis Aktivitas</span>
							</v-tooltip>
						</template>
						<template v-slot:expanded-item="{ headers, item }">
							<td :colspan="headers.length" class="text-center">
								<v-col cols="12">
									<strong>ID:</strong>{{ item.idjenis }}
									<strong>CREATED AT:</strong>
									{{ $date(item.created_at).format("DD/MM/YYYY HH:mm") }}
									<strong>UPDATED AT:</strong>
									{{ $date(item.updated_at).format("DD/MM/YYYY HH:mm") }}
								</v-col>
							</td>
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
	import { mdiChevronRight, mdiPlus, mdiEye, mdiDelete, mdiPencil, mdiBook } from "@mdi/js";
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
					mdiPlus,
					mdiDelete,
					mdiEye,
					mdiPencil,
					mdiBook
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
				{ text: "NAMA AKTIVITAS", value: "nama_aktivitas" },
				{ text: "AKSI", value: "actions", sortable: false, width: 100 },
			],

			//dialog
			dialogfrm: false,
			dialogdetailitem: false,

			//form data
			form_valid: true,
			formdata: {
				idjenis: null,
				nama_aktivitas: null,
				created_at: null,
				updated_at: null,
			},
			formdefault: {
				idjenis: null,
				nama_aktivitas: null,
				created_at: null,
				updated_at: null,
			},
			editedIndex: -1,

			//form rules			
			rule_nama_aktivitas: [
				value => !!value || "Mohon untuk di isi nama jenis aktivitas !!!",
				value =>
					/^[A-Za-z\s]*$/.test(value) || "Nama jenis aktivitas hanya boleh string dan spasi",
			],
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
			save: async function() {
				if (this.$refs.frmdata.validate()) {
					this.btnLoading = true;
					if (this.editedIndex > -1) {
						await this.$ajax
							.post(
								"/akademik/perkuliahan/jenisaktivitas/" + this.formdata.idjenis,
								{
									_method: "PUT",
									nama_aktivitas: this.formdata.nama_aktivitas,
								},
								{
									headers: {
										Authorization: this.$store.getters["auth/Token"],
									},
								}
							)
							.then(({ data }) => {
								Object.assign(this.datatable[this.editedIndex], data.jenisaktivitas);
								this.closedialogfrm();
								this.btnLoading = false;
							})
							.catch(() => {
								this.btnLoading = false;
							});
					} else {
						await this.$ajax
							.post(
								"/akademik/perkuliahan/jenisaktivitas/store",
								{
									nama_aktivitas: this.formdata.nama_aktivitas,
								},
								{
									headers: {
										Authorization: this.$store.getters["auth/Token"],
									},
								}
							)
							.then(({ data }) => {
								this.datatable.push(data.jenisaktivitas);
								this.closedialogfrm();
								this.btnLoading = false;
							})
							.catch(() => {
								this.btnLoading = false;
							});
					}
				}
			},
			viewItem(item) {
				this.formdata = item;
				this.dialogdetailitem = true;				
			},
			editItem(item) {
				this.editedIndex = this.datatable.indexOf(item);
				this.formdata = Object.assign({}, item);
				this.dialogfrm = true;
			},
			deleteItem(item) {
				this.$root.$confirm
					.open(
						"Delete",
						"Apakah Anda ingin menghapus data dengan ID " + item.idjenis + " ?",
						{ color: "red" }
					)
					.then(confirm => {
						if (confirm) {
							this.btnLoading = true;
							this.$ajax
								.post(
									"/akademik/perkuliahan/jenisaktivitas/" + item.idjenis,
									{
										_method: "DELETE",
									},
									{
										headers: {
											Authorization: this.$store.getters["auth/Token"],
										},
									}
								)
								.then(() => {
									const index = this.datatable.indexOf(item);
									this.datatable.splice(index, 1);
									this.btnLoading = false;
								})
								.catch(() => {
									this.btnLoading = false;
								});
						}
					});
			},
			closedialogdetailitem() {
				this.dialogdetailitem = false;
				setTimeout(() => {
					this.formdata = Object.assign({}, this.formdefault);
					this.editedIndex = -1;
				}, 300);
			},
			closedialogfrm() {
				this.dialogfrm = false;
				setTimeout(() => {
					this.formdata = Object.assign({}, this.formdefault);
					this.editedIndex = -1;
					this.$refs.frmdata.reset();
				}, 300);
			},
		},
		computed: {
			formTitle() {
				return this.editedIndex === -1 ? "TAMBAH JENIS AKTIVITAS" : "UBAH JENIS AKTIVITAS";
			},
		},
		components: {
			AkademikLayout,
			ModuleHeader,			
		},
	};
</script>
