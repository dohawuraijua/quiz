<template>
    <div>
        <section class="py-2 px-4">
            <div class="max-w-7xl mx-auto">

                <!-- Header -->
                <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3 mb-4">
                    <div>
                        <h1 class="text-xl md:text-2xl font-semibold text-gray-900">Jadwal Ujian</h1>

                    </div>

                    <div class="flex items-center gap-2">
                        <input v-model="search" type="text" placeholder="Cari Jadwal..."
                            class="w-full md:w-80 px-3 py-2 rounded-xl border bg-white focus:outline-none focus:ring-2 focus:ring-gray-200" />
                        <select v-model="filter" class="px-3 py-2 rounded-xl border bg-white">
                            <option value="all">Semua</option>
                            <option value="Berlangsung">Berlangsung</option>
                            <option value="Mendatang">Mendatang</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                </div>
                <hr>
                <!-- Empty -->
                <div v-if="groupedItems.length === 0" class="border rounded-2xl p-8 bg-white text-center mt-4">
                    <div class="text-4xl mb-2">🗓️</div>
                    <div class="font-semibold text-gray-900">Tidak ada jadwal</div>
                    <div class="text-sm text-gray-600">Coba ubah filter atau kata kunci.</div>
                </div>

                <!-- Timeline -->
                <div v-else class="grid grid-cols-1 md:grid-cols-1 gap-6 mt-5">
                    <div v-for="g in groupedItems" :key="g.dateKey" class="relative space-y-3">
                        <!-- Date badge -->
                        <div
                            class="sticky top-3 z-10 inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gray-900 text-white text-sm shadow">
                            <span class="text-xs opacity-80">📅</span>
                            <span class="font-medium">{{ formatDateID(g.dateKey) }}</span>
                            <span class="text-xs opacity-80">• {{ g.items.length }} sesi</span>
                        </div>

                        <!-- Cards (1 kolom per tanggal) -->
                        <div class="grid grid-cols-1 gap-3">
                            <article v-for="it in g.items" :key="it.id"
                                class="border rounded-2xl bg-white p-4 hover:shadow-md transition-shadow">
                                <!-- Title -->
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <h3 class="font-semibold text-gray-900 truncate">
                                            {{ it.event_nama }}
                                        </h3>
                                        <p class="text-sm text-gray-600 line-clamp-2">
                                            {{ it.event_desc || '-' }}
                                        </p>
                                    </div>

                                    <span class="shrink-0 px-2.5 py-1 rounded-full text-xs font-medium border"
                                        :class="statusPillClass(it.status)">
                                        {{ statusLabel(it.status) }}
                                    </span>
                                </div>

                                <!-- Meta -->
                                <div class="mt-3 space-y-2">
                                    <div class="flex flex-wrap gap-2">
                                        <div
                                            class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-50 border">

                                            <span class="text-sm font-medium text-gray-900">
                                                ⏰ {{ it.date_key }}<br>
                                                🗓️ {{ it.time_start }} - {{ it.time_end }}
                                            </span>
                                            <span class="text-xs text-gray-600">
                                                ({{ it.duration_min }} menit)
                                            </span>
                                        </div>

                                        <div
                                            class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-50 border">
                                            🏢
                                            <span class="text-sm font-medium text-gray-900">
                                                {{ it.kantor_nama || '-' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap gap-2">
                                        <div
                                            class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-50 border">
                                            👤
                                            <span class="text-sm font-medium text-gray-900">
                                                {{ it.peserta_nama }}
                                            </span>
                                            <span class="text-xs text-gray-600">
                                                {{ it.peserta_npp }}
                                            </span>
                                        </div>
                                        <div
                                            class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-50 border">
                                            🧩
                                            <span class="text-sm font-medium text-gray-900">
                                                Modul {{ it.modul }}
                                            </span>
                                        </div>
                                        <div v-if="it.skor_akhir"
                                            class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-gray-50 border">
                                            🔢
                                            <span class="text-sm font-medium text-gray-900">
                                                Skor {{ it.skor_akhir }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Attachment -->
                                <div v-if="it.file_count > 0" class="mt-3 text-xs text-gray-600">
                                    📎 {{ it.file_count }} file
                                </div>

                                <!-- Actions -->
                                <div class="mt-2 flex items-center justify-between">
                                    <button
                                        class="text-sm text-gray-700 hover:text-gray-900 underline underline-offset-4"
                                        @click="openDetail(it)">
                                        Lihat Materi
                                    </button>

                                    <button v-if="it.status != 'Selesai'"
                                        class="px-3 py-2 rounded-xl text-sm text-white bg-blue-600 hover:bg-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                        :disabled="it.status !== 'Berlangsung'" @click="startExam(it)">
                                        Mulai Ujian
                                    </button>
                                </div>


                            </article>
                        </div>
                    </div>
                </div>

                <!-- Modal Detail -->
                <div v-if="showModal" class="fixed inset-0 z-[9999] bg-black/40 flex items-center justify-center p-4">
                    <div class="w-full max-w-xl bg-white rounded-2xl p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="text-lg font-semibold text-gray-900">{{ selected?.event_nama }}</div>
                                <div class="text-sm text-gray-600">{{ selected?.event_desc || "-" }}</div>
                            </div>

                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                            <div class="border rounded-xl p-3">
                                <div class="text-xs text-gray-500 mb-1">Waktu</div>
                                <div class="font-medium text-gray-900">{{ selected?.date_label }}</div>
                                <div class="text-gray-700">{{ selected?.time_start }} - {{ selected?.time_end }} ({{
                                    selected?.duration_min }} menit)</div>
                            </div>

                            <div class="border rounded-xl p-3">
                                <div class="text-xs text-gray-500 mb-1">Peserta</div>
                                <div class="font-medium text-gray-900">{{ selected?.peserta_nama }}</div>
                                <div class="text-gray-700">NPP: {{ selected?.peserta_npp }}</div>
                            </div>

                            <div class="border rounded-xl p-3">
                                <div class="text-xs text-gray-500 mb-1">Kantor</div>
                                <div class="font-medium text-gray-900">{{ selected?.kantor_nama || "-" }}</div>
                            </div>

                            <div class="border rounded-xl p-3 flex flex-wrap justify-between">
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">Status</div>
                                    <div class="font-medium text-gray-900">{{ statusLabel(selected?.status) }}</div>

                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">Skor</div>
                                    <div class="font-medium text-gray-900">{{ (selected?.skor_akhir) }}</div>

                                </div>

                            </div>

                            <div class="border rounded-xl p-3 md:col-span-2">
                                <div class="text-xs text-gray-500 mb-2">File Event</div>
                                <ul class="list-disc pl-5 space-y-1">
                                    <li v-for="(f, idx) in selected?.files || []" :key="idx" class="url"
                                        @click="get_cloud_url(selected.event_id, f.path)">
                                        {{ f.name }}
                                    </li>
                                </ul>
                                <div v-if="(selected?.files || []).length === 0" class="text-gray-600">Tidak ada file.
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 flex justify-between gap-2">
                            <button class="px-3 py-2 rounded-lg border hover:bg-red-100"
                                @click="closeDetail">Tutup</button>

                            <button v-if="selected.status != 'Selesai'"
                                class="px-3 py-2 rounded-xl text-sm text-white bg-blue-600 hover:bg-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="selected.status !== 'Berlangsung'" @click="startExam(selected)">
                                Mulai Ujian
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
</template>

<script>
import RequestManager from '../../helper/RequestManager';
import toastService from '../../helper/toastService';

export default {
    name: "JadwalUjianCards",
    data() {
        return {
            // isi dari API: array sesi ujian
            items: [],

            search: "",
            filter: "all",
            showModal: false,
            selected: null,

            searchTimeout: null,
        };
    },

    mounted() {
        this.viewItem(true);
    },

    watch: {
        search() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.viewItem();
            }, 1200);
        },
        filter() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.viewItem();
            }, 800);
        },
    },

    computed: {
        // mapping data API -> view model yang siap dipakai UI
        normalizedItems() {
            const now = new Date();

            return (this.items || []).map((x) => {
                const start = this.parseSQLDateTime(x.jadwal_mulai);
                const end = this.parseSQLDateTime(x.jadwal_berakhir);

                const status = x.status;

                const eventNama = x?.event?.nama_event || "-";
                const eventDesc = x?.event?.deskripsi || "";
                const skor_akhir = x?.skor_akhir || null;
                const kantorNama = x?.kantor?.nama || "-";
                const pesertaNama = x?.peserta?.name || "-";
                const pesertaNpp = x?.peserta?.npp || x?.peserta?.email || "-";
                const files = Array.isArray(x?.event?.file_event) ? x.event.file_event : [];

                return {
                    id: x.id,
                    event_id: x.event_id,
                    userid: x.userid,
                    modul: x.modul,
                    hash_sesi: x.hash_sesi,

                    start_dt: start,
                    end_dt: end,

                    date_key: this.toDateKey(start),           // YYYY-MM-DD
                    date_label: this.formatDateID(this.toDateKey(start)),
                    time_start: this.formatTime(start),
                    time_end: this.formatTime(end),
                    duration_min: this.diffMinutes(start, end),
                    skor_akhir: skor_akhir,
                    status,

                    event_nama: eventNama,
                    event_desc: eventDesc,
                    kantor_nama: kantorNama,
                    peserta_nama: pesertaNama,
                    peserta_npp: pesertaNpp,

                    files,
                    file_count: files.length,

                    raw: x,
                };
            });
        },

        groupedItems() {
            const map = new Map();

            for (const it of this.normalizedItems) {
                const key = it.date_key || "-";

                if (!map.has(key)) {
                    map.set(key, {
                        dateKey: key,
                        items: []
                    });
                }

                map.get(key).items.push(it);
            }

            // kembalikan hasil grouping TANPA sorting
            return Array.from(map.values());
        },

    },

    methods: {
        openDetail(it) {
            this.selected = it;
            this.showModal = true;
        },
        closeDetail() {
            this.selected = null;
            this.showModal = false;
        },
        async startExam(it) {
            if (!it) return;
            this.loadingView = true;
            try {
                let parameter = {
                    action: "goToExam",
                    hash_sesi: it.hash_sesi
                }
                const response = await RequestManager("_b/cources/jadwal-aktif", parameter);
                if (response.data.type === "success") {
                    this.$router.push({ name: 'ujian-soal-multiple', params: { hash_sesi: it.hash_sesi } });
                } else {
                    toastService.show("", response.data.message, response.data.type);
                }
                this.loadingView = false;
            } catch (error) {
                this.loadingView = false;
                toastService.show("Error", error, "error");
            }

        },

        async viewItem(cekActive = false) {
            this.loadingView = true;
            try {
                let parameter = {
                    action: "jadwalActive",
                    search: this.search,
                    filter: this.filter,
                    id: this.idresourches
                }
                const response = await RequestManager("_b/cources/jadwal-aktif", parameter);


                if (response.data.type === "success") {
                    this.items = response.data.data;
                    if (cekActive) {
                        this.cekJadwalActive();
                    }
                } else {
                    toastService.show("Error", response.data.message, "error");
                }
                this.loadingView = false;
            } catch (error) {
                this.loadingView = false;
                toastService.show("Error", error, "error");
            }
        },

        async cekJadwalActive() {
            if (!Array.isArray(this.items) || this.items.length === 0) return;
            const hasBerlangsung = this.items.some(it =>
                String(it?.status ?? '').toLowerCase() === 'berlangsung'
            );
            if (hasBerlangsung) {
                const targetFilter = 'Berlangsung';
                if (this.filter === targetFilter) return;
                this.filter = targetFilter;
                await this.viewItem(false);
                return
            }

            const hasMendatang = this.items.some(it =>
                String(it?.status ?? '').toLowerCase() === 'mendatang'
            );

            if (hasMendatang) {
                const targetFilter = 'Mendatang';
                if (this.filter === targetFilter) return;
                this.filter = targetFilter;
                await this.viewItem(false);
                return
            }
        },

        async copySesi(hash) {
            if (!hash) return;
            try {
                await navigator.clipboard.writeText(hash);
            } catch {
                const ta = document.createElement("textarea");
                ta.value = hash;
                ta.setAttribute("readonly", "");
                ta.style.position = "fixed";
                ta.style.opacity = "0";
                document.body.appendChild(ta);
                ta.select();
                document.execCommand("copy");
                document.body.removeChild(ta);
            }
        },


        statusLabel(status) {
            return (
                {
                    Mendatang: "Mendatang",
                    Berlangsung: "Berlangsung",
                    Selesai: "Selesai",
                }[status] || "—"
            );
        },
        statusPillClass(status) {
            if (status === "Berlangsung") return "border-gray-900 text-gray-900 bg-white";
            if (status === "Mendatang") return "border-gray-300 text-gray-700 bg-gray-50";
            return "border-gray-300 text-gray-600 bg-white";
        },

        // ===== date helpers =====
        parseSQLDateTime(s) {
            // "2025-12-29 15:36:00" -> Date
            if (!s) return new Date(NaN);
            // buat jadi ISO-ish: "2025-12-29T15:36:00"
            const iso = String(s).replace(" ", "T");
            return new Date(iso);
        },
        toDateKey(d) {
            if (!(d instanceof Date) || isNaN(d)) return "-";
            const y = d.getFullYear();
            const m = String(d.getMonth() + 1).padStart(2, "0");
            const day = String(d.getDate()).padStart(2, "0");
            return `${y}-${m}-${day}`;
        },
        formatTime(d) {
            if (!(d instanceof Date) || isNaN(d)) return "--:--";
            const hh = String(d.getHours()).padStart(2, "0");
            const mm = String(d.getMinutes()).padStart(2, "0");
            return `${hh}:${mm}`;
        },
        diffMinutes(a, b) {
            if (!(a instanceof Date) || isNaN(a) || !(b instanceof Date) || isNaN(b)) return 0;
            return Math.max(0, Math.round((b - a) / 60000));
        },
        formatDateID(dateKey) {
            // dateKey: "YYYY-MM-DD"
            if (!dateKey || dateKey === "-") return "-";
            const [y, m, d] = dateKey.split("-").map((x) => parseInt(x, 10));
            const months = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
            return `${d} ${months[(m || 1) - 1]} ${y}`;
        },


        async get_cloud_url(id, pathto) {
            let parameter = {
                action: "get_cloud_url",
                id: id,
                path: pathto
            };
            const response = await RequestManager(
                "_b/cources/jadwal-aktif",
                parameter
            )
                .then((response) => {
                    if (response.data.type == "success") {
                        const cloudUrl = response.data.data;
                        if (cloudUrl) {
                            window.open(cloudUrl, "_blank");
                        }
                    } else {
                        toastService.show("Message", response.data.message);
                    }
                    this.load_cloud_url = null;
                })
                .catch((err) => {
                    this.load_cloud_url = null;
                });
        },
    },
};
</script>

<style scoped>
/* Optional: kalau tailwind kamu belum ada line-clamp plugin */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
