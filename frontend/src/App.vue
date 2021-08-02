<template>
  <div>
    <router-view />
  </div>
</template>
<script>
export default {
  name: "PortlalEkampus",
  created() {
    this.$ajax.interceptors.request.use(
      config => {
        this.setLoading(true);
        return config;
      },
      error => {
        this.setLoading(false);
        return Promise.reject(error);
      }
    );
    this.$ajax.interceptors.response.use(
      response => {
        let data = response.data;
        switch (data.pid) {
						case "store":
						case "update":
						case "destroy":
						case "resendemail":							
              this.$bvToast.toast(data.message, {
                title: "Proses ini sukses dilaksanakan !!! ",
                variant: "success",
                solid: true
              });
							break;
					}
					this.setLoading(false);
					return response;
      },
      error => {
        const {
          config,
          response: { data, status },
        } = error;
        switch (status) {
          case 401:
            if (data.page != "login") {
              this.$store.dispatch("auth/logout");
              this.$store.dispatch("uifront/reinit");
              this.$store.dispatch("uiadmin/reinit");
              this.$bvToast.toast("Token telah expire mohon login kembali", {
                title: "Token !!!",
                variant: "warning",
                solid: true
              });
            }
            break;
          case 403:
            this.page_message =
              "(" +
              status +
              ": Forbidden) " +
              data.message +
              " to access resource [" +
              config.baseURL +
              config.url +
              "]";
            this.$bvToast.toast(this.page_message, {
              title: "Error !!!",
              variant: "warning",
              solid: true
            });
            break;          
        }
        this.setLoading(false);
        return Promise.reject({
          error: error,
          status: status,
        });
      }
    );
  },
  data: () => ({
    // ajaxloading
    refCount: 0,
    isLoading: false,
    //message
    page_message: "",
  }),
  methods: {
    setLoading(isLoading) {
      if (isLoading) {
        this.refCount++;
        this.isLoading = true;
      } else if (this.refCount > 0) {
        this.refCount--;
        this.isLoading = this.refCount > 0;
      }
    },
  },
};
</script>
