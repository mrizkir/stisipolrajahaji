<template>
  <b-card
    no-body
    class="card-primary card-outline"
  >
    <template #header>
      <h3 class="card-title">Data Pengguna</h3>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" v-b-tooltip.hover title="Keluar" @click.stop="$router.push(urlfront)">
          <b-icon icon="x-square"></b-icon>
        </button>
      </div>
    </template>
    <b-card-body>
      <b-row>
        <b-col cols="2">
          <b-img-lazy v-bind="mainProps" :src="getImageUrl()" alt="foto pengguna"></b-img-lazy>
        </b-col>
        <b-col cols="10">
          <b-row>
            <b-col>
              <dl class="row">
                <dt class="col-sm-3">ID</dt>
                <dd class="col-sm-9">{{data_user.userid}}</dd>
              </dl>
            </b-col>
            <b-col>
              <dl class="row">
                <dt class="col-sm-4">Nama</dt>
                <dd class="col-sm-8">{{data_user.nama}}</dd>
              </dl>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <dl class="row">
                <dt class="col-sm-3">Username</dt>
                <dd class="col-sm-9">{{data_user.username}}</dd>
              </dl>
            </b-col>  
            <b-col>
              <dl class="row">
                <dt class="col-sm-4">Email</dt>
                <dd class="col-sm-8">
                  {{data_user.email}}
                </dd>
              </dl>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <dl class="row">
                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">              
                  <b-badge :variant="data_user.active == 1 ? 'primary' : 'secondary'">{{ data_user.active == 1 ? 'aktif' : 'tidak aktif' }}</b-badge>
                </dd>
              </dl>
            </b-col>  
            <b-col>
              <dl class="row">
                <dt class="col-sm-4">Created</dt>
                <dd class="col-sm-8">
                  {{$date(data_user.date_added).format("DD.MM.YYYY HH:mm")}}
                </dd>
              </dl>
            </b-col>
          </b-row>
        </b-col>
      </b-row>
    </b-card-body>
  </b-card>
</template>
<script>
  export default {
    name: 'user-detail',
    props: {
      urlfront: {
        type: String,
        required: true,
      }, 
      urlbackend: {
        type: String,
        default: null,
      },
      data_user: {
        type: Object,
        required: true,
      }
    },
    data: () => ({
      mainProps: {
        center: true,
        fluidGrow: true,
        blank: true,
        blankColor: '#bbb',
      }
    }),
    methods: {
      getImageUrl() {
        var detail = this.data_user.detail
        if (typeof detail !== 'undefined' && detail !== null) {
          return `${this.$backend.storageURL}/${this.data_user.detail.foto}`
        }
      }
    }
  }
</script>