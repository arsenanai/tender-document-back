import { createApp } from "vue/dist/vue.esm-bundler";
import App from "./App.vue";
import router from "./router/index";
import './bootstrap';
import '../sass/app.scss'
import i18n from './translations';

const app = createApp(App)
    .use(router)
    .use(i18n)
    .mount("#entries-app");

function titleCase(str) {
    return str.toLowerCase().replace(/\b\w/g, s => s.toUpperCase());
}

function formatName(str) {
    return titleCase(str.replaceAll('.', ' '));
}

router.beforeEach((to, from, next) => {
    document.title = `${import.meta.env.VITE_APP_NAME} - ${formatName(to.name)}`;
    if (to.matched.some(record => record.meta.public)
    || localStorage.getItem('entries_user')!==null) {
    next();
    } else {
    next({ name: 'auth.login' });
    }
});