<template>
    <div id="exam-container" class="bg-gray-50 p-4">
        <div class=" max-w-6xl mx-auto flex flex-col lg:flex-row mb-4 gap-6">
            <!-- LEFT -->
            <div class="flex-1">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="text-lg font-semibold">Ujian</div>
                        <div class="text-sm text-gray-600">
                            Hal {{ page }} / {{ lastPage }} • Terjawab {{ answeredCount }} / {{ total }}
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button class="px-2 py-1 rounded-lg border border-blue-200 bg-white"
                            :disabled="page <= 1 || loadingPage" @click="goPage(page - 1)">
                            Prev
                        </button>
                        <button class="px-2 py-1 rounded-lg border border-blue-200 bg-white"
                            :disabled="page >= lastPage || loadingPage" @click="goPage(page + 1)">
                            Next
                        </button>
                    </div>
                </div>

                <div v-if="loadingPage" class="p-4 bg-white rounded-2xl border">Loading soal...</div>

                <div v-else class="space-y-4">

                    <div v-for="q in pageItems" :key="q.id" class="bg-white border rounded-2xl p-4">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-2 md:gap-3">

                            <!-- Status -->
                            <span
                                class="text-xs px-2 py-1 rounded-full border self-start md:self-auto order-1 md:order-2 self-end"
                                :class="answers[q.id]
                                    ? 'bg-green-200 border-green-200 text-green-700'
                                    : 'bg-gray-50 border-gray-200 text-gray-600'">
                                {{ answers[q.id] ? 'Terjawab' : 'Belum' }}
                            </span>

                            <!-- Soal -->
                            <div class="font-semibold text-gray-900 order-2 md:order-1">
                                {{ q.order_number }}. {{ q.soal }}
                            </div>

                        </div>
                        <div class="mt-3 space-y-2 w-full">
                            <label v-for="opt in q.opsi_jawaban" :key="opt.idjawaban"
                                class="flex items-start gap-2 p-2 rounded-xl hover:bg-gray-50 cursor-pointer">
                                <input type="radio" class="mt-1" :name="'q_' + q.id" :value="opt.idjawaban"
                                    v-model="answers[q.id]" />
                                <div class="text-sm text-gray-900">{{ opt.jawaban_opsi }}</div>
                            </label>
                            <div class="w-full flex justify-end">
                                <a class="text-blue-500 hover:text-blue-600 url text-sm"
                                    @click="resetJawaban(q.id)">Reset Jawaban</a>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <!-- <div class="text-sm text-gray-600">
                            Soal halaman ini: {{ pageItems.length }}
                        </div> -->
                        <button
                            class="px-3 py-1 rounded-xl bg-blue-600 text-white text-md hover:bg-blue-500 disabled:opacity-50"
                            :disabled="saving" @click="saveAndNext">
                            {{ saving ? 'Menyimpan...' : 'Simpan & Lanjut' }}
                        </button>
                        <div class="flex gap-2">
                            <button class="px-2 py-1 rounded-lg border border-blue-200 bg-white"
                                :disabled="page <= 1 || loadingPage" @click="goPage(page - 1)">
                                Prev
                            </button>
                            <button class="px-2 py-1 rounded-lg border border-blue-200 bg-white"
                                :disabled="page >= lastPage || loadingPage" @click="goPage(page + 1)">
                                Next
                            </button>
                        </div>


                    </div>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="w-full mb-4 lg:w-64 lg:shrink-0">
                <div class="bg-white border rounded-2xl p-4 lg:sticky lg:top-4">
                    <div class="font-semibold mb-3">Navigasi Soal</div>

                    <div v-if="loadingNav" class="text-sm text-gray-600">Loading navigasi...</div>

                    <div v-else class="grid grid-cols-4 sm:grid-cols-5 gap-2">
                        <div class="text-sm text-gray-600 w-full col-span-5 mb-2">
                            Halaman {{ page }} / {{ lastPage }} • Terjawab {{ answeredCount }} / {{ total }}
                        </div>
                        <button v-for="n in nav" :key="n.id" class="w-10 h-10 rounded-lg border text-sm"
                            :class="navBtnClass(n)" @click="goToOrder(n.order_number)">
                            {{ n.order_number }}
                        </button>
                    </div>

                    <div class="mt-4 text-xs text-gray-600 space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded bg-green-500 inline-block"></span> Terjawab
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded bg-gray-300 inline-block"></span> Belum dikerjakan
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded border border-blue-500 border-2 inline-block"></span> Halaman
                            aktif
                        </div>
                    </div>

                    <div class="mt-4 text-xs text-gray-600 space-y-1">
                        <div class="timer-ujian">
                            ⏱️
                            {{ waktuUjian.jam.toString().padStart(2, '0') }} :
                            {{ waktuUjian.menit.toString().padStart(2, '0') }} :
                            {{ waktuUjian.detik.toString().padStart(2, '0') }}
                        </div>
                    </div>



                    <div class="mt-4 mb-4 text-xs text-gray-600 space-y-1">
                        <button
                            class="bg-orange-500 text-white hover:bg-orange-400 border-orange-300 w-full h-10 rounded-lg border text-md mb-4"
                            @click="confirmAkhiriUjian()">
                            Akhiri Ujian
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>

