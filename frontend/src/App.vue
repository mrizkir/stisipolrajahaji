<template>
  <b-overlay
    :show="isLoading"
    opacity="0.81"
    rounded="sm"
    spinner-variant="primary"
    spinner-type="grow"
    spinner-small
  >
    <router-view />
  </b-overlay>
</template>
<script>
  export default {
    name: 'PortalEkampus2',
    created() {
      this.$ajax.interceptors.request.use(
        (config) => {
          this.setLoading(true)
          return config
        },
        (error) => {
          this.setLoading(false)
          return Promise.reject(error)
        }
      )
      this.$ajax.interceptors.response.use(
        (response) => {
          let data = response.data
          switch (data.pid) {
            case 'store':
            case 'update':
            case 'destroy':
            case 'resendemail':
              this.page_message = data.message
              this.$bvToast.toast(this.page_message, {
                title: '200: Success',
                variant: 'success',
                autoHideDelay: 5000,
                appendToast: false,
                solid: true,
                bodyClass: 'text-break',
              })
              break
          }
          this.setLoading(false)
          return response
        },
        (error) => {
          const {
            config,
            response: { data, status },
          } = error
          switch (status) {
            case 401:
              if (data.page != 'login') {
                this.$store.dispatch('auth/logout')
                this.$store.dispatch('uifront/reinit')
                this.$store.dispatch('uiadmin/reinit')
                this.page_message =
                  '(' +
                  status +
                  ': ' +
                  data.error +
                  ') Token telah expire mohon login kembali'

                this.$bvToast.toast('Token telah expire mohon login kembali', {
                  title: status + ': ' + data.error,
                  variant: 'danger',
                  autoHideDelay: 5000,
                  appendToast: false,
                  solid: true,
                  bodyClass: 'text-break',
                })
              }
              break
            case 403:
              this.page_message =
                '(' +
                status +
                ': Forbidden) ' +
                data.message +
                ' to access resource [' +
                config.baseURL +
                config.url +
                ']'
              this.$bvToast.toast(
                data.message +
                  ' to access resource [' +
                  config.baseURL +
                  config.url +
                  ']',
                {
                  title: status + ': Forbidden',
                  variant: 'warning',
                  autoHideDelay: 5000,
                  appendToast: false,
                  solid: true,
                  bodyClass: 'text-break',
                }
              )
              break
            case 404:
              this.page_message =
                '(' +
                status +
                ': ' +
                data.error +
                ') Mohon untuk dicek url route (' +
                config.baseURL +
                config.url +
                ') apakah tersedia'

              this.$bvToast.toast(
                'Mohon untuk dicek url route (' +
                  config.baseURL +
                  config.url +
                  ') apakah tersedia',
                {
                  title: status + ': ' + data.error,
                  variant: 'warning',
                  autoHideDelay: 5000,
                  appendToast: false,
                  solid: true,
                  bodyClass: 'text-break',
                }
              )
              break
            case 405:
              this.page_message =
                '(' +
                status +
                ': ' +
                data.exception +
                ') Mohon untuk dicek HTTP METHOD '
              this.$bvToast.toast(
                'Mohon untuk dicek HTTP METHOD dari url (' +
                  config.baseURL +
                  config.url +
                  ')',
                {
                  title: status + ': Method Not Allowed',
                  variant: 'danger',
                  autoHideDelay: 5000,
                  appendToast: false,
                  solid: true,
                  bodyClass: 'text-break',
                }
              )
              break
            case 422:
              var error_messages = []
              for (var p in data) {
                var messages = []
                var error_list = data[p]
                if (Array.isArray(error_list)) {
                  for (var i = 0; i < error_list.length; i++) {
                    this.$bvToast.toast(error_list[i], {
                      title: status + ': Unprocessible Entity',
                      variant: 'warning',
                      autoHideDelay: 5000,
                      appendToast: false,
                      solid: true,
                      bodyClass: 'text-break',
                    })
                    messages.push({
                      message: error_list[i],
                    })
                  }
                  error_messages.push({
                    field: p,
                    error: messages,
                  })
                } else {
                  error_messages.push({
                    field: p,
                    error: [
                      {
                        message: data[p],
                      },
                    ],
                  })
                  this.$bvToast.toast(data[p], {
                    title: status + ': Unprocessible Entity',
                    variant: 'warning',
                    autoHideDelay: 5000,
                    appendToast: false,
                    solid: true,
                    bodyClass: 'text-break',
                  })
                }
              }
              this.page_form_error_message = error_messages
              this.page_message = '(' + status + ': Unprocessible Entity)'
              break
            case 500:
              this.page_message =
                '(' + status + ' (internal server eror): ' + data.message

              this.$bvToast.toast(data.message, {
                title: status + ': internal server eror',
                variant: 'danger',
                autoHideDelay: 5000,
                appendToast: false,
                solid: true,
                bodyClass: 'text-break',
              })
              break
          }
          this.setLoading(false)
          console.clear()
          return Promise.reject(error)
        }
      )
    },
    data() {
      return {
        refCount: 0,
        isLoading: false,
        page_message: '',
        page_form_error_message: {},
      }
    },
    methods: {
      setLoading(isLoading) {
        if (isLoading) {
          this.refCount++
          this.isLoading = true
        } else if (this.refCount > 0) {
          this.refCount--
          this.isLoading = this.refCount > 0
        }
      },
    },
  }
</script>
