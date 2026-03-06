import { createApp } from 'vue';
import router from './router/loginroute.js';

const app = createApp({});

import appVue from './login.vue';
app.component('App', appVue);
app.use(router);
app.mount('#app');