import RequestManager from "../../helper/RequestManager";
import toastService from "../../helper/toastService";
import Swal from 'sweetalert2';
import { nextTick } from 'vue';
export default {
    data() {
        return {
            nav: [],
            pageItems: [],
            answers: {},

            page: 1,
            perPage: 5,
            lastPage: 1,
            total: 0,

            loadingNav: false,
            loadingPage: false,
            saving: false,
            sisaDetik: 0,
            timer: null
        };
    },
    computed: {
        hashSesi() {
            return this.$route.params.hash_sesi;
        },
        answeredCount() {
            // pakai nav supaya cepat (status semua soal)
            return this.nav.filter(n => (n.jawaban_id && n.jawaban_id !== 0) || this.answers[n.id]).length;
        },
        waktuUjian() {
            const total = Math.max(0, Math.floor(this.sisaDetik))

            // BATASI agar jam tidak lebih dari 99
            const jam = Math.floor(total / 3600) % 100
            const menit = Math.floor((total % 3600) / 60)
            const detik = total % 60

            const format2 = (n) => String(n).padStart(2, '0').slice(-2)

            return {
                jam: format2(jam),
                menit: format2(menit),
                detik: format2(detik)
            }
        },
    },

    async mounted() {
        await this.fetchNav();
        await this.fetchPage(1);
    },
    methods: {
        resetJawaban(qid) {
            this.answers[qid] = null;
        },
        async fetchNav() {
            this.loadingNav = true;
            try {
                const parameter = { action: "nav", hash_sesi: this.hashSesi };
                const res = await RequestManager("_b/cources/exam", parameter);

                if (res.data.type === "success") {
                    this.nav = res.data.data.nav || [];
                    this.total = res.data.data.total || this.nav.length;
                    this.sisaDetik = res.data.data.sisa_waktu_detik
                    this.startTimer()
                } else {
                    toastService.show("Error", res.data.message, "error");
                }
            } catch (e) {
                toastService.show("Error", String(e), "error");
            } finally {
                this.loadingNav = false;
            }
        },

        startTimer() {
            if (this.timer) return

            this.timer = setInterval(() => {
                if (this.sisaDetik > 0) {
                    this.sisaDetik--
                } else {
                    clearInterval(this.timer);
                    this.akhiriUjian()
                }
            }, 1000)
        },


        async fetchPage(page) {
            this.loadingPage = true;
            try {
                const parameter = {
                    action: "page",
                    hash_sesi: this.hashSesi,
                    page,
                    per_page: this.perPage,
                };
                const res = await RequestManager("_b/cources/exam", parameter);

                if (res.data.type === "success") {
                    const data = res.data.data;
                    this.pageItems = data.items || [];
                    this.page = data.meta.current_page;
                    this.perPage = data.meta.per_page;
                    this.lastPage = data.meta.last_page;
                    this.total = data.meta.total;
                    this.pageItems.forEach(q => {
                        if (q.jawaban_id && q.jawaban_id !== 0) {
                            this.answers[q.id] = q.jawaban_id;
                        }
                    });

                    // Scroll to top of the correct content area
                    setTimeout(() => {
                        nextTick(() => {
                            const contentArea = document.querySelector('.content-area');
                            if (contentArea) {
                                contentArea.scrollTo({ top: 0, behavior: 'smooth' });
                            } else {
                                window.scrollTo({ top: 0, behavior: 'smooth' });
                            }
                        })
                    }, 1000);

                } else if (res.data.type == "warning") {
                    toastService.show("Warning", res.data.message, res.data.type);
                    this.$router.push("/course/jadwal-aktif");

                } else {
                    toastService.show("Error", res.data.message, "error");
                }
            } catch (e) {
                toastService.show("Error", String(e), "error");
            } finally {
                this.loadingPage = false;
            }
        },

        goPage(p) {
            if (p < 1 || p > this.lastPage) return;
            this.fetchPage(p);
        },

        goToOrder(orderNumber) {
            this.saveAndNext(false);
            const targetPage = Math.ceil(orderNumber / this.perPage);
            this.goPage(targetPage);
        },

        navBtnClass(n) {
            const inPage = n.order_number > (this.page - 1) * this.perPage &&
                n.order_number <= this.page * this.perPage;

            const answered = !!this.answers[n.id] ||
                (n.jawaban_id && n.jawaban_id !== 0) ||
                (n.dikerjakan == 1);

            const notAnswered = !answered;

            // background default
            let bgClass = "bg-gray-400 text-gray-700";

            if (notAnswered) {
                bgClass = "bg-gray-400 text-white";
            } else if (answered) {
                bgClass = "bg-green-500 text-white";
            }

            // border
            const borderClass = inPage ? "border-2 border-blue-500" : "border-gray-300";

            return `${bgClass} border ${borderClass}`;
        },

        async saveAndNext(next = true) {
            const payload = this.pageItems
                // .filter(q => this.answers[q.id])
                .map(q => ({ release_id: q.id, idjawaban: this.answers[q.id] ?? null }));
            console.log(payload);


            if (payload.length === 0) {
                // toastService.show("Info", "Belum ada jawaban dipilih di halaman ini.", "info");
                return;
            }

            this.saving = true;
            try {
                const parameter = {
                    action: "saveAnswers",
                    hash_sesi: this.hashSesi,
                    answers: payload,
                };

                const res = await RequestManager("_b/cources/exam", parameter);

                if (res.data.type === "success") {
                    // refresh nav supaya status tombol update (ringan)
                    this.fetchNav();

                    // next page
                    if (next && this.page < this.lastPage) {
                        await this.fetchPage(this.page + 1);

                    }
                    next

                    // toastService.show("Success", res.data.message, "success");

                } else if (res.data.type == "warning") {
                    toastService.show("Warning", res.data.message, res.data.type);
                    this.$router.push("/course/jadwal-aktif");

                } else {
                    toastService.show("Error", res.data.message, "error");
                }
            } catch (e) {
                toastService.show("Error", String(e), "error");
            } finally {
                this.saving = false;
            }
        },

        async confirmAkhiriUjian() {
            const terjawab = this.answeredCount;
            const totalSoal = this.total;
            const sisa = totalSoal - terjawab;

            const icon = sisa > 0 ? 'warning' : 'success';

            let htmlPesan = '';

            if (sisa > 0) {
                // MASIH ADA SOAL BELUM DIKERJAKAN
                htmlPesan = `
            <div style="text-align:left; font-size:14px; line-height:1.6;">
                <p style="margin-bottom:12px;">
                    <strong>Perhatian</strong>,
                </p>

                <p>
                    Anda telah menjawab
                    <strong>${terjawab}</strong> dari
                    <strong>${totalSoal}</strong> soal.
                </p>

                <p>
                    Masih terdapat
                    <strong style="color:#d33;">${sisa} soal</strong>
                    yang <strong>belum dikerjakan</strong>.
                </p>

                <hr style="margin:12px 0;">

                <p>Apabila ujian diakhiri sekarangn, soal yang belum dikerjakan akan dianggap <strong>tidak dijawab</strong>.Seluruh jawaban akan <strong>dikunci</strong> dan tidak dapat diubah kembali.</p>
                </ul>

                <p style="margin-top:12px;">
                    Apakah Anda <strong>yakin</strong> ingin mengakhiri ujian sekarang?
                </p>
            </div>
        `;
            } else {
                // SEMUA SOAL SUDAH DIKERJAKAN
                htmlPesan = `
            <div style="text-align:left; font-size:14px; line-height:1.6;">
                <p>
                    Seluruh soal telah <strong>selesai dikerjakan</strong>.
                </p>

                <p>
                    Anda telah menjawab
                    <strong>${totalSoal}</strong> dari
                    <strong>${totalSoal}</strong> soal.
                </p>

                <hr style="margin:12px 0;">
                    <p>Seluruh jawaban akan <strong>dikunci</strong> dan tidak dapat diubah kembali</p>
                </ul>

                <p style="margin-top:12px;">
                    Apakah Anda yakin ingin <strong>mengakhiri ujian dan mengirim jawaban</strong>?
                </p>
            </div>
        `;
            }

            const result = await Swal.fire({
                title: 'Konfirmasi Akhiri Ujian',
                icon: icon,
                html: htmlPesan,
                input: 'text',
                inputPlaceholder: 'Ketik "ya" untuk mengakhiri ujian',
                showCancelButton: true,
                confirmButtonColor: '#e25151',
                cancelButtonColor: '#0d6efd',
                confirmButtonText: 'Akhiri Ujian',
                cancelButtonText: 'Kembali Mengerjakan',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    const validation = document.querySelector('.swal2-validation-message');
                    if (validation) {
                        validation.style.backgroundColor = "#fff";
                        validation.style.color = '#c13614';
                    }
                },
                preConfirm: (value) => {
                    if (!value) {
                        Swal.showValidationMessage('Silakan ketik <b style="font-size:22px; margin-left:10px; margin-right:10px;"> ya </b> terlebih dahulu');
                        return false;
                    }

                    const val = value.toLowerCase().trim();

                    if (val !== 'ya') {
                        Swal.showValidationMessage(
                            'Untuk mengakhiri ujian, Anda harus mengetik: <b style="font-size:22px; margin-left:10px; margin-right:10px;"> ya </b>'
                        );
                        return false;
                    }

                    return true;
                }
            });

            if (result.isConfirmed) {
                await this.saveAndNext(false); // simpan jawaban halaman aktif
                this.akhiriUjian();
            }
        },


        async akhiriUjian() {
            try {
                const parameter = {
                    action: "akhiriUjian",
                    hash_sesi: this.hashSesi,
                };

                const res = await RequestManager("_b/cources/exam", parameter);

                if (res.data.type === "success") {
                    toastService.show("Success", res.data.message, "success");
                    this.$router.push("/course/jadwal-aktif");
                } else {
                    toastService.show("Error", res.data.message, "error");
                }
            } catch (e) {
                toastService.show("Error", String(e), "error");
            }
        },

    },

};
</script>

<style scoped>
.timer-ujian {
    font-size: large;
}

.no-scrollbar::-webkit-scrollbar {
    display: none;
    /* Chrome, Safari */
}

.no-scrollbar {
    -ms-overflow-style: none;
    /* IE & Edge */
    scrollbar-width: none;
    /* Firefox */
}
</style>