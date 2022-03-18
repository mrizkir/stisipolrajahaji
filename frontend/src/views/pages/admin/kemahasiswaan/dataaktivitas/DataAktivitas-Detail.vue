<template>
  <KemahasiswaanLayout :showsubheader="true" :showrightsidebar="false">
    <template v-slot:page-header>Data Aktivitas</template>
    <template v-slot:page-sub-header>
      Program Studi <strong>{{ nama_prodi }}</strong> T.A <strong>{{ tahun_akademik }}</strong>
      Semester <strong>{{ $store.getters["uiadmin/getNamaSemester"](semester_akademik) }}</strong>
    </template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item to="/kemahasiswaan">Kemahasiswaan</b-breadcrumb-item>
      <b-breadcrumb-item to="/kemahasiswaan/dataaktivitas">Data Aktivitas</b-breadcrumb-item>
      <b-breadcrumb-item active>Detail</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      <b-row>
        <b-col>
          <b-card
            no-body
            class="card-primary card-outline"
          >
            <template #header>
              <h3 class="card-title">Data Aktivitas</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" v-b-tooltip.hover title="Ubah" @click.stop="$router.push('/kemahasiswaan/dataaktivitas/' + id + '/edit')">
                  <b-icon icon="pencil-square" class="p-0 m-0"></b-icon>
                </button>
                <button type="button" class="btn btn-tool" v-b-tooltip.hover title="Keluar" @click.stop="$router.push('/kemahasiswaan/dataaktivitas')">
                  <b-icon icon="x-square"></b-icon>
                </button>                
              </div>
            </template>
            <b-card-body>
              <b-row>
                <b-col>
                  <dl class="row">
                    <dt class="col-sm-3">ID</dt>
                    <dd class="col-sm-9">{{data_aktivitas.id}}</dd>
                  </dl>
                </b-col>
                <b-col>
                  <dl class="row">
                    <dt class="col-sm-4">Tahun / Semester</dt>
                    <dd class="col-sm-8">{{data_aktivitas.tahun}} {{ $store.getters["uiadmin/getNamaSemester"](data_aktivitas.idsmt) }}</dd>
                  </dl>
                </b-col>
              </b-row>
              <b-row>
                <b-col>
                  <dl class="row">
                    <dt class="col-sm-3">Nomor SK</dt>
                    <dd class="col-sm-9">{{data_aktivitas.no_sk_tugas}}</dd>
                  </dl>
                </b-col>
                <b-col>
                  <dl class="row">
                    <dt class="col-sm-4">Lokasi</dt>
                    <dd class="col-sm-8">{{data_aktivitas.lokasi}}</dd>
                  </dl>
                </b-col>
              </b-row>
              <b-row>
                <b-col>
                  <dl class="row">
                    <dt class="col-sm-3">Judul</dt>
                    <dd class="col-sm-9">{{data_aktivitas.judul_aktivitas}}</dd>
                  </dl>
                </b-col>
                <b-col>
                  <dl class="row">
                    <dt class="col-sm-4">Keterangan</dt>
                    <dd class="col-sm-8">{{data_aktivitas.keterangan}}</dd>
                  </dl>
                </b-col>
              </b-row>
              <b-row>
                <b-col>
                  <dl class="row">
                    <dt class="col-sm-3">Jenis Anggota</dt>
                    <dd class="col-sm-9">{{data_aktivitas.jenis_anggota == 1 ? 'Personal' : 'Kelompok'}}</dd>
                  </dl>
                </b-col>
                <b-col>
                  <dl class="row">
                    <dt class="col-sm-4">Created</dt>
                    <dd class="col-sm-8">{{ $date(data_aktivitas.created_at).format("DD.MM.YYYY HH:mm") }}</dd>
                  </dl>
                </b-col>
              </b-row>
              <b-row>
                <b-col>
                  <dl class="row">
                    <dt class="col-sm-3">Jenis Kegiatan</dt>
                    <dd class="col-sm-9">{{data_aktivitas.nama_aktivitas}}</dd>
                  </dl>
                </b-col>
                <b-col>
                  <dl class="row">
                    <dt class="col-sm-4">Updated</dt>
                    <dd class="col-sm-8">{{ $date(data_aktivitas.updated_at).format("DD.MM.YYYY HH:mm") }}</dd>
                  </dl>
                </b-col>
              </b-row>
            </b-card-body>
          </b-card>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <b-card no-body>
            <b-tabs
              v-model="indexTab"
              card
              fill
            >
              <b-tab title="Peserta Aktivitas">
                <b-card-text>Tab contents 1</b-card-text>
              </b-tab>
              <b-tab title="Dosen Pembimbing" lazy>
                <b-card-text>Tab contents 2</b-card-text>
              </b-tab>
              <b-tab title="Dosen Penguji" lazy>
                <b-card-text>Tab contents 2</b-card-text>
              </b-tab>
            </b-tabs>
          </b-card>
        </b-col>
      </b-row>
    </template>
  </KemahasiswaanLayout>
