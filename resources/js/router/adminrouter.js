import { createRouter, createWebHistory } from 'vue-router';

import AdminLayout from '../admin.vue';
import Home from '../components/admin/dashboard/HomeUser.vue';
import QuizIsian from '../components/admin/quiz/isian.vue';
const routes = [
    {
        path: '/',
        name: 'home',
        component: Home,
    },
    {
        path: '/quiz',
        name: 'admin_quiz',
        // component: AdminLayout,
        children: [
            {
                path: 'isian',
                name: 'resources_isian',
                component: QuizIsian,
            },
        ]
    },


];

const router = createRouter({
    history: createWebHistory("/"),
    routes,
});

router.afterEach((to) => {
    const exceptionPaths = ["/me", "/exam/:hash_sesi"];

    const isException = to.matched.some(route =>
        exceptionPaths.includes(route.path)
    );

    if (window.innerWidth <= 768 && !isException) {
        closeSidebar();
    }
});

export default router;
