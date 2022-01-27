<template>
  <b-navbar toggleable="lg" type="dark" variant="info">
    <b-navbar-brand href="#">PortalEkampusV2</b-navbar-brand>
    <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

    <b-collapse id="nav-collapse" is-nav>
      <b-navbar-nav>
        <b-nav-item href="/">Home</b-nav-item>
        <b-nav-item href="/berita">Berita</b-nav-item>
        <b-nav-item href="/pendaftaran">Pendaftaran</b-nav-item>
      </b-navbar-nav>

      <!-- geser ke kanan -->
      <b-navbar-nav class="ml-auto">
         <b-navbar-nav>
           <b-nav-item href="/login">Login</b-nav-item>
         </b-navbar-nav>
      </b-navbar-nav>
    </b-collapse>
  </b-navbar>
</template>