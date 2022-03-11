<template>
  <div class="login-box" v-if="userlogged">
    <div class="login-logo">
      <b-link to="/">PortalEkampusV2</b-link>
    </div>
    <b-card class="login-card-body">
      <p class="login-box-msg">Silahkan masukan username dan password</p>
      <b-form @submit.prevent="onSubmit" name="frmlogin" id="frmlogin">
        <b-form-group label="Username:" label-for="txtUsername">
          <b-form-input
            id="txtUsername"
            v-model="v$.formdata.username.$model"
            placeholder="Masukan username"
            :state="validateState('username')"
            aria-describedby="frmlogin-username"
          />
          <b-form-invalid-feedback id="frmlogin-username">
            Username tidak boleh kosong, silahkan diisi !!!.
          </b-form-invalid-feedback>
        </b-form-group>
        <b-form-group label="Password:" label-for="txtPassword">
          <b-form-input
            id="txtPassword"
            v-model="v$.formdata.password.$model"
            placeholder="Masukan password"
            type="password"
            :state="validateState('password')"
            aria-describedby="frmlogin-password"
          />
          <b-form-invalid-feedback id="frmlogin-password">
            Password tidak boleh kosong, silahkan diisi !!!.
          </b-form-invalid-feedback>
        </b-form-group>
        <b-form-group label="Halaman:" label-for="selectPage">
          <b-form-select
            id="selectPage"
            v-model="v$.formdata.page.$model"
            :options="daftar_page"
            :state="validateState('page')"
            aria-describedby="frmlogin-page"
          />
          <b-form-invalid-feedback id="frmlogin-page">
            Silahkan pilih akan mengakses halaman apa ?
          </b-form-invalid-feedback>
        </b-form-group>
        <div class="buttons-w">
          <b-button
            type="submit"
            :disabled="v$.formdata.$invalid || btnLoading"
            variant="primary"
            block
          >
            Login
          </b-button>
          <b-alert :show="form_error" variant="danger" class="mt-2">
            Username atau Password tidak dikenal !!!
          </b-alert>
        </div>
      </b-form>
    </b-card>
  </div>
</template>
<script>
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
      if (this.$store.getters['auth/Authenticated']) {
        this.$router.push(
          '/dashboard/' + this.$store.getters['auth/AccessToken']
        )
      } else {
        this.$store.dispatch('uifront/init', this.$ajax)
      }
    },

    data() {
      return {
        form_error: false,
        userlogged: true,
        btnLoading: false,
        daftar_page: [
          {
            text: 'Pilih Halaman',
            value: '',
          },
          {
            text: 'superadmin',
            value: 'sa',
          },
          {
            text: 'manajemen',
            value: 'm',
          },
          {
            text: 'pmb',
            value: 'pmb',
          },
          {
            text: 'keuangan',
            value: 'k',
          },
          {
            text: 'operator nilai',
            value: 'on',
          },
          {
            text: 'dosen',
            value: 'd',
          },
          {
            text: 'dosen wali',
            value: 'dw',
          },
          {
            text: 'mahasiswa',
            value: 'mh',
          },
          {
            text: 'mahasiswa baru',
            value: 'mb',
          },
          {
            text: 'alumni',
            value: 'al',
          },
          {
            text: 'orangtua wali',
            value: 'ot',
          },
        ],
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
            required,
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
          this.form_error = false
          await this.$ajax
            .post('/auth/login', {
              username: this.formdata.username,
              password: this.formdata.password,
              page: this.formdata.page,
            })
            .then(({ data }) => {
              this.$ajax
                .get('/auth/me', {
                  headers: {
                    Authorization: data.token_type + ' ' + data.access_token,
                  },
                })
                .then((response) => {
                  var data_user = {
                    token: data,
                    user: response.data,
                  }
                  this.$store.dispatch('auth/afterLoginSuccess', data_user)
                })
              this.btnLoading = false
              this.userlogged = false
              this.$router.push('/dashboard/' + data.access_token)
            })
            .catch(() => {
              this.form_error = true
              this.btnLoading = false
            })
        }
      },
    },
  }
</script>
