import { createRouter, createWebHistory } from 'vue-router';

import AdminLayout from '../admin.vue';
import Home from '../components/admin/dashboard/HomeUser.vue';
import ResourcesPeraturan from '../components/admin/resouces/peraturan.vue';
import ResourcesVideos from '../components/admin/resouces/videos.vue';

import QuizdanUjianKategori from '../components/admin/UjiandanQuiz/kategori.vue';
import QuizdanUjianModul from '../components/admin/UjiandanQuiz/modul_ujian.vue';
import QuizdanUjianSoal from '../components/admin/UjiandanQuiz/soal_ujian.vue';
import QuizdanUjianEvent from '../components/admin/UjiandanQuiz/event_ujian.vue';

import JadwakAktif from '../components/user/ujian/jadwal_aktif_table.vue';
import Exampage from '../components/user/ujian/exampage.vue';
import ResourcesPeraturanDetail from '../components/admin/resouces/peraturan_detail.vue';
import SettingUser from '../components/admin/settings/users.vue';
import ProfilePage from '../components/admin/settings/profilePage.vue';
import Export_laporan from '../components/admin/UjiandanQuiz/export_laporan.vue';
const routes = [
    {
        path: '/',
        name: 'home',
        component: Home,
    },
    {
        path: '/me',
        name: "profile",
        component: ProfilePage
    },
    {
        path: '/resources',
        name: 'admin_resources',
        // component: AdminLayout,
        children: [
            {
                path: 'peraturan',
                name: 'resources_peraturan',
                component: ResourcesPeraturan,
            },
            {
                path: 'peraturan/:id',
                name: 'resources_peraturan_detail',
                component: ResourcesPeraturanDetail,
            },
            {
                path: 'videos',
                name: 'resources_videos',
                component: ResourcesVideos,
            },
        ]
    },
    {
        path: '/course',
        name: 'admin_course',
        // component: AdminLayout,
        children: [
            {
                path: 'kategori',
                name: 'course_kategori',
                component: QuizdanUjianKategori,
            },
            {
                path: 'modul-ujian',
                name: 'soal_modul',
                component: QuizdanUjianModul,
            },
            {
                path: 'soal',
                name: 'soal_soal',
                component: QuizdanUjianSoal,
            },
            {
                path: 'event',
                name: 'soal_event',
                component: QuizdanUjianEvent,
            },
            {
                path: 'export',
                name: 'admin_course_export',
                component: Export_laporan,
            },
            {
                path: 'jadwal-aktif/:c?',
                name: 'course_jadwal_aktif',
                component: JadwakAktif,
            },
            {
                path: 'exam/:hash_sesi',
                name: 'ujian-soal-multiple',
                component: Exampage,
                meta: {
                    title: 'Ujian',
                    hideSidebar: true,
                }
            },
        ]
    },
    {
        path: '/setting',
        name: 'admin_settings',
        // component: AdminLayout,
        children: [
            {
                path: 'users',
                name: 'course_users',
                component: SettingUser,
            },

        ]
    },
    {
        path: '/callback',
        name: "callback",
        component: ProfilePage
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
