<template>
  <div>
    <b-navbar toggleable="lg" type="dark" variant="dark" class="mb-2">
      <b-navbar-brand href="#">Portal Ekampus v3.0</b-navbar-brand>
      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
      <b-collapse id="nav-collapse" is-nav>
        <!-- Right aligned nav items -->
        <b-navbar-nav class="ml-auto">
          <b-nav-form>
            <b-form-input size="sm" class="mr-sm-2" placeholder="Search"></b-form-input>
            <b-button size="sm" class="my-2 my-sm-0" type="submit">Search</b-button>
          </b-nav-form>
          <b-nav-item-dropdown right>
            <!-- Using 'button-content' slot -->
            <template #button-content>
              <em>User</em>
            </template>
            <b-dropdown-item href="#">Profile</b-dropdown-item>
            <b-dropdown-item href="#">Sign Out</b-dropdown-item>
          </b-nav-item-dropdown>
        </b-navbar-nav>
      </b-collapse>
    </b-navbar>
    <sa-dashboard v-if="dashboard == 'sa'" />
    <d-dashboard v-if="dashboard == 'd'" />
  </div>
</template>
<script>
import SADashboard from "@/components/sa/SADashboard";
import DDashboard from "@/components/sa/DDashboard";
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
        .catch((error) => {          
          if (error.status == 401) {
            this.$router.push("/");
          }
        }); 
			this.$store.dispatch("uiadmin/init");
    },
  },
  components: {
    "sa-dashboard": SADashboard,    
    "d-dashboard": DDashboard,    
  }
}
</script>