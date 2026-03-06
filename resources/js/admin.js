import './bootstrap';
import { createApp } from 'vue';
import router from './router/adminrouter.js';
import vSelect from 'vue-select';
const app = createApp({});

import appVue from './admin.vue';
app.component('App', appVue);
app.component('v-select', vSelect);
app.use(router);
app.mount('#app');

