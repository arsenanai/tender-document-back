import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import path from "path";
const purgecss = require("@fullhuman/postcss-purgecss");
import VueI18nPlugin from "@intlify/unplugin-vue-i18n/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                //'resources/css/app.css',
                "resources/js/app.js",
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    // The Vue plugin will re-write asset URLs, when referenced
                    // in Single File Components, to point to the Laravel web
                    // server. Setting this to `null` allows the Laravel plugin
                    // to instead re-write asset URLs to point to the Vite
                    // server instead.
                    base: null,

                    // The Vue plugin will parse absolute URLs and treat them
                    // as absolute paths to files on disk. Setting this to
                    // `false` will leave absolute URLs un-touched so they can
                    // reference assets in the public directory as expected.
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: [
            {
                find: "~bootstrap",
                replacement: "node_modules/bootstrap",
            },
            // '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            {
                find: "vue-i18n",
                replacement: "vue-i18n/dist/vue-i18n.cjs.js",
            },
            {
                find: "vue",
                replacement: "vue/dist/vue.esm-bundler.js",
            },
        ],
    },
    css: {
        postcss: {
            plugins: [
                purgecss({
                    content: [
                        "./resources/views/**/*.blade.php",
                        "./resources/js/**/*.vue",
                    ],
                    safelist: ["show"],
                }),
            ],
        },
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes("node_modules")) {
                        return id
                            .toString()
                            .split("node_modules/")[1]
                            .split("/")[0]
                            .toString();
                    }
                },
            },
        },
    },
});
