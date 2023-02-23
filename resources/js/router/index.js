import {createRouter, createWebHistory } from "vue-router";
import Home from "../views/Home.vue";
import Partners from "../views/Partners.vue";
import Subpartners from "../views/Subpartners.vue";
import Entries from "../views/Entries.vue";
import Login from "../views/Login.vue";
import Logout from "../views/Logout.vue";
import NotFound from '../views/NotFound.vue';

const routes = [
    {
        path: "/",
        component: Home,
        name: 'home',
        meta: {
            public: true
        } 
    },
    {
        path: "/partners",
        name: 'partners.index',
        component: Partners,
    },
    {
        path: "/subpartners",
        component: Subpartners,
    },
    {
        path: "/entries",
        component: Entries,
    },
    {
        path: '/login',
        name: 'auth.login',
        component: Login,
        meta: {
            public: true
        }
    },
    {
        path: '/logout',
        name: 'auth.logout',
        component: Logout,
    },
    { path: '/404', name: '404', component: NotFound, meta: {public: true} },
    {
        path: "/:catchAll(.*)",
        redirect: '/404',
        meta: {
            public: true
        } 
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.public)
    || localStorage.getItem('entries_user')!==null) {
    next();
  } else {
    next({ name: 'auth.login' });
  }
});

export default router;