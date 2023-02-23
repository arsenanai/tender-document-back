<template>
  <Header />
  <div class="container mt-4">
    <router-view v-slot="{ Component, route }">
      <transition :name="route.meta.transition || 'fade'">
        <component :is="Component" :key="route.path"/>
      </transition>
    </router-view>
  </div>
</template>

<script>
import Header from './components/Header.vue';
import common from './common';

export default {
    name: 'App',
    mixins:[common],
    components: {
      Header,
    },
    created() {
      var userData = this.getAuthenticatedUser();
      if(userData !== null) {
        this.user = userData;
      }
    }
}
</script>

<style scoped>
.fade-enter-active {
  transition: all .15s ease-out .15s;
}
.fade-leave-active {
  transition: all .15s ease-in 0s;
}
.fade-enter-from, .fade-leave-to{
  opacity: 0;
  margin-top: 100px;
}
.fade-enter-to, .fade-leave-from {
  opacity: 1;
  margin-top: 0px;
}
</style>