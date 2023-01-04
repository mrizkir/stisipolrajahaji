<template>
  <b-card
    no-body
    class="card-primary card-outline"
  >
    <template #header>
      <h3 class="card-title">Ringkasan Profil</h3>        
    </template>
    <b-card-body>
      <b-row class="mb-4">
        <b-col>
          <b-img center thumbnail :src="photoUser" :alt="$store.getters['auth/DetailUser']('username')" height="100px"></b-img>
        </b-col>
      </b-row>
      <dl class="row">
        <dt class="col-sm-3">Nama</dt>
        <dd class="col-sm-9">{{ ringkasan.nama }}</dd>
      </dl>
      <dl class="row">
        <dt class="col-sm-3">NIDN/NITK</dt>
        <dd class="col-sm-9">{{ ringkasan.nidn }}</dd>
      </dl>
      <dl class="row">
        <dt class="col-sm-3">NIP</dt>
        <dd class="col-sm-9">{{ ringkasan.nip }}</dd>
      </dl>
      <dl class="row">
        <dt class="col-sm-3">Prodi</dt>
        <dd class="col-sm-9">{{ ringkasan.nip }}</dd>
      </dl>
    </b-card-body>
  </b-card>
</template>
<script>
  export default {
    name: 'Kepegawaian',
    created() {
      console.log("test");
    },
    data: () => ({
      ringkasan: {
        nama: '-',
        nidn: '-',
        nip: '-',
      },
    }),
    computed: {
      photoUser() {
        let img = this.$store.getters['auth/DetailUser']('foto')
        var photo
        if (img == '') {
          photo =
            this.$backend.storageURL + '/storage/images/users/no_photo.png'
        } else {
          photo = this.$backend.storageURL + '/' + img
        }
        return photo
      },
    },
  }
</script>