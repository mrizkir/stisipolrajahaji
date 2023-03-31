<template>
  <PenggunaSistemLayout>
    <template v-slot:page-header>Pengguna Mahasiswa</template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item to="/sistem-pengguna">
        Pengguna Sistem
      </b-breadcrumb-item>
      <b-breadcrumb-item to="/sistem-pengguna/mahasiswa">
        Pengguna Mahasiswa
      </b-breadcrumb-item>
      <b-breadcrumb-item active>Tambah</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      <b-container
        fluid
        v-if="$store.getters['auth/can']('SYSTEM-USERS-MAHASISWA_STORE')"
      >
        <b-row>
          <b-col>
            <b-form @submit.prevent="onSubmit" name="frmdata" id="frmdata">
              <b-card no-body class="card-primary card-outline">
                <template #header>
                  <h3 class="card-title">Tambah Pengguna</h3>
                  <div class="card-tools">
                    <button
                      type="button"
                      class="btn btn-tool"
                      v-b-tooltip.hover
                      title="Keluar"
                      @click.stop="$router.push('/sistem-pengguna/mahasiswa')"
                    >
                      <b-icon icon="x-square"></b-icon>
                    </button>
                  </div>
                </template>
                <b-card-body>
                  <b-alert class="m-3 font-italic" show>
                    Proses ini akan menyalin username dan password per 50 mahasiswa dari portal1 ke portal2 ini.
                  </b-alert>
                  <b-row>
                    <b-col cols="2" md="auto">
                      <b-card
                        bg-variant="dark"
                        text-variant="white"
                        title="JUMLAH MAHASISWA"
                      >
                        <b-card-text>
                          {{ jumlah_mahasiswa }}
                        </b-card-text>
                      </b-card>
                    </b-col>
                  </b-row>
                </b-card-body>
                <template #footer>
                  <b-button
                    type="submit"
                    :disabled="v$.formdata.$invalid || btnLoading"
                    variant="primary"
                  >
                    Salin
                  </b-button>
                </template>
              </b-card>
            </b-form>
          </b-col>
        </b-row>
      </b-container>
    </template>
  </PenggunaSistemLayout>
</template>
<script>
  import useVuelidate from '@vuelidate/core'
  import PenggunaSistemLayout from '@/views/layouts/PenggunaSistemLayout'
  export default {
    name: 'PenggunaMahasiswaCreate',
    setup() {
      return {
        v$: useVuelidate(),
      }
    },
    mounted() {
      this.initialize()
    },
    data: () => ({
      btnLoading: false,
      formdata: {},
      jumlah_mahasiswa: 0,
    }),
    validations() {
      return {
        formdata: {},
      }
    },
    methods: {
      async initialize() {
        await this.$ajax
          .get('/system/usersmahasiswa/create', {
            headers: {
              Authorization: this.$store.getters['auth/Token'],
            },
          })
          .then(({ data }) => {
            this.jumlah_mahasiswa = data.jumlah
          })
      },
      validateState(name) {
        const { $dirty, $error } = this.v$.formdata[name]
        return $dirty ? !$error : null
      },
      async onSubmit() {
        if (!this.v$.formdata.$invalid) {
          this.btnLoading = true
          this.$ajax
            .post(
              '/system/usersmahasiswa/store',
              {},
              {
                headers: {
                  Authorization: this.$store.getters['auth/Token'],
                },
              }
            )
            .then(() => {
              this.$router.push('/sistem-pengguna/mahasiswa')
            })
            .catch(() => {
              this.btnLoading = false
            })
        }
      },
    },
    components: {
      PenggunaSistemLayout,
    },
  }
</script>
