<template>
  <PenggunaSistemLayout>
    <template v-slot:page-header>
      Pengguna Manajemen
    </template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item to="/sistem-pengguna">Pengguna Sistem</b-breadcrumb-item>      
      <b-breadcrumb-item to="/sistem-pengguna/manajemen">Pengguna Manajemen</b-breadcrumb-item>      
      <b-breadcrumb-item active>Tambah</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      <b-container fluid v-if="$store.getters['auth/can']('SYSTEM-USERS-AKADEMIK_UPDATE')">
        <b-col>
          <b-form @submit.prevent="onSubmit" name="frmdata" id="frmdata">
            <b-card
              no-body
              class="card-primary card-outline"
            >
              <template #header>
                <h3 class="card-title">Tambah Pengguna</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" v-b-tooltip.hover title="Keluar" @click.stop="$router.push('/sistem-pengguna/manajemen')">
                    <b-icon icon="x-square"></b-icon>
                  </button>
                </div>
              </template>
              <b-card-body>              
                <b-form-group
                  label="Nama:"
                  label-for="txtNama"
                >      
                  <b-form-input
                    id="txtUsername"
                    v-model="v$.formdata.nama.$model"
                    placeholder="Masukan Nama"
                    :state="validateState('nama')"
                    aria-describedby="frmdata-nama"
                  />
                  <b-form-invalid-feedback
                    id="frmdata-nama"
                  >
                    Nama tidak boleh kosong, silahkan diisi !!!.
                  </b-form-invalid-feedback>
                </b-form-group>
                <b-form-group
                  label="Email:"
                  label-for="txtEmail"
                >      
                  <b-form-input
                    id="txtEmail"
                    v-model="v$.formdata.email.$model"
                    placeholder="Masukan Email"
                    :state="validateState('email')"
                    aria-describedby="frmdata-email"
                  />
                  <b-form-invalid-feedback
                    id="frmdata-email"
                  >
                    Email tidak boleh kosong / Salah format, silahkan diisi !!!.
                  </b-form-invalid-feedback>
                </b-form-group>
                <b-form-group
                  label="Username:"
                  label-for="txtUsername"
                >      
                  <b-form-input
                    id="txtUsername"
                    v-model="v$.formdata.username.$model"
                    placeholder="Masukan Username"
                    :state="validateState('username')"
                    aria-describedby="frmdata-username"
                  />
                  <b-form-invalid-feedback
                    id="frmdata-username"
                  >
                    Username tidak boleh kosong, silahkan diisi !!!.
                  </b-form-invalid-feedback>
                </b-form-group>
                <b-form-group
                  label="Password:"
                  label-for="txtPassword"
                >      
                  <b-form-input
                    id="txtPassword"
                    v-model="formdata.password"
                    type="password"
                    placeholder="Password"
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
        </b-col>
      </b-container>
    </template>
  </PenggunaSistemLayout>
</template>
<script>
  import useVuelidate from '@vuelidate/core'
  import { required,email } from '@vuelidate/validators'

  import PenggunaSistemLayout from '@/views/layouts/PenggunaSistemLayout'  
  export default {
    name: 'PenggunaManajemenCreate',    
    setup() {
      return { 
        v$: useVuelidate(),        
      }
    },

    data: () => ({      
      btnLoading: false,
      user_id: null,
      formdata: {
        nama: null,
        email: null,
        username: null,
        password: null,        
      },      
    }),

    validations() {
      return {
        formdata: {
          nama: {
            required,
          },          
          email: {
            required,
            email, 
          }, 
          username: {
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
          
          this.$ajax.post('/system/usersmanajemen/store',
						{
							nama: this.formdata.nama,
							email: this.formdata.email,
							username: this.formdata.username,
							password: this.formdata.password,							
						},
						{
							headers: {
								Authorization: 'Bearer ' + this.$store.getters['auth/AccessToken'],
							}
						}
					)
          .then(() => {
						this.$router.push('/sistem-pengguna/manajemen')
					})
          .catch(() => {
						this.btnLoading = false;
					});
        }
      },
    },
    components: {
			PenggunaSistemLayout,
		},
  }
</script>


