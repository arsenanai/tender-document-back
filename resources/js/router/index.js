import {createRouter, createWebHistory } from "vue-router";
import Home from "../views/Home.vue";
import Partners from "../views/Partners.vue";
import Subpartners from "../views/Subpartners.vue";
import Entries from "../views/PartnerIDs.vue";
import Login from "../views/Login.vue";
import Logout from "../views/Logout.vue";
import NotFound from '../views/NotFound.vue';
import PartnerIDEdit from '@/views/PartnerIDEdit.vue';
import PartnerIDCreate from '@/views/PartnerIDCreate.vue';
import PartnerEdit from '@/views/PartnerEdit.vue';
import PartnerCreate from '@/views/PartnerCreate.vue';
import SubpartnerEdit from '@/views/SubpartnerEdit.vue';
import SubpartnerCreate from '@/views/SubpartnerCreate.vue';

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
        path: '/partners',
        component: Partners,
    },
    {
        path: "/partners/edit/:id",
        component: PartnerEdit,
    },
    {
        path: "/partners/create",
        component: PartnerCreate,
    },
    {
        path: "/subpartners",
        component: Subpartners,
    },
    {
        path: "/subpartners/edit/:id",
        component: SubpartnerEdit,
    },
    {
        path: "/subpartners/create",
        component: SubpartnerCreate,
    },
    {
        path: "/partner-ids",
        component: Entries,
    },
    {
        path: "/partner-ids/edit/:id",
        component: PartnerIDEdit,
    },
    {
        path: "/partner-ids/create",
        component: PartnerIDCreate,
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