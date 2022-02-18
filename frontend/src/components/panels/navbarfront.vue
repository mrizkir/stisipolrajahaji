<template>
  <b-navbar toggleable="lg" type="dark" variant="info">
    <b-navbar-brand to="#">PortalEkampusV2</b-navbar-brand>
    <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

    <b-collapse id="nav-collapse" is-nav>
      <b-navbar-nav>
        <b-nav-item to="/">Home</b-nav-item>
        <b-nav-item to="/berita">Berita</b-nav-item>
        <b-nav-item to="/pendaftaran">Pendaftaran</b-nav-item>
      </b-navbar-nav>

      <!-- geser ke kanan -->
      <b-navbar-nav class="ml-auto">
         <b-navbar-nav>
           <b-nav-item to="/login">Login</b-nav-item>
         </b-navbar-nav>
      </b-navbar-nav>
    </b-collapse>
  </b-navbar>
</template>