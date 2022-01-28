<template>
  <FrontendLayout>
    <b-form-row>
      <b-col>
        <b-form @submit="onSubmit" @reset="onReset">
          <b-form-group
            label="Username:"
            label-for="txtUsername"
          >
            <b-form-input
              id="txtUsername"
              v-model="formdata.username"
              placeholder="Masukan username"
            />          
          </b-form-group>
          <b-form-group
            label="Password:"
            label-for="txtPassword"
          >
            <b-form-input
              id="txtPassword"
              v-model="formdata.password"
              type="password"
              placeholder="Masukan password"
            />          
          </b-form-group>
          <b-form-group
            label="Page:"            
          >
                   
          </b-form-group>
          <b-button type="submit" variant="primary">Submit</b-button>
          <b-button type="reset" variant="danger">Reset</b-button>
        </b-form>
      </b-col>
    </b-form-row>
    <div v-if="v$.formdata.username.$error">Name field has an error.</div>
  </FrontendLayout>
</template>
<script>
  import FrontendLayout from '@/views/layouts/FrontendLayout';
  import useVuelidate from '@vuelidate/core'
  import { required } from '@vuelidate/validators'
  export default {
    name: 'Login',
    setup() {
      return {
        v$: useVuelidate(),
      }
    },
    data() {
      return {
        formdata: {
          username: null,
          password: null,
          page: null,
        },
      }
    },
    validations() {
      return {
        formdata: {
          username: required,
          password: required,
          page: required,
        },
      }
    },
    methods: {
      async onSubmit(event) {
        event.preventDefault()
        const result = await this.v$.$touch()
        if (!result) {
          return
        }
        console.log(result);
      },
      onReset() {

      },
    },
    components: {
			FrontendLayout,
		},
  }
</script>