<template>
    <div>
        <section class="py-2 px-4 sm:px-2 lg:px-8">
            <div class="max-w-8xl mx-auto">

                <!-- OVERVIEW -->
                <div v-if="stats" class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div class="bg-white shadow rounded p-4 text-center">
                        <div class="text-gray-500 text-sm">Total Words</div>
                        <div class="text-2xl font-bold">{{ stats.overview.total_words }}</div>
                    </div>
                    <div class="bg-white shadow rounded p-4 text-center">
                        <div class="text-gray-500 text-sm">Checked</div>
                        <div class="text-2xl font-bold text-blue-600">{{ stats.overview.checked_words }}</div>
                    </div>
                    <div class="bg-white shadow rounded p-4 text-center">
                        <div class="text-gray-500 text-sm">Average Score</div>
                        <div class="text-2xl font-bold text-green-600">{{ stats.overview.average_score }}</div>
                    </div>
                    <div class="bg-white shadow rounded p-4 text-center">
                        <div class="text-gray-500 text-sm">Highest</div>
                        <div class="text-2xl font-bold text-green-500">{{ stats.overview.max_score }}</div>
                    </div>
                    <div class="bg-white shadow rounded p-4 text-center">
                        <div class="text-gray-500 text-sm">Lowest</div>
                        <div class="text-2xl font-bold text-red-500">{{ stats.overview.min_score }}</div>
                    </div>
                </div>

                <!-- DISTRIBUTION -->
                <div v-if="stats" class="bg-white shadow rounded p-6">
                    <h2 class="text-lg font-semibold mb-4">Score Distribution</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <StatBadge label="100 Perfect" color="green" :value="stats.distribution.perfect" />
                        <StatBadge label="90-99 Excellent" color="emerald" :value="stats.distribution.excellent" />
                        <StatBadge label="75-89 Good" color="blue" :value="stats.distribution.good" />
                        <StatBadge label="60-74 Fair" color="yellow" :value="stats.distribution.fair" />
                        <StatBadge label="40-59 Poor" color="orange" :value="stats.distribution.poor" />
                        <StatBadge label="1-39 Bad" color="red" :value="stats.distribution.bad" />
                        <StatBadge label="0 Wrong" color="rose" :value="stats.distribution.zero" />
                    </div>
                </div>

                <!-- DAILY STATS CHART -->
                <div v-if="stats" class="bg-white shadow rounded p-6">
                    <h2 class="text-lg font-semibold mb-4">Daily Average Score</h2>
                    <canvas v-if="stats && stats.daily_stats.length" ref="dailyChart"></canvas>
                </div>

                <!-- LOADING -->
                <div v-if="loadingStats" class="text-gray-500">Loading dashboard...</div>
                <div v-if="errorStats" class="text-red-500">{{ errorStats }}</div>
            </div>
        </section>
    </div>
</template>

<script>
import { ref, onMounted } from "vue";
import Chart from "chart.js/auto";
import RequestManager from "../../helper/RequestManager";
import StatBadge from '../../global/StatBadge.vue';

const DASHBOARD_API = "dashboard/stats";

export default {
    components: { StatBadge },
    data() {
        return {
            stats: null,
            loadingStats: false,
            errorStats: null,
            dailyChart: null,
        };
    },
    mounted() {
        this.fetchStats();
    },
    methods: {
        async fetchStats() {
            this.loadingStats = true;
            this.errorStats = null;
            try {
                const response = await RequestManager(DASHBOARD_API);
                this.stats = response.data;

                // tunggu DOM render
                this.$nextTick(() => {
                    if (this.stats.daily_stats.length > 0 && this.$refs.dailyChart) {
                        this.initDailyChart();
                    }
                });

            } catch (e) {
                console.error(e);
                this.errorStats = "Gagal memuat dashboard";
            } finally {
                this.loadingStats = false;
            }
        },

        initDailyChart() {
            if (!this.$refs.dailyChart) return;

            const ctx = this.$refs.dailyChart.getContext("2d");
            if (!ctx) return;

            const labels = this.stats.daily_stats.map(d => d.date);
            const data = this.stats.daily_stats.map(d => parseFloat(d.avg_score));

            this.dailyChart = new Chart(ctx, {
                type: "line",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Average Score",
                        data: data,
                        borderColor: "#34D399",
                        backgroundColor: "#A7F3D0",
                        fill: true,
                        tension: 0.3,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true, max: 100 }
                    }
                }
            });
        }
    }
};
</script>

<style scoped></style>