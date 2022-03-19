<template>
  <FeederLayout :showrightsidebar="false">
    <template v-slot:page-header>
      FEEDER
    </template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item active>FEEDER</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      <b-container fluid>
        <b-row>
          <b-col>
            <b-card no-body class="card-primary card-outline">
              <template #header>
                <h3 class="card-title">Generate Token</h3>
              </template>
              <b-card-body>
                Generate token sekaligus Tes koneksi ke server Feeder. Alamat server, username, dan password diatur di file .env
                <b-alert :show="message_test !== null">
                  <p>{{ message_test }}</p>
                  <p>
                    {{ token }}
                  </p>
                </b-alert>              
              </b-card-body>
              <template #footer>
                <b-button variant="outline-primary" @click.stop="koneksi" :disabled="btnLoading">
                  Get Token
                </b-button>              
              </template>
            </b-card>
          </b-col>
        </b-row>
      </b-container>
    </template>
  </FeederLayout>
</template>
<script>
  import FeederLayout from '@/views/layouts/FeederLayout'
  export default {
    name: 'PDDIKTI',
    data: () => ({
      btnLoading: false,
      message_test: null,
      token: null,
    }),
    methods: {
      async koneksi() {
        this.btnLoading = true
        await this.$ajax
          .get('/feeder/teskoneksi', {
            headers: {
              Authorization: this.$store.getters['auth/Token'],
            }
          })
          .then(({ data }) => {
            this.message_test = data.error_desc
            this.token = data.data.token
            this.btnLoading = false
            this.$store.dispatch('uiadmin/updateFeederToken', this.token)
          })
          .catch(() => {
            this.message_test = null
            this.token = null
            this.btnLoading = false
            this.$store.dispatch('uiadmin/updateFeederToken', null)
          })
      },
    },
    components: {
      FeederLayout,
    },
  }
</script>
