<template>
<nav class="navbar bg-body-tertiary">
  <div class="container">
    <router-link class="navbar-brand" to="/">
      <img :src="logo" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
      {{brand}}
    </router-link>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse float-right" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <!--v-if="authenticated()==true"-->
          <router-link
            class="nav-link" active-class="active"
            to="/">
            Partners
          </router-link>
        </li>
        <li class="nav-item">
          <!--v-if="authenticated()==true"-->
          <router-link
            class="nav-link" active-class="active"
            to="/">
            <!--:to="{ name: 'partners.index' }">-->
            Partners
          </router-link>
        </li>
      </ul>
    </div>
  </div>
</nav>
</template>

<script>
export default {
  name: 'Header',
  data() {
    return {
      logo: import.meta.env.VITE_LOGO_URL,
      brand: import.meta.env.VITE_BRAND_TITLE,
      toggleMenu: false,
    }
  }
}
</script>

<style>
</style>