import { createRouter, createWebHistory } from "vue-router";
import Home from "../views/Home.vue";
import Partners from "../views/Partners.vue";
import Subpartners from "../views/Subpartners.vue";
import Entries from "../views/PartnerIDs.vue";
import Login from "../views/Login.vue";
import Logout from "../views/Logout.vue";
import NotFound from "../views/NotFound.vue";
import PartnerIDEdit from "@/views/PartnerIDEdit.vue";
import PartnerIDCreate from "@/views/PartnerIDCreate.vue";
import PartnerEdit from "@/views/PartnerEdit.vue";
import PartnerCreate from "@/views/PartnerCreate.vue";
import SubpartnerEdit from "@/views/SubpartnerEdit.vue";
import SubpartnerCreate from "@/views/SubpartnerCreate.vue";
import Numbers from "@/views/Numbers.vue";
import NumberCreate from "@/views/NumberCreate.vue";
import NumberEdit from "@/views/NumberEdit.vue";
import NumberShow from "@/views/NumberShow.vue";
import QRCodePage from "@/views/QRCodePage.vue";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/",
            component: Home,
            name: "home",
            meta: { public: true },
        },
        {
            path: "/partners",
            component: Partners,
            name: "partners",
        },
        {
            path: "/partners/edit/:id",
            component: PartnerEdit,
            name: "partner.edit",
        },
        {
            path: "/partners/create",
            component: PartnerCreate,
            name: "partner.create",
        },
        {
            path: "/subpartners",
            component: Subpartners,
            name: "subpartners",
        },
        {
            path: "/subpartners/edit/:id",
            component: SubpartnerEdit,
            name: "subpartner.edit",
        },
        {
            path: "/subpartners/create",
            component: SubpartnerCreate,
            name: "subpartner.create",
        },
        {
            path: "/numbers",
            component: Numbers,
            name: "numbers",
        },
        {
            path: "/numbers/create",
            component: NumberCreate,
            name: "number.create",
        },
        {
            path: "/numbers/edit/:id",
            component: NumberEdit,
            name: "number.edit",
        },
        {
            path: "/numbers/show/:id",
            component: NumberShow,
            name: "number.show",
        },
        {
            path: "/partner-ids",
            component: Entries,
            name: "partner.ids",
        },
        {
            path: "/partner-ids/edit/:id",
            component: PartnerIDEdit,
            name: "partner.id.edit",
        },
        {
            path: "/partner-ids/create",
            component: PartnerIDCreate,
            name: "partner.id.create",
        },
        {
            path: "/login",
            name: "auth.login",
            component: Login,
            meta: { public: true },
        },
        {
            path: "/logout",
            name: "auth.logout",
            component: Logout,
        },
        {
            path: "/generate-qr-code/:fullId",
            name: "qr.code",
            component: QRCodePage,
        },
        {
            path: "/404",
            name: "404",
            component: NotFound,
            meta: { public: true },
        },
        {
            path: "/:catchAll(.*)",
            redirect: "/404",
            meta: { public: true },
        },
    ],
});

export default router;
