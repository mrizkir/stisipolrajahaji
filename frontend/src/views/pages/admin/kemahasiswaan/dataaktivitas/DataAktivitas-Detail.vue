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
                <b-form @submit.prevent="onSubmitPeserta" name="frmdata_peserta" id="frmdata_peserta" class="card-text">
                  <b-form-group
                    label-cols-sm="2"
                    label-cols-lg="2"
                    content-cols-sm
                    content-cols-lg="10"
                    label="NIM:"
                    label-for="txtNim"
                  >    
                    <b-form-input
                      id="txtNim"
                      v-model="v$.formdata_peserta.nim.$model"
                      placeholder="Masukan NIM"
                      :state="validateState('nim')"
                      aria-describedby="frmdata-peserta-nim"
                    />
                    <b-form-invalid-feedback
                      id="frmdata-peserta-nim"
                    >
                      NIM tidak boleh kosong, silahkan diisi !!!.
                    </b-form-invalid-feedback>
                  </b-form-group>
                  <b-form-group
                    label-cols-sm="2"
                    label-cols-lg="2"
                    content-cols-sm
                    content-cols-lg="10"
                    label="Peran Peserta:"
                    label-for="selectPeranPeserta"
                  >
                    <b-form-select
                      id="selectPeranPeserta"
                      v-model="v$.formdata_peserta.jenis_anggota.$model"
                      :options="[{value: 1, text: 'Personal'}]"
                      :state="validateState('jenis_anggota')"
                      aria-describedby="frmdata-peserta-jenis-anggota"
                    />
                    <b-form-invalid-feedback id="frmdata-peserta-jenis-anggota">
                      Silahkan pilih jenis anggota
                    </b-form-invalid-feedback>                  
                  </b-form-group>
                  <div class="form-row form-group">
                    <div class="col-sm col-log-10 offset-2">
                      <b-button
                        type="submit"
                        :disabled="v$.formdata_peserta.$invalid || btnLoading"
                        variant="primary"
                      >
                        Tambah
                      </b-button>
                    </div>
                  </div>
                </b-form>              
                <b-card-text class="p-0 m-0">
                  <b-table
                    id="datatable_peserta"
                    primary-key="id"
                    :fields="fields_peserta"
                    :items="datatable_peserta"                    
                    striped
                    hover
                    show-empty
                    responsive                    
                    small
                  >
                    <template #cell(no)="{ index }">
                      {{ index + 1 }}
                    </template>
                    <template #cell(jenis_anggota)="{ item }">
                      {{ item.jenis_anggota == 1 ? 'Personal' : 'Kelompok' }}
                    </template>
                    <template #cell(aksi)="{ item }">
                      <b-button
                        :id="'btDelete' + item.id"
                        variant="outline-danger p-1"
                        size="xs"
                        @click.stop="showModalDeletePeserta(item)"
                        :disabled="btnLoading"
                        v-if="$store.getters['auth/can']('KEMAHASISWAAN-AKTIVITAS_DESTROY')"
                      >
                        <b-icon icon="trash" class="p-0 m-0"></b-icon>
                      </b-button>
                      <b-tooltip :target="'btDelete' + item.id" variant="danger">Hapus Data Peserta</b-tooltip>
                    </template>
                    <template #emptytext>
                      tidak ada data yang bisa ditampilkan
                    </template>
                  </b-table>
                  <b-modal
                    id="modal-delete-peserta"
                    header-bg-variant="danger"
                    centered
                    @hidden="resetModal"
                    @ok="handleDeletePeserta"
                  >
                    <template #modal-title>Hapus Data</template>
                    <div class="d-block">
                      Data peserta aktivitas dengan nim "{{ dataItem.nim }}" akan dihapus ?
                    </div>
                  </b-modal>
                </b-card-text>
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
      this.indexTab = this.$store.getters['uiadmin/AtributeValueOfPage'](
        'dataaktivitas',
        'indexTab'
      )
      this.initialize()
    },
    mounted() {
      this.firstloading = false
    },
    data: () => ({
      id: null,
      firstloading: true,
      btnLoading: false,
      prodi_id: null,
      nama_prodi: null,
      tahun_akademik: null,
      semester_akademik: null,
      indexTab: 0,
      daftar_jenis_aktivitas: [],
      formdata_peserta: {
        nim: null,
        jenis_anggota: 1,
      },
      data_aktivitas: {},

      dataItem: {},
      //table peserta
      datatable_peserta: [],
      fields_peserta: [
        {
          label: 'No.',
          key: 'no',
          thStyle: 'width: 50px',
        },
        {
          key: 'nim',
          label: 'NIM',
        },
        {
          key: 'nirm',
          label: 'NIRM',
        },
        {
          key: 'nama_mhs',
          label: 'Nama Mahasiswa',
        },
        {
          key: 'jenis_anggota',
          label: 'Jenis',
        },       
            
        {
          label: 'Aksi',
          key: 'aksi',
          thStyle: 'width: 50px',
        },
      ],
    }),
    validations() {
      return {
        formdata_peserta: {
          nim: {
            required,
          },
          jenis_anggota: {
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

          this.$ajax
            .get(this.url + '/' + this.id + '/peserta', {
              headers: {
                Authorization: this.$store.getters['auth/Token'],
              },
            })
            .then(({ data }) => {
              this.datatable_peserta = data.result
            })
      },
      validateState(name) {
        const { $dirty, $error } = this.v$.formdata_peserta[name]
        return $dirty ? !$error : null
      },
      async onSubmitPeserta() {
        if (!this.v$.formdata_peserta.$invalid) {
          this.btnLoading = true

          this.$ajax.post(this.url + '/storepeserta',
            {
              data_aktivitas_id: this.id,    
			        nim: this.formdata_peserta.nim,      
              jenis_anggota: this.formdata_peserta.jenis_anggota,      
      	    },
            {
            	headers: {
            		Authorization: this.$store.getters['auth/Token'],
            	},
            }
      		)
          .then(() => {
            this.btnLoading = false
            this.$router.go()
      		})
          .catch(() => {
            this.btnLoading = false
      		})
        }
      },
      showModalDeletePeserta(item) {
        this.dataItem = item
        this.$bvModal.show('modal-delete-peserta')
      },
      resetModal() {
        this.dataItem = {}
      },
      handleDeletePeserta(event) {
        event.preventDefault()
        this.btnLoading = true
        this.$ajax
          .post(
            this.url + '/' + this.dataItem.id + '/deletepeserta',
            {
              _method: 'DELETE',
            },
            {
              headers: {
                Authorization: this.$store.getters['auth/Token'],
              },
            }
          )
          .then(() => {
            this.$router.go()
            this.btnLoading = false
          })
          .catch(() => {
            this.btnLoading = false
          })
      },
    },
    watch: {
      indexTab(val) {        
        var page = this.$store.getters['uiadmin/Page']('dataaktivitas')
        page.indexTab = val
        this.$store.dispatch('uiadmin/updatePage', page)
      },
    },
    components: {
      KemahasiswaanLayout,
    },
  }
</script>
