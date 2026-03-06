<template>
    <div>
        <section class="py-2 px-4">
            <div class="max-w-8xl mx-auto">

                <!-- Header -->
                <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-4">
                    <div>
                        <h1 class="text-xl md:text-2xl font-semibold text-gray-900">Jadwal Ujian</h1>
                        <!-- <p class="text-sm text-gray-600">
                            Jadwal ujian yang akan berlangsung.
                        </p> -->
                    </div>

                    <div class="flex items-center gap-2">
                        <select class="w-full border rounded-md px-3 py-2" v-model="pagination.per_page"
                            @change="viewItem()">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="200">200</option>
                        </select>
                        <select v-model="filter" class="px-3 py-2 rounded-md border bg-white">
                            <option value="all">Semua Status</option>
                            <option value="Berlangsung">Berlangsung</option>
                            <option value="Mendatang">Mendatang</option>
                            <option value="Selesai">Selesai</option>
                        </select>

                        <input v-model="search" type="text" placeholder="Cari jadwal..."
                            class="w-full md:w-80 px-3 py-2 rounded-md border bg-white focus:outline-none focus:ring-2 focus:ring-gray-200" />


                        <label v-if="hasRoles([1])" class="w-full"><input type="checkbox" v-model="showMeOnly"
                                class="mr-2">Me
                            Only</label>
                    </div>
                </div>
                <hr>
                <!-- Empty -->
                <div v-if="groupedItems.length === 0" class="border rounded-2xl p-8 bg-white text-center mt-4">
                    <div class="text-4xl mb-2">🗓️</div>
                    <div class="font-semibold text-gray-900">Tidak ada jadwal</div>
                    <div class="text-sm text-gray-600">Coba ubah filter atau kata kunci.</div>
                </div>


                <div v-else class="grid grid-cols-1 md:grid-cols-1 gap-6 mt-5">

                    <SortableTableTail :data="items" :columns="columns" :hasActions="true">
                        <template #actions="{ row }">
                            <div class="relative inline-block text-left group">

                                <button v-if="hasRoles([1])"
                                    class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-gray-800 rounded-md">
                                    Action
                                    <svg class="w-5 h-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12l-4-4h8l-4 4z" />
                                    </svg>
                                </button>

                                <button v-else :title="row.status !== 'Berlangsung'
                                    ? 'Ujian hanya bisa dimulai saat status Berlangsung'
                                    : 'Mulai Ujian'" class="block bg-blue-500 rounded-md px-4 py-2 text-sm" :class="row.status !== 'Berlangsung'
                                        ? 'text-black cursor-not-allowed opacity-70'
                                        : 'text-white hover:bg-blue-600 hover:text-white'"
                                    @click.prevent="row.status === 'Berlangsung' && startExam(row)">
                                    {{ row.waktu_mulai && !row.waktu_berakhir ? 'Lanjutkan Ujian' : 'Mulai Ujian' }}
                                </button>

                                <div v-if="hasRoles([1])" class=" invisible group-focus-within:visible opacity-0
                                    group-focus-within:opacity-100 transition-all duration-300 absolute right-0 w-48
                                    mt-2 border rounded-md shadow-xl z-50 bg-gray-100">
                                    <a href="javascript:void(0)" v-if="hasRoles([1])"
                                        class="block px-4 py-2 text-sm text-black hover:bg-blue-100"
                                        @click="tampilkanHasilUjian(row)">Tampilkan Hasil Ujian</a>

                                    <a href="#" :title="row.status !== 'Berlangsung'
                                        ? 'Ujian hanya bisa dimulai saat status Berlangsung'
                                        : 'Mulai Ujian'" class="block px-4 py-2 text-sm" :class="row.status !== 'Berlangsung'
                                            ? 'text-gray-400 cursor-not-allowed opacity-70'
                                            : 'text-black hover:bg-green-800 hover:text-white'"
                                        @click.prevent="row.status === 'Berlangsung' && startExam(row)">

                                        {{ row.waktu_mulai && !row.waktu_berakhir ? 'Lanjutkan Ujian' : 'Mulai Ujian' }}
                                    </a>

                                    <a href="#" :title="row.status !== 'Mendatang'
                                        ? 'Jadwal hanya bisa diedit saat status Mendatang'
                                        : 'Edit Jadwal'" class="block px-4 py-2 text-sm" :class="row.status !== 'Mendatang'
                                            ? 'text-gray-400 cursor-not-allowed opacity-70'
                                            : 'text-black hover:bg-green-800 hover:text-white'"
                                        @click.prevent="row.status === 'Mendatang' && editJadwal(row)">
                                        Edit Jadwal
                                    </a>
                                </div>
                            </div>


                        </template>
                        <template #custom="{ row, column }">

                            <span v-if="column.key == 'nama_pegawai'">
                                {{ row?.peserta?.name }}
                            </span>

                            <span v-if="column.key == 'jadwal_mulai'">
                                <span v-if="row.jadwal_mulai">
                                    <TanggalIndo :longdate="false" :tanggal="row.jadwal_mulai" :time="false">
                                    </TanggalIndo>
                                </span>
                            </span>


                            <span v-if="column.key == 'jadwal_berakhir'">
                                <span v-if="row.jadwal_berakhir">
                                    <TanggalIndo :longdate="false" :tanggal="row.jadwal_mulai" :time_only="true"
                                        :time="true">
                                    </TanggalIndo> -
                                    <TanggalIndo :longdate="false" :tanggal="row.jadwal_berakhir" :time_only="true"
                                        :time="true">
                                    </TanggalIndo>
                                </span>
                            </span>
                            <span v-if="column.key == 'status'">
                                <span :class="{
                                    'border-green-900 text-white bg-green-500': row.status === 'Berlangsung',
                                    'border-blue-900 text-white bg-blue-500': row.status === 'Mendatang',
                                    'border-gray-300 text-gray-600 bg-gray-50': row.status === 'Selesai',
                                }"
                                    class="inline-flex items-center gap-1 px-2 py-1 rounded-full border text-xs font-medium">
                                    {{ statusLabel(row.status) }}
                                </span>
                            </span>

                            <span v-if="column.key == 'status_hasil'">
                                <span v-if="row.status_hasil && row.skor_akhir && row.waktu_mulai" :class="{
                                    'border-green-500 text-white bg-green-400': row.status_hasil === 'Kompeten',
                                    'border-red-500 text-white bg-red-400': row.status_hasil === 'Tidak Kompeten'
                                }"
                                    class="inline-flex items-center gap-1 px-2 py-1 rounded-full border text-xs font-medium">
                                    {{ row.status_hasil }}
                                </span>
                            </span>



                            <span v-if="column.key == 'jadwal'">
                                <span v-if="row.event">
                                    {{ row.event.nama_event }}
                                </span>
                            </span>

                            <span v-if="column.key == 'kantor'">
                                <span v-if="row.kantor">
                                    {{ row.kantor.nama }}
                                </span>
                            </span>

                            <span v-if="column.key == 'kantorinduk'">
                                <span v-if="row.kantorinduk">
                                    {{ row.kantorinduk.nama }}
                                </span>
                            </span>
                            <span v-if="column.key == 'materi'">
                                <button class="text-sm text-gray-700 hover:text-gray-900 underline underline-offset-4"
                                    @click="openDetail(row)">
                                    Lihat Materi
                                </button>
                            </span>
                            <span v-if="column.key == 'created_at'">
                                <TanggalIndo :longdate="false" :tanggal="row.created_at" :time="true"></TanggalIndo>
                            </span>
                        </template>
                    </SortableTableTail>

                    <PaginationTail :pagination="pagination" @page-changed="viewItem" />
                </div>

                <!-- Modal Detail -->
                <div v-if="showModal" class="fixed inset-0 z-[9999] bg-black/40 flex items-center justify-center p-4">
                    <div class="w-full w-7/10 md:w-2/3 max-w-4xl bg-white rounded-2xl p-5">
                        <div class=" md:col-span-2">
                            <div class="text-gray-500 mb-2">Materi {{ selected?.event?.nama_event }}</div>
                            <ul class="list-disc pl-5 space-y-1">
                                <li v-for="(f, idx) in selected?.event.file_event || []" :key="idx" class="url"
                                    @click="get_cloud_url(selected.event_id, f.path)">
                                    {{ f.name }}
                                </li>
                            </ul>
                            <div v-if="(selected?.event.file_event || []).length === 0" class="text-gray-600">Tidak ada
                                file.
                            </div>
                        </div>

                        <div class="mt-5 flex justify-end gap-2">
                            <button class="px-3 py-2 rounded-lg border bg-yellow-500 text-white"
                                @click="closeDetail">Tutup</button>
                        </div>

                    </div>
                </div>

                <div v-if="showModalEditJadwal"
                    class="fixed inset-0 z-[9999] bg-black/40 flex items-center justify-center p-4">
                    <div class="w-full w-7/10 md:w-2/3 max-w-4xl bg-white rounded-2xl p-5">
                        <div class=" md:col-span-2">
                            <div class="text-gray-500 mb-2">Jadwal</div>
                        </div>
                        <div class="w-full">
                            <label class="block text-sm font-medium mb-1">Jadwal Mulai</label>
                            <div class="w-full">
                                <input type="datetime-local" v-model="formEditJadwal.jadwal_mulai"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                    placeholder="Judul..." />
                            </div>
                        </div>

                        <div class="w-full">
                            <label class="block text-sm font-medium mb-1">Jadwal Berakhir</label>
                            <div class="w-full">
                                <input type="datetime-local" v-model="formEditJadwal.jadwal_berakhir"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                    placeholder="Judul..." />
                            </div>
                        </div>

                        <div class="mt-5 flex justify-end gap-2">
                            <button class="px-3 py-2 rounded-lg border bg-yellow-500 text-white"
                                @click="showModalEditJadwal = false">Tutup</button>

                            <button class="px-3 py-2 rounded-lg border bg-blue-500 text-white"
                                @click="simpanEditJadwal()">Simpan</button>
                        </div>

                    </div>
                </div>


                <hasilUjian ref="modal" :data="soalData" :selectedEventDetail="selectedEventDetail"
                    class="py-3 bg-white" />

            </div>
        </section>
    </div>
