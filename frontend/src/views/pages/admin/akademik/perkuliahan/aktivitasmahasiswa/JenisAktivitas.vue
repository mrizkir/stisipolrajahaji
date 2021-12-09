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
						<v-icon>mdi-chevron-right</v-icon>
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
						:search="search"
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

          </v-data-table>
        </v-col>
      </v-row>
    </v-container>
  </AkademikLayout>
</template>
<script>
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
		},
    data: () => ({
      breadcrumbs: null,
      btnLoading: false,
			datatableLoading: false,      
			expanded: [],
			datatable: [],
      headers: [],
    }),
    methods: {
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