</template>
<script>
  import useVuelidate from '@vuelidate/core'
  import { required } from '@vuelidate/validators'  
  import KemahasiswaanLayout from '@/views/layouts/KemahasiswaanLayout'  
  export default {
    name: 'JenisAktivtasDetail',
    setup() {
      return {
        v$: useVuelidate(), 
        url: '/kemahasiswaan/dataaktivitas',
      }
    },
    created() {
      this.id = this.$route.params.id
      this.initialize()
    },
    data: () => ({
      id: null,
      btnLoading: false,
      prodi_id: null,
      nama_prodi: null,
      tahun_akademik: null,
      semester_akademik: null,
      indexTab: 0,
      daftar_jenis_aktivitas: [],
      formdata: {
        no_sk_tugas: null,
        tanggal_sk_tugas: null,
        jenis_aktivitas_id: null,
        jenis_anggota: 1,
        judul_aktivitas: null,
        keterangan: null,
        lokasi: null,
      },
      data_aktivitas: {},
    }),
    validations() {
      return {
        formdata: {
          no_sk_tugas: {
            required,
          },
          tanggal_sk_tugas: {
            required,
          },
          jenis_aktivitas_id: {
            required,
          },
          judul_aktivitas: {
            required,
          },
        },
      }
    },
    methods: {
      async initialize() {       

        await this.$ajax
          .get(this.url + '/' + this.id, {
            headers: {
              Authorization: this.$store.getters['auth/Token'],
            },
          })
          .then(({ data }) => {
            this.data_aktivitas = data.result
            this.prodi_id = this.data_aktivitas.prodi_id
            this.nama_prodi = this.$store.getters['uiadmin/getProdiName'](
              this.prodi_id
            )
            this.tahun_akademik = this.data_aktivitas.tahun

            this.semester_akademik = this.data_aktivitas.idsmt
          })
      },
      validateState(name) {
        const { $dirty, $error } = this.v$.formdata[name]
        return $dirty ? !$error : null
      },
      async onSubmit() {
        if (!this.v$.formdata.$invalid) {
          this.btnLoading = true

          this.$ajax.post(this.url + '/store',
            {
              prodi_id: this.prodi_id,              
			        idsmt: this.semester_akademik,
              tahun: this.tahun_akademik,
            	no_sk_tugas: this.formdata.no_sk_tugas,
              tanggal_sk_tugas: this.formdata.tanggal_sk_tugas,
              jenis_aktivitas_id: this.formdata.jenis_aktivitas_id,
              jenis_anggota: this.formdata.jenis_anggota,
              judul_aktivitas: this.formdata.judul_aktivitas,
              keterangan: this.formdata.keterangan,
              lokasi: this.formdata.lokasi,
      	    },
            {
            	headers: {
            		Authorization: this.$store.getters['auth/Token'],
            	},
            }
      		)
          .then(() => {
            this.btnLoading = false
            this.$router.push(this.url)
      		})
          .catch(() => {
            this.btnLoading = false
      		})
        }
      },
    },
    watch: {
      indexTab(val) {
        console.log(val)
      },
    },
    components: {
      KemahasiswaanLayout,
    },
  }
</script>
