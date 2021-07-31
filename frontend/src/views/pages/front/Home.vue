<template>
  <FrontLayout>
    <b-form @submit.stop.prevent="login">
      <b-card title="Login">
        <b-card-text>        
          <b-form-group
            id="input-group-username"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm
            content-cols-lg="7"
            label="Username:"
            label-for="input-username"
            description="Masukan username, khusus mahasiswa gunakan NIM"
          >
            <b-form-input
              id="input-username"
              v-model="$v.formdata.username.$model"
              type="text"
              placeholder="Username"
              class="mb-2"
              :state="validateState('username')"
              aria-describedby="input-username-live-feedback"
            />
            <b-form-invalid-feedback id="input-username-live-feedback">
              Mohon isi username.
            </b-form-invalid-feedback>
          </b-form-group>        
          <b-form-group
            id="input-group-password"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm
            content-cols-lg="7"
            label="Password:"
            label-for="input-password"
            description="Masukan password"
          >
            <b-form-input
              id="input-password"            
              v-model="$v.formdata.password.$model"
              type="password"
              :state="validateState('password')"
              placeholder="Password"
              aria-describedby="input-password-live-feedback"
            />
            <b-form-invalid-feedback id="input-pasword-live-feedback">
              Mohon isi password.
            </b-form-invalid-feedback>
          </b-form-group>
          <b-form-group
            id="input-group-page"
            label-cols-sm="4"
            label-cols-lg="3"
            content-cols-sm
            content-cols-lg="7"
            label="Role:"
            label-for="select-page"
            aria-describedby="select-page-live-feedback"
          >
            <b-form-select
              id="select-page"
              v-model="$v.formdata.page.$model"
              class="mb-3"
              :state="validateState('page')"
            >
              <b-form-select-option :value="null">PILIH ROLE</b-form-select-option>
              <b-form-select-option value="sa">SUPER ADMIN</b-form-select-option>
              <b-form-select-option value="m">MANAJEMEN</b-form-select-option>
              <b-form-select-option value="pmb">PMB</b-form-select-option>
              <b-form-select-option value="k">KEUANGAN</b-form-select-option>
              <b-form-select-option value="dw">DOSEN WALI</b-form-select-option>
              <b-form-select-option value="mh">MAHASISWA</b-form-select-option>
              <b-form-select-option value="mb">MAHASISWA BARU</b-form-select-option>
              <b-form-select-option value="al">ALUMNI</b-form-select-option>
              <b-form-select-option value="ot">ORANG TUA WALI</b-form-select-option>
            </b-form-select>
            <b-form-invalid-feedback id="select-page-live-feedback">
              Mohon role untuk dipilih.
            </b-form-invalid-feedback>
          </b-form-group>
          <b-form-invalid-feedback :state="ajaxMessage">
            {{ ajaxMessage }}
          </b-form-invalid-feedback>
        </b-card-text>
        <b-button type="submit" variant="primary" :disabled="btnLoading">
          Login
        </b-button>      
      </b-card>
    </b-form>
  </FrontLayout>
</template>
<script>
import FrontLayout from "@/views/layouts/FrontLayout";
import { validationMixin } from "vuelidate";
import { required } from "vuelidate/lib/validators";

export default {
  name: "Home",
  mixins: [validationMixin],
  created() {
    if (this.$store.getters["auth/Authenticated"]) {
      this.$router.push(
        "/dashboard/" + this.$store.getters["auth/AccessToken"]
      );
    }
  },
  data: () => ({
    btnLoading: false,
    //form
    formdata: {
      username: null,
      password: null,
      page: null,
    },
    ajaxMessage: null,
  }),
  validations: {
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
  },
  methods: {
    validateState(name) {
      const { $dirty, $error } = this.$v.formdata[name];
      return $dirty ? !$error : null;
    },
    async login() {
      this.$v.formdata.$touch();
      if (this.$v.formdata.$anyError) {
        return;
      }
      this.ajaxMessage = null;
      this.btnLoading = true;
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
              };
              this.$store.dispatch("auth/afterLoginSuccess", data_user);
            });
          this.btnLoading = false;
          this.$router.push("/dashboard/" + data.access_token);
        })
        .catch(() => {          
          this.btnLoading = false;
        });
    },
  },
  components: {
    FrontLayout,
  },
};
</script>
