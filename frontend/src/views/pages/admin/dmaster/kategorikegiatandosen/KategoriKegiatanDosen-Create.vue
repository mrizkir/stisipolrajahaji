<template>
  <DMasterLayout :showrightsidebar="false">
    <template v-slot:page-header>Kategori Kegiatan</template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item to="/dmaster">Data Master</b-breadcrumb-item>
      <b-breadcrumb-item to="/dmaster/dosen/kategorikegiatan">Kategori Kegiatan</b-breadcrumb-item>
      <b-breadcrumb-item active>Tambah</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      <b-form @submit.prevent="onSubmit" name="frmdata" id="frmdata" v-if="$store.getters['auth/can']('DMASTER-DOSEN-KATEGORI-KEGIATAN_STORE')">
        <b-card no-body class="card-primary card-outline">
          <template #header>
            <h3 class="card-title">Tambah Kategori Kegiatan</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" v-b-tooltip.hover title="Keluar" @click.stop="$router.push(url)">
                <b-icon icon="x-square"></b-icon>
              </button>
            </div>
          </template>
          <b-card-body>
            <b-form-group
              label="Kode Kategori:"
              label-for="txtKode"
            >
              <b-form-input
                id="txtKode"
                v-model="v$.formdata.kode_kategori.$model"
                placeholder="Kode Kategori Kegiatan Dosen"
                :state="validateState('kode_kategori')"
                aria-describedby="frmdata-kode"
              />
              <b-form-invalid-feedback
                id="frmdata-mode"
              >
                Kode kategori kegiatan tidak boleh kosong, silahkan diisi !!!.
              </b-form-invalid-feedback>
            </b-form-group>
            <b-form-group
              label="Nama Kategori:"
              label-for="txtNama"
            >
              <b-form-input
                id="txtNama"
                v-model="v$.formdata.nama_kategori.$model"
                placeholder="Nama Kategori Kegiatan Dosen"
                :state="validateState('nama_kategori')"
                aria-describedby="frmdata-nama"
              />
              <b-form-invalid-feedback
                id="frmdata-nama"
              >
                Nama kategori kegiatan tidak boleh kosong, silahkan diisi !!!.
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
      <b-alert class="m-3 font-italic" variant="warning" show v-else>
        Saudara tidak memiliki akses ke halaman ini
      </b-alert>
    </template>
  </DMasterLayout>
</template>
<script>
  import useVuelidate from '@vuelidate/core'
  import { required } from '@vuelidate/validators'  
  import DMasterLayout from '@/views/layouts/DMasterLayout'
  export default {
    name: 'KategoriKegiatanDosenCreate',
    setup() {
      return {
        v$: useVuelidate(), 
        url: '/dmaster/dosen/kategorikegiatan',
      }
    },
    data: () => ({
      btnLoading: false,
      formdata: {
        kode_kategori: null,
        nama_kategori: null,
      },
    }),
    validations() {
      return {
        formdata: {
          kode_kategori: {
            required,
          },
          nama_kategori: {
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
      async onSubmit() {
        if (!this.v$.formdata.$invalid) {
          this.btnLoading = true
          
          this.$ajax.post('/dmaster/dosen/kategorikegiatan/store',
            {
            	kode_kategori: this.formdata.kode_kategori,
            	nama_kategori: this.formdata.nama_kategori,
      	    },
            {
            	headers: {
            		Authorization: this.$store.getters['auth/Token'],
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
      DMasterLayout,
    },
  }
</script>