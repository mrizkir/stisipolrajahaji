<template>
  <v-app>
    <v-app-bar app elevation="0" color="#F4F5FA">
      <!-- -->
    </v-app-bar>
    <v-navigation-drawer app light="#F4F5FA">
      
    </v-navigation-drawer>
    <v-main>
      <v-container fluid>
        <router-view />
      </v-container>
    </v-main>
    <v-footer app>
      Footer
    </v-footer>
  </v-app>
</template>