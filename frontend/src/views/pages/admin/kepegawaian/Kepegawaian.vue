<template>
  <KepegawaianLayout :showrightsidebar="false">
    <template v-slot:page-header>
      Kepegawaian
    </template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item active>Kepegawaian</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      <b-container
        fluid
        v-if="$store.getters['auth/can']('KEPEGAWAIAN-DOSEN_BROWSE') && $store.getters['auth/DefaultRole'] == 'dosen'"
      >
        <b-row>
          <b-col>
            <RingkasanProfil>

            </RingkasanProfil>
          </b-col>
          <b-col>
            test
          </b-col>
        </b-row>
      </b-container>
    </template>
  </KepegawaianLayout>
</template>
<script>
  import KepegawaianLayout from '@/views/layouts/KepegawaianLayout'  
  import RingkasanProfil from '@/components/pengguna/dosen-ringkasan-profil'
  export default {
    name: 'Kepegawaian',
    created() {},
    components: {
      KepegawaianLayout,
      RingkasanProfil
    },
  }
</script>