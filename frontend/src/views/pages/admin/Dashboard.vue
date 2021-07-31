<template>
  <div>
    <b-container fluid>
      <b-row>
        <b-col>test</b-col>
      </b-row>
    </b-container>
  </div>
</template>
<script>
export default {
  name: "Dashboard",
  created() {
    this.TOKEN = this.$route.params.token;
    this.initialize();    
  },
  data: () => ({    
    TOKEN: null,
    dashboard: null,
  }),
  methods: {
    async initialize() {
      await this.$ajax
        .get("/auth/me", {
          headers: {
            Authorization: "Bearer " + this.TOKEN,
          },
        })
        .then(({ data }) => {          
          this.dashboard = data.page;          
          this.$store.dispatch("uiadmin/changeDashboard", this.dashboard);
        })
        .catch(() => {
          // console.log(error);
          // if (error.response.status == 401) {
          //   this.$router.push("/");
          // }
        }); 
			this.$store.dispatch("uiadmin/init");
    },
  }
}
</script>