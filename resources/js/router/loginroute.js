import { createRouter, createWebHistory } from 'vue-router';


import Login from '../components/LoginPage.vue';
import page404 from '../components/page404.vue';
const routes = [
    {
        path: '/',
        name: 'login',
        component: Login,
    },
    {
        path: "/:pathMatch(.*)*",
        name: 'not-found',
        component: Login,
    },
];

const router = createRouter({
    history: createWebHistory("/"),
    routes,
});

export default router;
