(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d226785"],{e994:function(e,t,n){"use strict";n.r(t);var a=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("PenggunaSistemLayout",{scopedSlots:e._u([{key:"page-header",fn:function(){return[e._v("Pengguna Manajemen")]},proxy:!0},{key:"page-breadcrumb",fn:function(){return[n("b-breadcrumb-item",{attrs:{to:"/sistem-pengguna"}},[e._v(" Pengguna Sistem ")]),n("b-breadcrumb-item",{attrs:{to:"/sistem-pengguna/manajemen"}},[e._v(" Pengguna Manajemen ")]),n("b-breadcrumb-item",{attrs:{active:""}},[e._v("Detail")])]},proxy:!0},{key:"page-content",fn:function(){return[e.$store.getters["auth/can"]("SYSTEM-USERS-AKADEMIK_SHOW")?n("b-container",{attrs:{fluid:""}},[n("b-row",[n("b-col",[n("DetailUser",{attrs:{data_user:e.data_user,urlfront:"/sistem-pengguna/manajemen"}})],1)],1),n("b-row",[n("b-col",[e.data_user.hasOwnProperty("userid")?n("UserPermissions",{attrs:{user_id:e.user_id,urlfront:"/sistem-pengguna/manajemen"}}):e._e()],1)],1)],1):e._e()]},proxy:!0}])})},r=[],s=n("1da1"),u=(n("96cf"),n("f439")),i=n("b715"),o=n("f45d"),c={name:"PenggunaManajemenDetail",created:function(){this.user_id=this.$route.params.user_id,this.initialize()},data:function(){return{user_id:null,data_user:{}}},methods:{initialize:function(){var e=this;return Object(s["a"])(regeneratorRuntime.mark((function t(){var n;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:return n="/system/usersmanajemen/"+e.user_id,t.next=3,e.$ajax.get(n,{headers:{Authorization:e.$store.getters["auth/Token"]}}).then((function(t){var n=t.data;e.data_user=n.user}));case 3:case"end":return t.stop()}}),t)})))()}},components:{PenggunaSistemLayout:u["a"],DetailUser:i["a"],UserPermissions:o["a"]}},m=c,d=n("2877"),g=Object(d["a"])(m,a,r,!1,null,null,null);t["default"]=g.exports}}]);