<template>
    <div class="p-4 sm:p-6 max-w-full sm:max-w-8xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome back {{ userData.name ?? ''
        }}!</h2>
        <p class="text-gray-600 mb-8">Continue your learning journey today</p>
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="card p-4">
                <div class="flex items-center gap-4 justify-start url" @click="goToJadwal('all')">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-r from-cyan-500 to-blue-600 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Quiz</p>
                        <p class="text-2xl font-bold text-gray-900">{{ itemsCardEvent?.jumlahEvent }}</p>
                    </div>
                </div>

            </div>
            <div class="card p-4">
                <div class="flex items-center gap-4 justify-start url" @click="goToJadwal('Mendatang')">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-r from-emerald-500 to-teal-600 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Quiz Mendatang</p>
                        <p class="text-2xl font-bold text-gray-900">{{ itemsCardEvent?.jumlahEventMendatang
                        }}</p>
                    </div>
                </div>

            </div>
            <div class="card p-4">
                <div class="flex items-center gap-4 justify-start url" @click="goToJadwal('Berlangsung')">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-r from-amber-500 to-orange-600 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Quiz Berlangsung</p>
                        <p class="text-2xl font-bold text-gray-900">{{
                            itemsCardEvent?.jumlahEventBerlangsung }}</p>
                    </div>
                </div>

            </div>
            <div class="card p-4">
                <div class="flex items-center gap-4 justify-start url" @click="goToJadwal('Selesai')">
                    <div
                        class="w-12 h-12 rounded-lg bg-gradient-to-r from-purple-500 to-indigo-600 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Skor Quiz Terakhir</p>
                        <p class="text-2xl font-bold text-gray-900">{{ itemsCardEvent?.skorEventTerakhir }}
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <div class="w-full mb-8" v-if="jadwalActive">
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                <div v-for="item in jadwalActive" :key="item.id"
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition duration-300">
                    <!-- HEADER -->
                    <div class="p-4 border-b border-gray-100">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold text-gray-800 line-clamp-2">
                                {{ item.event?.nama_event }}
                            </h3>

                            <!-- Status Badge -->
                            <span :class="statusBadge(item)" class="text-xs font-medium px-3 py-1 rounded-full">
                                {{ statusLabel(item) }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-500">
                            {{ formatDate(item.event?.tanggal_event) }}
                        </p>
                    </div>

                    <!-- BODY -->
                    <div class="p-4 space-y-2 text-sm text-gray-600">

                        <div class="flex justify-between">
                            <span>Peserta</span>
                            <span class="font-medium text-gray-800">
                                {{ item.peserta?.name }}
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span>Kantor</span>
                            <span class="font-medium text-gray-800">
                                {{ item.kantor?.nama }}
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span>Jadwal</span>
                            <span class="font-medium text-gray-800">
                                {{ formatTime(item.jadwal_mulai) }} -
                                {{ formatTime(item.jadwal_berakhir) }}
                            </span>
                        </div>
                    </div>

                    <!-- FOOTER -->
                    <div class="px-4 py-4 bg-gray-50 rounded-b-2xl flex justify-end items-center">
                        <!-- <span class="text-xs text-gray-400">
                            ID Sesi: {{ item.hash_sesi.substring(0, 8) }}...
                        </span> -->

                        <router-link to="/course/jadwal-aktif/Berlangsung"
                            class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                            Lihat Detail Jadwal →
                        </router-link>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full mb-8">
            <dashboardChart />
        </div>
    </div>
</template>

<script>
import dashboardChart from './dashboardChart.vue';
import toastService from '../../helper/toastService';
import RequestManager from '../../helper/RequestManager';
import { login_state } from '../../../stores/login_state';
export default {
    data() {
        return {
            itemsCardEvent: [],
            jadwalActive: []
        }
    },
    components: {
        dashboardChart
    },
    mounted() {
        this.fetchItemCard();
        this.jadwalActiveCall();
    },
    computed: {
        isLogedin() {
            return login_state.state.isLogin;
        },
        userData() {
            return login_state.state.userDetail;
        },
    },
    methods: {

        formatDate(date) {
            if (!date) return '-'
            return new Intl.DateTimeFormat('id-ID', {
                dateStyle: 'medium'
            }).format(new Date(date))
        },

        formatTime(date) {
            if (!date) return '-'
            return new Intl.DateTimeFormat('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            }).format(new Date(date))
        },

        statusLabel(item) {
            if (item.waktu_mulai && item.waktu_berakhir) return 'Selesai'
            if (item.waktu_mulai && !item.waktu_berakhir) return 'Berlangsung'
            return 'Jadwal Mendatang'
        },

        statusBadge(item) {
            if (item.waktu_mulai && item.waktu_berakhir)
                return 'bg-green-100 text-green-700'
            if (item.waktu_mulai && !item.waktu_berakhir)
                return 'bg-blue-100 text-blue-700'
            return 'bg-yellow-100 text-yellow-600'
        },


        async fetchItemCard(page) {
            this.loadingPage = true;
            try {
                const parameter = {
                    action: "dashboardData",
                };
                const res = await RequestManager("_b/dashboard", parameter);

                if (res.data.type === "success") {
                    this.itemsCardEvent = res.data.data;
                } else {
                    toastService.show("Error", res.data.message, "error");
                }
            } catch (e) {
                toastService.show("Error", String(e), "error");
            } finally {
                this.loadingPage = false;
            }
        },

        async jadwalActiveCall() {
            this.loadingPage = true;
            try {
                const parameter = {
                    action: "jadwalActive",
                };
                const res = await RequestManager("_b/dashboard", parameter);

                if (res.data.type === "success") {
                    this.jadwalActive = res.data.data;
                }
            } catch (e) {
                toastService.show("Error", String(e), "error");
            } finally {
                this.loadingPage = false;
            }
        },

        goToJadwal(status) {
            let filter = '';
            if (status === 'Berlangsung') {
                filter = 'Berlangsung';
            } else if (status === 'Mendatang') {
                filter = 'Mendatang';
            } else if (status === 'Selesai') {
                filter = 'Selesai';
            } else {
                filter = 'all';
            }
            this.$router.push({ name: 'course_jadwal_aktif', params: { c: filter } });
        }
    }
}
</script>