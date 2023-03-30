<template>
  <PenggunaSistemLayout>
    <template v-slot:page-header>Pengguna Mahasiswa</template>
    <template v-slot:page-breadcrumb>
      <b-breadcrumb-item to="/sistem-pengguna">
        Pengguna Sistem
      </b-breadcrumb-item>
      <b-breadcrumb-item to="/sistem-pengguna/mahasiswa">
        Pengguna Mahasiswa
      </b-breadcrumb-item>
      <b-breadcrumb-item active>Tambah</b-breadcrumb-item>
    </template>
    <template v-slot:page-content>
      <b-container
        fluid
        v-if="$store.getters['auth/can']('SYSTEM-USERS-MAHASISWA_STORE')"
      >
        <b-row>
          <b-col>
            <b-form @submit.prevent="onSubmit" name="frmdata" id="frmdata">
              <b-card
                no-body
                class="card-primary card-outline"
              >
                <template #header>
                  <h3 class="card-title">Tambah Pengguna</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" v-b-tooltip.hover title="Keluar" @click.stop="$router.push('/sistem-pengguna/mahasiswa')">
                      <b-icon icon="x-square"></b-icon>
                    </button>
                  </div>
                </template>
              </b-card>
            </b-form>
          </b-col>
        </b-row>
      </b-container>
    </template>
  </PenggunaSistemLayout>
</template>
<script>
  import PenggunaSistemLayout from '@/views/layouts/PenggunaSistemLayout'  
  export default {
    name: 'PenggunaMahasiswaCreate',
    components: {
      PenggunaSistemLayout,      
    },
  }
</script>