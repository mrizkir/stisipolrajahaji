<template>
  <FrontendLayout>
    <b-form-row>
      <b-col>
        <b-form @submit.prevent="onSubmit" name="frmlogin" id="frmlogin">    
          <b-form-group
            label="Username:"
            label-for="txtUsername"
          >      
            <b-form-input
              id="txtUsername"
              v-model="v$.formdata.username.$model"
              placeholder="Masukan username"
              :state="validateState('username')"
              aria-describedby="frmlogin-username"
            />
            <b-form-invalid-feedback
              id="frmlogin-username"
            >
              Username tidak boleh kosong, silahkan diisi !!!.
            </b-form-invalid-feedback>
          </b-form-group>
          
          <b-form-group
            label="Username:"
            label-for="txtPassword"
          >      
            <b-form-input
              id="txtPassword"
              v-model="v$.formdata.password.$model"
              placeholder="Masukan password"
              type="password"
              :state="validateState('password')"
              aria-describedby="frmlogin-password"
            />
            <b-form-invalid-feedback
              id="frmlogin-password"
            >
              Password tidak boleh kosong, silahkan diisi !!!.
            </b-form-invalid-feedback>
          </b-form-group>
          <b-form-group
            label="Halaman:"            
          >      
            <b-form-select
              v-model="v$.formdata.page.$model"
              :options="daftar_page"
              :state="validateState('page')"
              aria-describedby="frmlogin-page"
            />
            <b-form-invalid-feedback
              id="frmlogin-page"
            >
              Silahkan pilih akan mengakses halaman apa ?
            </b-form-invalid-feedback>
          </b-form-group>
          <!-- Submit Button -->
          <div class="buttons-w">
            <button :disabled="v$.formdata.$invalid || btnLoading" class="btn btn-primary">Login</button>
          </div>
        </b-form>
      </b-col>
    </b-form-row>
  </FrontendLayout>
</template>
<script>
  import FrontendLayout from '@/views/layouts/FrontendLayout'
  import useVuelidate from '@vuelidate/core'
  import { required } from '@vuelidate/validators'

  export default {
    name: 'Login',
    setup() {
      return { 
        v$: useVuelidate(),        
      }
    },

    created() {
			if (this.$store.getters["auth/Authenticated"]) {				
				this.$router.push(
					"/dashboard/" + this.$store.getters["auth/AccessToken"]
				);
			}      
		},

    mounted() {
      this.daftar_page = this.$store.getters['uifront/getDaftarPage']
      if (!this.daftar_page.length) {
        this.$router.go();
      }
    },

    data() {
      return {
        btnLoading: false,
        daftar_page: [],
        formdata: {
          username: null,
          password: null,
          page: '',
        },
      }
    },
      
    validations() {
      return {
        formdata: {
          username: {
            required 
          },
          password: {
            required,             
          },
          page: {
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
          await this.$ajax
						.post("/auth/login", {
							username: this.formdata.username,
							password: this.formdata.password,
							page: this.formdata.page,
						})
						.then(({ data }) => {
							this.$ajax
								.get("/auth/me", {
									headers: {
										Authorization: data.token_type + " " + data.access_token,
									},
								})
								.then(response => {
									var data_user = {
										token: data,
										user: response.data,
									}
									this.$store.dispatch("auth/afterLoginSuccess", data_user)
								})
							this.btnLoading = false							
							this.$router.push("/dashboard/" + data.access_token)
						})
						.catch(() => {
							this.btnLoading = false
						})
        }      
      }    
    },
    components: {
			FrontendLayout,
		},
  }
</script>