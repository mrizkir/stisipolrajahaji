<template>
  <div class="ml-2 mr-2">
    <b-form-group label="PROGRAM STUDI:">
      <b-form-select v-model="prodi_id" :options="daftar_prodi" />
    </b-form-group>
    <b-form-group label="TAHUN AKADEMIK:">
      <b-form-select v-model="ta" :options="daftar_ta" />
    </b-form-group>
    <b-form-group label="SEMESTER:">
      <b-form-select v-model="semester" :options="daftar_semester" />
    </b-form-group>      
  </div>
</template>
<script>
  export default {
    name: 'FilterMode6',
    created() {
      this.daftar_prodi = this.$store.getters['uiadmin/getDaftarProdi'](true)
      this.daftar_ta = this.$store.getters['uiadmin/getDaftarTA']
      this.daftar_semester = this.$store.getters['uiadmin/getDaftarSemester']      
    },
    props: {
			prodi: {
				type: Number,
				default: 0,
        required: true,
			},
			tahun_akademik: {
				type: Number,	
        required: true,
			},
			semester_akademik: {
				type: Number,	
        required: true,
			},
		},
    data() {
      return {
        firstloading: true,
        daftar_prodi: [],
        prodi_id: this.prodi,

        daftar_ta: [],
        ta: this.tahun_akademik,

        daftar_semester: [],
        semester: this.semester_akademik,
      }
    },
    methods: {
      setFirstTimeLoading(bool) {
        this.firstloading = bool
      },
    },
    watch: {
      prodi_id(val) {  
        if (!this.firstloading) {    
          this.$emit('changeProdi', val)
        }
      },
      ta(val) {
        if (!this.firstloading) {    
          this.$emit('changeTahunAkademik', val)
        }
      },
      semester(val) {
        if (!this.firstloading) {    
          this.$emit('changeSemesterAkademik', val)
        }
      },
    },
  }
</script>
