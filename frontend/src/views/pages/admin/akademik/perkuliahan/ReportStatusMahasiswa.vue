<template>
  <AkademikLayout>
    <template v-slot:page-header>
      Laporan Status Mahasiswa
    </template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item to="/akademik">Akademik</b-breadcrumb-item>
      <b-breadcrumb-item active>Laporan Status Mahasiswa</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      <b-container fluid>
        <b-row>
          <b-col>
            <b-card no-body class="card-primary card-outline">
              <b-card-body class="p-0">
                <b-table
                  id="datatable"
                  primary-key="tahun_masuk"
                  :fields="fields"
                  :items="datatable"
                  :sort-by.sync="sortBy"
                  :sort-desc.sync="sortDesc" 
                  :current-page="currentPage"
                  :busy="datatableLoading"
                  outlined
                  striped
                  hover
                  show-empty
                  responsive
                  no-local-sorting
                  small
                >
                  <template #thead-top>
                    <b-tr>
                      <b-th>&nbsp;</b-th>
                      <b-th>&nbsp;</b-th>
                      <b-th colspan="2" class="text-center" variant="warning">AP</b-th>
                      <b-th colspan="2" class="text-center" variant="info">IP</b-th>
                      <b-th colspan="2" class="text-center" variant="success">SOS</b-th>                      
                    </b-tr>
                  </template>
                </b-table>
              </b-card-body>
            </b-card>
          </b-col>
        </b-row>
      </b-container>
    </template>
  </AkademikLayout>
</template>
<script>
  import AkademikLayout from '@/views/layouts/AkademikLayout'
  export default {
    name: 'ReportStatusMahasiswa',
    data: () => ({
      fields: [
        {
          label: 'No.',
          key: 'no',
          thStyle: 'width: 50px',
        },
        {
          label: 'Angkatan',
          key: 'tahun_masuk',
          thStyle: 'width: 50px',
        },
        {
          key: 'ap_ganjil',
          label: 'Ganjil',
        },
        {
          key: 'ap_genap',
          label: 'Genap',
        },       
        {
          key: 'ip_ganjil',
          label: 'Ganjil',
        },       
        {
          key: 'ip_genap',
          label: 'Genap',
        },       
        {
          key: 'sos_ganjil',
          label: 'Ganjil',
        },       
        {
          key: 'sos_genap',
          label: 'Genap',
        },       
      ],
    }),
    components: {
      AkademikLayout,
		},
  }
</script>