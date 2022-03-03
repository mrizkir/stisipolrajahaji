<template>
  <KemahasiswaanLayout>
    <template v-slot:page-header>Data Aktivitas</template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item to="/kemahasiswaan">Kemahasiswaan</b-breadcrumb-item>
      <b-breadcrumb-item to="/kemahasiswaan/dataaktivitas">Data Aktivitas</b-breadcrumb-item>
      <b-breadcrumb-item active>Tambah</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      <b-form @submit.prevent="onSubmit" name="frmdata" id="frmdata" v-if="$store.getters['auth/can']('KEMAHASISWAAN-AKTIVITAS_STORE')">
        <b-card no-body class="card-primary card-outline">
          <template #header>
            <h3 class="card-title">Tambah Data Aktivitas</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" v-b-tooltip.hover title="Keluar" @click.stop="$router.push(url)">
                <b-icon icon="x-square"></b-icon>
              </button>
            </div>
          </template>
          <b-card-body>
            
          </b-card-body>
        </b-card>
      </b-form>
    </template>
  </KemahasiswaanLayout>
</template>
<script>
  import useVuelidate from '@vuelidate/core'
  import { required } from '@vuelidate/validators'  
  import KemahasiswaanLayout from '@/views/layouts/KemahasiswaanLayout'  
  export default {
    name: 'JenisAktivtasCreate',
    setup() {
      return {
        v$: useVuelidate(), 
        url: '/kemahasiswaan/dataaktivitas',
      }
    },
    validations() {
      return {
        formdata: {
          nama_aktivitas: {
            required,
          },
        },
      }
    },
    components: {
      KemahasiswaanLayout,
    },
  }
</script>
