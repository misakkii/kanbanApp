require('./bootstrap');

// Import modules...
import Vue from 'vue';
import VueCompositionApi from '@vue/composition-api'
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue';
import PortalVue from 'portal-vue';
import vuetify from './plugins/vuetify';
import store from './store'
import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';
import axios from 'axios';
import VueAxios from 'vue-axios';

Vue.use(VueCompositionApi);
Vue.use(VueAxios, axios);
Vue.mixin({ methods: { route } });
Vue.use(InertiaPlugin);
Vue.use(PortalVue);
Vue.component('VueCtkDateTimePicker', VueCtkDateTimePicker);

const app = document.getElementById('app');

new Vue({
    vuetify,
    store,
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
}).$mount(app);
