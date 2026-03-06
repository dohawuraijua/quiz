<template>
    <div class="w-full rounded-xl border border-slate-200 bg-white p-4">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-sm font-semibold text-slate-800">Progres Nilai Event</h2>
                <p class="text-xs text-slate-500">Sorot titik untuk melihat lebih detail</p>
            </div>

            <button class="text-xs px-3 py-1 rounded-lg border border-slate-200 hover:bg-slate-50 disabled:opacity-60"
                @click="fetchData" :disabled="loading">
                {{ loading ? 'Loading...' : 'Refresh' }}
            </button>
        </div>

        <div class="mt-4 h-72">
            <Chart v-if="chartData" :type="'bar'" :data="chartData" :options="chartOptions" />
            <div v-else class="h-full flex items-center justify-center text-sm text-slate-500">
                Tidak ada data skor
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, shallowRef, onMounted } from 'vue'
import { Chart } from 'vue-chartjs'
import RequestManager from '../../helper/RequestManager'

import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale,
} from 'chart.js'

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale
)

const loading = ref(false)
const points = ref([])
const chartData = shallowRef(null)

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    interaction: {
        mode: 'index',
        intersect: false,
    },
    plugins: {
        legend: {
            display: false,
        },
        tooltip: {
            backgroundColor: 'rgba(15, 23, 42, 0.9)',
            titleColor: '#e5e7eb',
            bodyColor: '#e5e7eb',
            padding: 10,
            filter: (tooltipItem) => tooltipItem.dataset.type === 'bar',
            callbacks: {
                title: (items) => {
                    const idx = items?.[0]?.dataIndex ?? 0
                    return points.value[idx]?.tanggal ?? ''
                },
                label: (item) => {
                    const idx = item.dataIndex
                    const p = points.value[idx]
                    if (!p) return ''
                    return [
                        `Skor : ${p.skor}`,
                        `Event : ${p.nama_event}`,
                        `Kantor: ${p.nama_kantor}`,
                    ]
                },
            },
        },
    },
    scales: {
        x: {
            grid: { display: false },
            ticks: { color: '#64748b', font: { size: 11 } },
        },
        y: {
            beginAtZero: true,
            suggestedMax: 100,
            grid: { color: 'rgba(148, 163, 184, 0.2)' },
            ticks: { precision: 0, stepSize: 5, color: '#64748b', font: { size: 11 } },
        },
    },
    animation: {
        onComplete: (ctx) => {
            const chart = ctx.chart
            const { ctx: canvasCtx } = chart

            canvasCtx.save()
            canvasCtx.font = '600 11px Inter, sans-serif'
            canvasCtx.fillStyle = '#1e293b' // slate-800
            canvasCtx.textAlign = 'center'

            chart.data.datasets.forEach((dataset, datasetIndex) => {
                // hanya label di BAR
                if (dataset.type !== 'bar') return

                const meta = chart.getDatasetMeta(datasetIndex)
                meta.data.forEach((bar, index) => {
                    const value = dataset.data[index]
                    canvasCtx.fillText(
                        value,
                        bar.x,
                        bar.y - 6 // posisi di atas bar
                    )
                })
            })

            canvasCtx.restore()
        },
    },

}

function scoreColor(value) {
    if (value < 60) return '#ef4444'   // red-500
    if (value <= 80) return '#f59e0b'  // amber-500
    return '#22c55e'                   // green-500
}

async function fetchData() {
    let isMobile = window.innerWidth < 750 ? true : false;
    if (loading.value) return
    loading.value = true
    try {
        const res = await RequestManager('_b/dashboard', { limit: isMobile ? 4 : 10, action: 'eventScoreChart' })
        const json = res.data.data;
        points.value = Array.isArray(json) ? json : (Array.isArray(json.data) ? json.data : [])

        if (!points.value.length) {
            chartData.value = null
            return
        }

        const labels = points.value.map(p => p.tanggal)
        const values = points.value.map(p => Number(p.skor) ?? 0)

        chartData.value = {
            labels,
            datasets: [
                {
                    type: 'bar',
                    data: values,
                    backgroundColor: values.map(v => scoreColor(v) + '55'), // transparan
                    borderColor: values.map(v => scoreColor(v)),
                    borderWidth: 1,
                    borderRadius: 8,
                    barPercentage: 0.6,
                    categoryPercentage: 0.6,
                },
                {
                    type: 'line',
                    data: values,
                    borderColor: 'rgb(99, 102, 241)',
                    backgroundColor: 'rgb(99, 102, 241)',
                    tension: 0.35,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: values.map(v => scoreColor(v)),
                    pointBorderWidth: 2,
                },
            ],
        }
    } catch (e) {
        console.error(e)
        chartData.value = null
    } finally {
        loading.value = false
    }
}

onMounted(fetchData)
</script>
