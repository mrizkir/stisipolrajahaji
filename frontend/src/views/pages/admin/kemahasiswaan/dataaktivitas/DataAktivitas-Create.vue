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
      <b-breadcrumb-item active>Tambah</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      <b-form @submit.prevent="onSubmit" name="frmdata" id="frmdata" v-if="$store.getters['auth/can']('KEMAHASISWAAN-AKTIVITAS_STORE')">
        <b-card no-body class="card-primary card-outline">
          <template #header>
            <h3 class="card-title">Tambah Data Aktivitas</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" v-b-tooltip.hover title="Keluar" @click.stop="$router.push(url)">
                <b-icon icon="x-square"></b-icon>
              </button>
            </div>
          </template>
          <b-card-body>
            <dl class="row">
              <dt class="col-sm-3">Program Studi</dt>
              <dd class="col-sm-9">{{ nama_prodi }}</dd>
            </dl>
            <dl class="row">
              <dt class="col-sm-3">Semester</dt>
              <dd class="col-sm-9">{{ tahun_akademik }} {{ $store.getters["uiadmin/getNamaSemester"](semester_akademik) }}</dd>
            </dl>
            <hr>
            <b-form-group
              label="Nomor SK Tugas:"
              label-for="txtNoSKTugas"
            >      
              <b-form-input
                id="txtNoSKTugas"
                v-model="v$.formdata.no_sk_tugas.$model"
                placeholder="Masukan Nomor SK Tugas"
                :state="validateState('no_sk_tugas')"
                aria-describedby="frmdata-no-sk-tugas"
              />
              <b-form-invalid-feedback
                id="frmdata-no-sk-tugas"
              >
                Nomor SK Tugas tidak boleh kosong, silahkan diisi !!!.
              </b-form-invalid-feedback>
            </b-form-group>
            <b-form-group
              label="Tanggal SK Tugas:"
              label-for="selectTglSKTugas"
            >
              <b-form-datepicker
                id="selectTglSKTugas"
                v-model="v$.formdata.tanggal_sk_tugas.$model" 
                :state="validateState('tanggal_sk_tugas')"
                locale="id"
                placeholder="Tanggal SK Tugas"
                aria-describedby="frmdata-tanggal-sk-tugas"
              />              
              <b-form-invalid-feedback
                id="frmdata-tanggal-sk-tugas"
              >
                Tanggal SK Tugas tidak boleh kosong, silahkan dipilih !!!.
              </b-form-invalid-feedback>
            </b-form-group>
            
            <b-form-group
              label="Jenis Kegiatan:"
              label-for="selectJenisAktivitas"
            >
              <b-form-select
                v-model="v$.formdata.jenis_aktivitas_id.$model"
                :state="validateState('jenis_aktivitas_id')"
                :options="daftar_jenis_aktivitas"
                aria-describedby="frmdata-jenis-kegiatan-id"
              />
              <b-form-invalid-feedback
                id="frmdata-jenis-kegiatan-id"
              >
                Jenis kegiatan tidak boleh kosong, silahkan dipilih !!!.
              </b-form-invalid-feedback>
            </b-form-group>            
            
            <b-form-group
              label="Judul:"
              label-for="txtJudul"
            >      
              <b-form-textarea
                id="txtJudul"
                v-model="v$.formdata.judul_aktivitas.$model"
                placeholder="Judul Aktivitas"
                :state="validateState('judul_aktivitas')"
                aria-describedby="frmdata-judul-aktivitas"
                rows="3"
                max-rows="6"
              />
              <b-form-invalid-feedback
                id="frmdata-judul-aktivitas"
              >
                Judul Aktivitas tidak boleh kosong, silahkan diisi !!!.
              </b-form-invalid-feedback>
            </b-form-group>

            <b-form-group
              label="Jenis Anggota:"              
              v-slot="{ ariaDescribedby }"
            >
              <b-form-radio v-model="formdata.jenis_anggota" :aria-describedby="ariaDescribedby" name="formdata-jenis-anggota" value="1">Personal</b-form-radio>
              <b-form-radio v-model="formdata.jenis_anggota" :aria-describedby="ariaDescribedby" name="formdata-jenis-anggota" value="2">Kelompok</b-form-radio>              
            </b-form-group>

            <b-form-group
              label="Keterangan:"
              label-for="txtKeterangan"
            >      
              <b-form-textarea
                id="txtKeterangan"
                v-model="formdata.keterangan"
                placeholder="Keterangan"                                
                rows="3"
                max-rows="6"
              />              
            </b-form-group>

            <b-form-group
              label="Lokasi:"
              label-for="txtLokasi"
            >      
              <b-form-input
                id="txtLokasi"
                v-model="formdata.lokasi"
                placeholder="Lokasi Kegiatan / Aktivitas"                
                aria-describedby="frmdata-txtLokasi"
              />             
            </b-form-group>

          </b-card-body>
          <template #footer>    
            <b-button
              type="submit"
              :disabled="v$.formdata.$invalid || btnLoading"
              variant="primary"
            >
              Simpan
            </b-button>      
          </template>
        </b-card>
      </b-form>
    </template>
  </KemahasiswaanLayout>
</template>
<script>
  import useVuelidate from '@vuelidate/core'
  import { required } from '@vuelidate/validators'  
  import KemahasiswaanLayout from '@/views/layouts/KemahasiswaanLayout'  
  export default {
    name: 'JenisAktivtasCreate',
    setup() {
      return {
        v$: useVuelidate(), 
        url: '/kemahasiswaan/dataaktivitas',
      }
    },
    created() {
      this.prodi_id = this.$store.getters['uiadmin/AtributeValueOfPage'](
        'dataaktivitas',
        'prodi_id'
      )
      this.nama_prodi = this.$store.getters['uiadmin/getProdiName'](
        this.prodi_id
      )
      this.tahun_akademik = this.$store.getters['uiadmin/AtributeValueOfPage'](
        'dataaktivitas',
        'tahun_akademik'
      )
      this.semester_akademik =
        this.$store.getters['uiadmin/AtributeValueOfPage'](
          'dataaktivitas',
          'semester_akademik'
        )

      this.initialize()
    },
    data: () => ({
      btnLoading: false,
      prodi_id: null,
      nama_prodi: null,
      tahun_akademik: null,
      semester_akademik: null,

      daftar_jenis_aktivitas: [],
      formdata: {
        no_sk_tugas: null,        
        tanggal_sk_tugas: null,
        jenis_aktivitas_id: null,
        jenis_anggota: null,
        judul_aktivitas: null,
        keterangan: null,
        lokasi: null,
      },
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
        var url = '/kemahasiswaan/jenisaktivitas'

        await this.$ajax
          .get(url, {
            headers: {
              Authorization: this.$store.getters['auth/Token'],
            }
          })
          .then(({ data }) => {            
            var jenis_aktivitas = data.result.data
            this.daftar_jenis_aktivitas.push({
              value: null,
              text: 'PILIH JENIS KEGIATAN',
            })
            if (jenis_aktivitas) {
              jenis_aktivitas.forEach(element => {
                this.daftar_jenis_aktivitas.push({
                  value: element.idjenis,
                  text: element.nama_aktivitas,
                })
              });
            }
          })
      },
      validateState(name) {
        const { $dirty, $error } = this.v$.formdata[name]
        return $dirty ? !$error : null
      },
      async onSubmit() {
        if (!this.v$.formdata.$invalid) {
        this.btnLoading = true

        }
      },
    },
    components: {
      KemahasiswaanLayout,
    },
  }
</script>
