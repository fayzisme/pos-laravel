import './bootstrap';
import '../css/app.css';

import { createVuestic, createIconsConfig } from "vuestic-ui";
import "vuestic-ui/css";
import "material-design-icons-iconfont/dist/material-design-icons.min.css";

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs-fix-scroll/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

import helpers from './helpers';
import Swal from 'sweetalert2';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props),  provide: {
            helpers: helpers,
            Swal: Swal
          } 
        })
        .use(plugin)
        .use(ZiggyVue, Ziggy)
        .use(createVuestic({config: {
            icons: createIconsConfig({
                aliases: [
                  {
                    name: "bell",
                    color: "#FFD43A",
                    to: "fa4-bell",
                  },
                  {
                    name: "ru",
                    to: "flag-icon-ru small",
                  },
                ],
                fonts: [
                  {
                    name: "fa4-{iconName}",
                    resolve: ({ iconName }) => ({ class: `fa fa-${iconName}` }),
                  },
                  {
                    name: "flag-icon-{countryCode} {flagSize}",
                    resolve: ({ countryCode, flagSize }) => ({
                      class: `flag-icon flag-icon-${countryCode} flag-icon-${flagSize}`,
                    }),
                  },
                ],
            }),
            colors: {
              variables: {
                // Default colors
                primary: "#23e066",
                secondary: "#002c85",
                success: "#40e583",
                info: "#2c82e0",
                danger: "#e34b4a",
                warning: "#ffc200",
                gray: "#babfc2",
                dark: "#34495e",
    
                // Custom colors
                yourCustomColor: "#d0f55d",
              },
            },
          },
        }));

        app.mount(el);

        return app;
    },
    progress: {
        color: '#4B5563',
    },
});
