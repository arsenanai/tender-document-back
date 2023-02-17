<template>
  <Header />
  <div class="container mt-4">
    <router-view />
  </div>
</template>

<script>
import Header from './components/Header.vue';

export default {
    name: 'App',
    components: {
      Header,
    },
}
</script>

<style>

</style>