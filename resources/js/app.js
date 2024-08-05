import './bootstrap';
import '@placetopay/spartan-vue/style.css';
import { es, en } from '@placetopay/spartan-vue/locales';
import { createI18n } from 'vue-i18n';

import '../css/app.css';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
import { createApp, h } from 'vue';
import LaravelPermissionToVueJS from 'laravel-permission-to-vuejs'
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import i18n from '@/i18n.js';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// const i18n = createI18n({
//     locale: 'en',
//     fallbackLocale: 'en',
//     legacy: false,
//     messages: {
//         es,
//         en 
//     }
// });

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(i18n)
            .use(plugin)
            .use(ZiggyVue)
            .use(LaravelPermissionToVueJS)
            .mount(el);

    },
    progress: {
        color: '#4B5563',
    },
});
