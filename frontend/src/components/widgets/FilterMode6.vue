<template>
  <div>
    <b-form-group label="Program Studi:">
      <b-form-select
        v-model="prodi_id"
        :options="daftar_prodi"				
      />
    </b-form-group>
      <!-- <v-select
        v-model="prodi"
        :items="daftar_prodi"
        item-text="text"
        item-value="id"
        label="PROGRAM STUDI"
        outlined
      />
      <v-select
        v-model="tahun_akademik"
        label="TAHUN AKADEMIK"
        :items="daftar_ta"
        outlined
      />
      <v-select
        v-model="semester_akademik"
        :items="daftar_semester"
        item-text="text"
        item-value="id"
        label="SEMESTER"
        outlined
      /> -->    
  </div>
</template>
<script>
  export default {
    name: "FilterMode6",
    created() {
      this.daftar_prodi = this.$store.getters["uiadmin/getDaftarProdi"](true)

      // this.daftar_ta = this.$store.getters["uiadmin/getDaftarTA"]
      // this.tahun_akademik = this.$store.getters["uiadmin/getTahunAkademik"]
      // this.daftar_semester = this.$store.getters["uiadmin/getDaftarSemester"]
      // this.semester_akademik = this.$store.getters[
      // 	"uiadmin/getSemesterAkademik"
      // ]
    },
    props: {
			prodi: {
				type: Number,
				default: 0,
			},			
		},
    data() {
      return {
        firstloading: true,
        daftar_prodi: [],
        prodi_id: this.prodi,

        daftar_ta: [],
        tahun_akademik: null,

        daftar_semester: [],
        semester_akademik: null,
      }
    },
    methods: {
      setFirstTimeLoading(bool) {
        this.firstloading = bool
      },
    },
    watch: {
      // tahun_akademik(val) {
      //   if (!this.firstloading) {          
      //     this.$emit("changeTahunAkademik", val)
      //   }
      // },
      prodi_id(val) {        
        if (!this.firstloading) {          
          this.$emit("changeProdi", val)
        }
      },
      // semester_akademik(val) {
      //   if (!this.firstloading) {
      //     this.$store.dispatch("uiadmin/updateSemesterAkademik", val)
      //     this.$emit("changeSemesterAkademik", val)
      //   }
      // },
    },
  }
</script>
