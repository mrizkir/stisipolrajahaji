<template>
  <AkademikLayout>
    <template v-slot:page-header>
      Jenis Aktivitas
    </template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item to="/akademik">Akademik</b-breadcrumb-item>          
      <b-breadcrumb-item to="/akademik/perkuliahan/aktivitasmahasiswa/jenisaktivitas">Jenis Aktivitas</b-breadcrumb-item>          
      <b-breadcrumb-item active>Tambah</b-breadcrumb-item>      
    </template>
    <template v-slot:page-content>   
      <b-form @submit.prevent="onSubmit" name="frmdata" id="frmdata">
        <b-card
          no-body
          class="card-primary card-outline"
        >
          <template #header>
            <h3 class="card-title">Ubah Jenis Aktivitas</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" v-b-tooltip.hover title="Keluar" @click.stop="$router.push(url)">
                <b-icon icon="x-square"></b-icon>
              </button>
            </div>
          </template>
          <b-card-body>      
            <b-form-group
              label="Nama Jenis Aktivitas:"
              label-for="txtNama"
            >      
              <b-form-input
                id="txtNama"
                v-model="v$.formdata.nama_aktivitas.$model"
                placeholder="Nama Jenis Aktivitas"
                :state="validateState('nama_aktivitas')"
                aria-describedby="frmdata-nama"
              />
              <b-form-invalid-feedback
                id="frmdata-nama"
              >
                Nama jenis aktivitas tidak boleh kosong, silahkan diisi !!!.
              </b-form-invalid-feedback>
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
  </AkademikLayout>
</template>
<script>
  import useVuelidate from '@vuelidate/core'
  import { required } from '@vuelidate/validators'  
  import AkademikLayout from '@/views/layouts/AkademikLayout'  
  export default {
    name: 'JenisAktivtasEdit',  
    created() {
      this.idjenis =this.$route.params.idjenis
      this.initialize()
    },
    setup() {
      return { 
        v$: useVuelidate(), 
        url: '/akademik/perkuliahan/aktivitasmahasiswa/jenisaktivitas',       
      }
    },
    data: () => ({  
      idjenis: null,    
      btnLoading: false,      
      formdata: {
        nama_aktivitas: null,               
      },      
    }),
    validations() {
      return {
        formdata: {
          nama_aktivitas: {
            required,
          },
        },
      }
    },
    methods: {
      validateState(name) {
        const { $dirty, $error } = this.v$.formdata[name]
        return $dirty ? !$error : null
      },
      async initialize() {
        var url = '/akademik/perkuliahan/jenisaktivitas/' + this.idjenis;
        await this.$ajax.get(url, {
          headers: {
            Authorization: 'Bearer ' + this.$store.getters['auth/AccessToken'],
          }
        })
        .then(({ data }) => {
          this.formdata = data.result
        })
      },
      async onSubmit() {
        if (!this.v$.formdata.$invalid) {
          this.btnLoading = true
          
          this.$ajax.post('/akademik/perkuliahan/jenisaktivitas/' + this.idjenis,
						{
              _method: 'PUT',
							nama_aktivitas: this.formdata.nama_aktivitas,							
						},
						{
							headers: {
								Authorization: 'Bearer ' + this.$store.getters['auth/AccessToken'],
							}
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
    components: {
			AkademikLayout,
		},
  }
</script>