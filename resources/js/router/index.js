import {createRouter, createWebHistory } from "vue-router";
import Home from "../views/Home.vue";
import Partners from "../views/Partners.vue"
import Subpartners from "../views/Subpartners.vue"
import Entries from "../views/Entries.vue"

const routes = [
    {
        path: "/",
        name: "home",
        component: Home,
    },
    {
        path: "/partners",
        name: "partners",
        component: Partners,
    },
    {
        path: "/subpartners",
        name: "subpartners",
        component: Subpartners,
    },
    {
        path: "/entries",
        name: "entries",
        component: Entries,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;