import { createRouter, createWebHistory } from 'vue-router';


import HomeUser from '../components/user/dashboard/HomeUser.vue';
import ResourcesPeraturan from '../components/user/resouces/peraturan.vue';
import ResourcesPeraturanDetail from '../components/user/resouces/peraturan_detail.vue';
import ResourcesVideos from '../components/user/resouces/videos.vue';
import ResourcesVideosDetail from '../components/user/resouces/videos_detail.vue';
import JadwakAktif from '../components/user/ujian/jadwal_aktif.vue';
import Exampage from '../components/user/ujian/exampage.vue';
import ProfilePage from '../components/user/dashboard/profilePage.vue';


const routes = [
    {
        path: '/',
        name: 'home',
        component: HomeUser,
    },
    {
        path: "/me",
        name: "profile",
        component: ProfilePage
    },
    {
        path: '/resources',
        name: 'user_resources',
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
            {
                path: 'video/:pretty_url',
                name: 'resources_videos_detail',
                component: ResourcesVideosDetail,
            },
        ]
    },
    {
        path: '/course',
        name: 'user_course',
        // component: AdminLayout,
        children: [
            {
                path: 'jadwal-aktif',
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
];

const router = createRouter({
    history: createWebHistory("/_b"),
    routes,
});

router.afterEach(() => {
    if (window.innerWidth <= 768) {
        toggleSidebar()
    }
});

export default router;
