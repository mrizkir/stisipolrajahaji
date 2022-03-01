<template>
  <b-form @submit.prevent="onSubmit" name="frmdata" id="frmdata">
    <b-card
      no-body
      class="card-primary card-outline"
    >
      <template #header>
        <h3 class="card-title">Ubah Pengguna</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" v-b-tooltip.hover title="Keluar" @click.stop="$router.push(urlfront)">
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
            id="txtNama"
            v-model="v$.datauser.nama.$model"
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
            v-model="v$.datauser.email.$model"
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
            v-model="v$.datauser.username.$model"
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
            v-model="datauser.password"
            type="password"
            placeholder="Password"
          />
        </b-form-group>
        <b-form-group
          label="Status:"            
        >      
          <b-form-select
            v-model="datauser.active"
            :options="[{value: 0, text: 'TIDAK AKTIF'}, {value: 1, text: 'AKTIF'}]"
          />          
        </b-form-group>
      </b-card-body>
      <template #footer>    
        <b-button
          type="submit"
          :disabled="v$.datauser.$invalid || btnLoading"
          variant="primary"
        >
          Simpan
        </b-button>      
      </template>
    </b-card>
  </b-form>  
</template>
<script>
  import useVuelidate from '@vuelidate/core'
  import { required, email } from '@vuelidate/validators'  
  export default {
    name: 'user-edit',    
    setup() {
      return { 
        v$: useVuelidate(),        
      }
    },
    props: {
      urlfront: {
        type: String,
        required: true,
      }, 
      urlbackend: {
        type: String,
        required: true,
      },
      datauser: {
        type: Object,
        required: true,
      },
    },

    data: () => ({      
      btnLoading: false,
    }),

    validations() {
      return {
        datauser: {
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
        const { $dirty, $error } = this.v$.datauser[name]
        return $dirty ? !$error : null
      },      
      async onSubmit() {
        if (!this.v$.datauser.$invalid) {
          this.btnLoading = true
          
          this.$ajax.post(this.urlbackend,
						{
              _method: 'PUT',
							nama: this.datauser.nama,
							email: this.datauser.email,
							username: this.datauser.username,
							password: this.datauser.password,
							active: this.datauser.active,
						},
						{
							headers: {
								Authorization: 'Bearer ' + this.$store.getters['auth/AccessToken'],
							}
						}
					)
          .then(() => {
						this.$router.push(this.urlfront)
					})
          .catch(() => {
						this.btnLoading = false;
					});
        }
      },
    },    
  }
</script>