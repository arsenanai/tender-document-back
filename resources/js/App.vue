<template>
  <Header class="flex-grow-0" style="z-index:2;" @locale-changed="changeLocale"/>
  <div class="w-100 position-relative" style="height: calc(100% - 56px)"
  :class="{'bg-black': $route.path === '/'}">
    <img v-if="$route.path === '/'" id="background" :src="getBackground()"
    class="position-absolute bottom-0 start-0 w-100 h-100 object-fit-cover opacity-75" style="z-index:0;"/>
    <div class="position-absolute top-0 start-0 w-100 h-100">
      <br>
      <div class="container" style="z-index:3;"
        :class="{
          'bg-white rounded pt-2': filterRoutes(),
        }">
        <router-view v-slot="{ Component, route }">
          <transition :name="route.meta.transition || 'fade'">
            <component :is="Component" :key="route.path"/>
          </transition>
        </router-view>
      </div>
    </div>
    <div class="position-absolute bottom-0 start-0 w-100 d-flex flex-column gap-2 p-4">
      <a id="email" class="fw-semibold text-white" style="text-decoration:none" :href="`mailto:${email}`">&#128231; {{ email }}</a>
      <a id="phone" class="fw-semibold text-white" style="text-decoration:none" :href="`tel:${phone}`">&#128222; {{ phone }}</a>
    </div>
  </div>
</template>

<script>
import Header from './components/Header.vue';
import common from '@/mixins/common';

export default {
  name: 'App',
  mixins:[common],
  components: {
    Header,
  },
  data() {
    return {
      email: import.meta.env.VITE_COMPANY_EMAIL,
      phone: import.meta.env.VITE_COMPANY_PHONE,
    };
  },
  created() {
    this.fetchUser();
    console.log('router', this.$route.path);
  },
  methods: {
    filterRoutes() {
      return !(['/login','/'].includes(this.$route.path)
      || this.$route.path.includes('/edit')
      || this.$route.path.includes('/create'));
    },
    getBackground() {
      return '/background.jpg';
    },
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