</template>

<script>
import RequestManager from '../../helper/RequestManager';
import toastService from '../../helper/toastService';
import SortableTableTail from '../../global/SortableTableTail.vue';
import TanggalIndo from '../../global/TanggalIndo.vue';
import hasRole from '../../helper/hasRole';
import hasilUjian from './componentUjian/hasilUjian.vue';
import PaginationTail from '../../global/PaginationTail.vue';
export default {
    name: "JadwalUjianCards",
    data() {
        return {
            items: [],
            soalData: [],
            selectedEventDetail: [],
            search: "",
            filter: this.$route.params.c || "all",
            showModal: false,
            showModalEditJadwal: false,
            selected: null,
            showMeOnly: true,
            modal: null,

            searchTimeout: null,
            columns: [
                { label: "Kantor", key: "kantor", sortable: true },
                { label: "Pegawai", key: "nama_pegawai", sortable: true },
                { label: "Tanggal", key: "jadwal_mulai", sortable: true },
                { label: "Jam", key: "jadwal_berakhir", sortable: true },
                { label: "Nama Jadwal", key: "jadwal", sortable: true },
                { label: "Materi", key: "materi", sortable: true },
                { label: "Status", key: "status", sortable: true },

                { label: "Skor", key: "skor_akhir", sortable: true },
                { label: "Hasil", key: "status_hasil", sortable: true },
            ],
            pagination: {
                current_page: 1,
                last_page: 0,
                total: 0,
                per_page: 10,
                search: ''
            },
            formEditJadwal: {
                id: null,
                event_id: null,
                hash_sesi: null,
                modul: null,
                jadwal_mulai: null,
                jadwal_berakhir: null,
            }
        };
    },
    components: {
        SortableTableTail, TanggalIndo, hasilUjian, PaginationTail
    },

    mounted() {
        this.viewItem(1, true);
        this.modal = this.$refs.modal;
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
        showMeOnly() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.viewItem();
            }, 200);
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
        hasRoles(rl) {
            return hasRole(rl);
        },
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

        async viewItem(page = 1, cekActive = false) {
            this.loadingView = true;
            try {
                let parameter = {
                    action: "jadwalActive",
                    search: this.search,
                    filter: this.filter,
                    id: this.idresourches,
                    page: page,
                    per_page: this.pagination.per_page,
                    showMeOnly: this.showMeOnly ? 1 : 0,
                }
                const response = await RequestManager("_b/cources/jadwal-aktif", parameter);


                if (response.data.type === "success") {
                    this.items = response.data.data.data;
                    this.pagination.current_page = response.data.data.current_page;
                    this.pagination.total = response.data.data.total;
                    this.pagination.per_page = response.data.data.per_page;
                    this.pagination.last_page = response.data.data.last_page;
                    if (cekActive) {
                        // this.cekJadwalActive();
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
                await this.viewItem(1, false);
                return
            }

            const hasMendatang = this.items.some(it =>
                String(it?.status ?? '').toLowerCase() === 'mendatang'
            );

            if (hasMendatang) {
                const targetFilter = 'Mendatang';
                if (this.filter === targetFilter) return;
                this.filter = targetFilter;
                await this.viewItem(1, false);
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


        async tampilkanHasilUjian(row) {
            if (!row) return;
            try {
                let parameter = {
                    action: "hasilUjian",
                    sesi: row.hash_sesi,
                    userid: row.peserta.id,
                    eventid: row.event_id
                }

                this.$refs.modal.openModal();
                const response = await RequestManager("_b/cources/jadwal-aktif", parameter);
                if (response.data.type === "success") {
                    this.soalData = response.data.data;
                    this.selectedEventDetail = row;

                } else {
                    toastService.show("Error", response.data.message, "error");
                }
            } catch (error) {
                toastService.show("Error", error, "error");
            }
        },

        editJadwal(row) {
            if (!row) return;
            this.formEditJadwal = row;
            this.showModalEditJadwal = true;
        },
        async simpanEditJadwal() {
            if (!this.formEditJadwal) return;
            try {
                let parameter = {
                    action: "editJadwal",
                    id: this.formEditJadwal.id,
                    hash_sesi: this.formEditJadwal.hash_sesi,
                    jadwal_mulai: this.formEditJadwal.jadwal_mulai,
                    jadwal_berakhir: this.formEditJadwal.jadwal_berakhir,
                }
                const response = await RequestManager("_b/cources/jadwal-aktif", parameter);
                if (response.data.type === "success") {
                    toastService.show("Success", response.data.message, "success");
                    this.showModalEditJadwal = false;
                    this.formEditJadwal = {
                        id: null,
                        event_id: null,
                        hash_sesi: null,
                        modul: null,
                        jadwal_mulai: null,
                        jadwal_berakhir: null,
                    };
                    this.viewItem();
                } else {
                    toastService.show("Error", response.data.message, "error");
                }
            } catch (error) {
                toastService.show("Error", error, "error");
            }
        }
    }
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
