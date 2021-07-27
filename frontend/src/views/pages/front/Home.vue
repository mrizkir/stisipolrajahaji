<template>
  <FrontLayout>
    <b-card title="Login">
      <b-form @submit.stop.prevent="login">
        <b-form-group
          id="input-group-username"
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
        <b-button type="submit" variant="primary">
          Login
        </b-button>
      </b-form>
    </b-card>
  </FrontLayout>
</template>
<script>
import FrontLayout from "@/views/layouts/FrontLayout";
import { validationMixin } from "vuelidate";
import { required } from "vuelidate/lib/validators";

export default {
  name: "Home",
  mixins: [validationMixin],
  data: () => ({
    formdata: {
      username: null,
      password: null,
    },
  }),
  validations: {
    formdata: {
      username: {
        required,
      },
      password: {
        required,
      },
    },
  },
  methods: {
    validateState(name) {
      const { $dirty, $error } = this.$v.formdata[name];
      return $dirty ? !$error : null;
    },
    login() {
      this.$v.formdata.$touch();
      if (this.$v.formdata.$anyError) {
        return;
      }
      console.log(this.formdata.username);
      console.log(this.formdata.password);
    },
  },
  components: {
    FrontLayout,
  },
};
</script>
