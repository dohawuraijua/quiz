<template>

    <main class="content-area bg-gray-50 p-6">
        <div class="max-w-8xl mx-auto">
            <h2 id="welcome-heading" class="text-3xl font-bold text-gray-900 mb-2">Welcome back!</h2>
            <p id="dashboard-subtitle" class="text-gray-600 mb-8">Continue your learning journey today</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                <!-- MODUL -->
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-lg bg-gradient-to-r from-cyan-500 to-blue-600 flex items-center justify-center">
                            <!-- Book Icon -->
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13M12 6.253C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13
                           C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253
                           M12 6.253C13.168 5.477 14.754 5 16.5 5
                           c1.747 0 3.332.477 4.5 1.253v13
                           C19.832 18.477 18.247 18 16.5 18
                           c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-1">Modul</p>
                    <p class="text-3xl font-bold text-gray-900">
                        {{ numberFormat(itemsCardEvent.jumlahmodul) ?? 0 }}
                    </p>
                </div>

                <!-- PERATURAN -->
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-lg bg-gradient-to-r from-emerald-500 to-teal-600 flex items-center justify-center">
                            <!-- Document Icon -->
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6M7 4h6l4 4v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-1">Peraturan</p>
                    <p class="text-3xl font-bold text-gray-900">
                        {{ numberFormat(itemsCardEvent.jumlahResource) ?? 0 }}
                    </p>
                </div>

                <!-- USERS -->
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-lg bg-gradient-to-r from-amber-500 to-orange-600 flex items-center justify-center">
                            <!-- Users Icon -->
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1
                           M9 20H4v-2a4 4 0 014-4h1
                           m4-4a4 4 0 10-8 0 4 4 0 008 0
                           m6 4a4 4 0 10-8 0" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-1">Users</p>
                    <p class="text-3xl font-bold text-gray-900">
                        {{ numberFormat(itemsCardEvent.jumlahUser) ?? 0 }}
                    </p>
                </div>

                <!-- EVENT -->
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-lg bg-gradient-to-r from-purple-500 to-indigo-600 flex items-center justify-center">
                            <!-- Calendar Icon -->
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10
                           M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5
                           a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-1">Event</p>
                    <p class="text-3xl font-bold text-gray-900">
                        {{ numberFormat(itemsCardEvent.jumlahEvent) ?? 0 }}
                    </p>
                </div>

            </div>

        </div>
    </main>
</template>


<script>
import toastService from '../helper/toastService';
import RequestManager from '../helper/RequestManager';
export default {
    data() {
        return {
            itemsCardEvent: []
        }
    },
    components: {

    },
    mounted() {
        this.fetchItemCard();
    },
    methods: {
        numberFormat(value) {
            const number = Number(value) || 0
            return new Intl.NumberFormat('id-ID').format(number)
        }
        ,
        async fetchItemCard(page) {
            this.loadingPage = true;
            try {
                const parameter = {
                    action: "dashboard",
                };
                const res = await RequestManager("_a/dashboard", parameter);

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
    }
}
</script>