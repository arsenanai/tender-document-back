import { createApp } from "vue";
import App from "./App.vue";
import router from "./router/index";
import './bootstrap';
import '../sass/app.scss'
import i18n from './translations';

const vueApp = createApp(App)
    .use(router)
    .use(i18n)
    .mount("#entries-app");