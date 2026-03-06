<template>

    <div v-if="showModal" class="fixed inset-0 z-[9999] bg-black/40 flex items-center justify-center p-4">
        <div class="w-full max-w-6xl bg-white rounded-2xl p-5">
            <div class=" md:col-span-2">
                <div class="text-gray-500 mb-2">Lembar Hasil Ujian</div>
                <div class="p-4 space-y-4 max-h-[75vh] overflow-y-auto">

                    <div class="mb-4">
                        <table class="w-full text-left ">
                            <tbody>

                                <tr>
                                    <td class=" px-3 w-1/6 py-1 font-medium">Nama</td>
                                    <td class=" px-3 py-1">: {{ selectedEventDetail?.peserta?.name }}</td>
                                </tr>
                                <tr>
                                    <td class=" px-3 w-1/6 py-1 font-medium">Kantor</td>
                                    <td class=" px-3 py-1">: {{ selectedEventDetail?.kantor?.nama }}</td>
                                </tr>
                                <tr>
                                    <td class=" px-3 py-1 font-medium">Tanggal Ujian</td>
                                    <td class=" px-3 py-1">:
                                        <TanggalIndo :longdate="false" :tanggal="selectedEventDetail.jadwal_mulai"
                                            :tanggal_only="true" :time="false">
                                        </TanggalIndo>
                                    </td>
                                </tr>
                                <tr>
                                    <td class=" px-3 py-1 font-medium">Waktu Ujian</td>
                                    <td class=" px-3 py-1">:
                                        <TanggalIndo :longdate="false" :tanggal="selectedEventDetail.jadwal_mulai"
                                            :time_only="true" :time="true">
                                        </TanggalIndo> - <TanggalIndo :longdate="false"
                                            :tanggal="selectedEventDetail.jadwal_berakhir" :time_only="true"
                                            :time="true">
                                        </TanggalIndo>
                                    </td>
                                </tr>
                                <tr>
                                    <td class=" px-3 py-1 font-medium">Skor</td>
                                    <td class=" px-3 py-1">: {{ selectedEventDetail.skor_akhir }}</td>
                                </tr>
                                <tr>
                                    <td class=" px-3 py-1 font-medium">Hasil</td>
                                    <td class=" px-3 py-1">: <span v-if="selectedEventDetail.status_hasil" :class="{
                                        'border-green-500 text-white bg-green-400': selectedEventDetail.status_hasil === 'Kompeten',
                                        'border-red-500 text-white bg-red-400': selectedEventDetail.status_hasil === 'Tidak Kompeten'
                                    }" class="inline-flex items-center gap-1 px-2 py-1 rounded-full border text-xs font-medium">
                                            {{ selectedEventDetail.status_hasil }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-for="(item, index) in data" :key="item.id" class="p-4 border rounded-md">
                        <!-- Soal -->
                        <small v-if="item.dikerjakan == 0" class="text-red-600 text-md font-medium">
                            Tidak dikerjakan
                        </small>
                        <h5 class="font-semibold mb-2">
                            {{ index + 1 }}. {{ item.soal }}
                        </h5>

                        <!-- Jawaban -->
                        <ul class="space-y-1">
                            <li v-for="(ops, index) in item.opsi_jawaban" :key="ops.idjawaban" class="flex gap-2">
                                {{ String.fromCharCode(65 + index) }}. <span :class="[
                                    item.jawaban_id == ops.idjawaban ? 'font-bold border border-blue-500 rounded px-1' : '',
                                    ops.skor > 0 ? 'text-green-600 border border-green-400 rounded px-1' : '',
                                    'flex items-center gap-1'
                                ]">
                                    {{ ops.jawaban_opsi }}
                                    <small v-if="item.jawaban_id == ops.idjawaban && ops.skor > 0"
                                        class="text-green-600">(✓)</small>
                                    <small v-if="item.jawaban_id == ops.idjawaban && ops.skor == 0"
                                        class="text-red-600">(❌)</small>
                                </span>
                            </li>
                        </ul>

                        <!-- Info tidak dikerjakan -->

                    </div>
                </div>
            </div>

            <div class="mt-5 flex justify-between gap-2">
                <div>
                    <span class="font-sm text-gray-300">Sesi: {{ selectedEventDetail?.hash_sesi }}</span>
                </div>
                <div class="flex gap-3">
                    <button class="px-3 py-2 rounded-lg border bg-yellow-500 text-white"
                        @click="closeModal">Tutup</button>
                    <button class="px-3 py-2 rounded-lg border bg-blue-500 text-white disabled:opacity-50"
                        @click="downloadPDF(selectedEventDetail)" :disabled="loadingDownload">
                        Download PDF</button>
                </div>


            </div>

        </div>
    </div>

</template>

<script>
import TanggalIndo from '../../../global/TanggalIndo.vue';
import RequestManager from '../../../helper/RequestManager';
import toastService from '../../../helper/toastService';
export default {
    props: {
        data: {
            type: Array,
            required: true
        },
        selectedEventDetail: {
            type: Array,
            required: false
        }
    },
    data() {
        return {
            showModal: false,
            loadingDownload: false
        };
    },
    components: {
        TanggalIndo
    },
    methods: {
        openModal() {
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
        },

        async downloadPDF(postdata) {

            let parameter = {
                action: "get_pdf_hasil_ujian",
                hash_sesi: postdata.hash_sesi,
                userid: postdata.peserta.id
            };
            if (this.loadingDownload) {
                return; // Prevent multiple clicks
            }

            this.loadingDownload = true;

            const response = await RequestManager(
                "_b/cources/jadwal-aktif",
                parameter
            )
                .then((response) => {
                    this.loadingDownload = false;
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
                    this.loadingDownload = false;
                    this.load_cloud_url = null;
                });
        },

    }
};
</script>
