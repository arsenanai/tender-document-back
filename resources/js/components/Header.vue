<template>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <router-link class="navbar-brand" to="/">
      <img :src="logo" alt="Logo" width="24" height="24" class="d-inline-block align-text-top">
      {{brand}}
    </router-link>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse d-lg-flex justify-content-lg-end" id="navbarNav">
      <ul class="navbar-nav my-2 my-lg-0">
        <li class="nav-item" v-if="authenticated()">
          <!--v-if="authenticated()==true"-->
          <router-link
            class="nav-link" active-class="active"
            to="/partners">
            Partners
          </router-link>
        </li>
        <li class="nav-item" v-if="authenticated()">
          <!--v-if="authenticated()==true"-->
          <router-link
            class="nav-link" active-class="active"
            to="/subpartners">
            <!--:to="{ name: 'partners.index' }">-->
            Subpartners
          </router-link>
        </li>
        <li class="nav-item" v-if="authenticated()">
          <!--v-if="authenticated()==true"-->
          <router-link
            class="nav-link" active-class="active"
            to="/entries">
            <!--:to="{ name: 'partners.index' }">-->
            Entries
          </router-link>
        </li>
        <li class="nav-item" v-if="!authenticated()">
          <!--v-if="authenticated()==true"-->
          <router-link
            class="nav-link" active-class="active"
            to="/login">
            <!--:to="{ name: 'partners.index' }">-->
            Login
          </router-link>
        </li>
        <li class="nav-item" v-if="authenticated()">
          <!--v-if="authenticated()==true"-->
          <router-link
            class="nav-link" active-class="active"
            to="/logout">
            <!--:to="{ name: 'partners.index' }">-->
            Logout
          </router-link>
        </li>
      </ul>
    </div>
  </div>
</nav>
</template>

<script>
import common from '../common';
export default {
  name: 'Header',
  mixins: [common],
  data() {
    return {
      logo: import.meta.env.VITE_LOGO_URL,
      brand: import.meta.env.VITE_BRAND_TITLE,
      toggleMenu: false,
    }
  },
}
</script>

<style>
</style>