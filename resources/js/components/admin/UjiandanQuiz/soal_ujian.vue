<template>
    <div>
        <section class="py-2 px-4 sm:px-2 lg:px-8">
            <div class="max-w-8xl mx-auto">
                <div class="w-full rounded  p-0">
                    <div class="flex flex-wrap w-full justify-between items-start gap-3 mb-3">
                        <div class="flex flex-wrap w-full sm:w-1/2 items-start gap-3 mb-3">

                            <button @click="showModal" class="btn-primary text-white text-sm px-3 py-2 rounded">
                                Tambah Manual
                            </button>
                            <button @click="showModalImportSoal"
                                class="bg-green-600 text-white text-sm px-3 py-2 rounded ml-2">
                                Import/ Export Soal
                            </button>
                            <button @click="viewData" class="bg-blue-600 text-white text-sm px-3 py-2 rounded ml-2">
                                Reload
                            </button>


                        </div>
                        <div class="flex flex-wrap items-start gap-3 mb-3 w-full">
                            <div class="sm:w-1/8">
                                <select class="w-full border rounded px-3 py-2" v-model="pagination.per_page"
                                    @change="viewData()">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="200">200</option>
                                </select>
                            </div>
                            <div class="w-full sm:w-1/4">
                                <select v-model="selectedModule" @change="viewData(1)"
                                    class="w-full border rounded px-3 py-2">
                                    <option value="" disabled>-- Pilih Modul Ujian --</option>
                                    <option v-for="modul in optionModule" :key="modul.id" :value="modul.id">{{
                                        modul.nama_modul }}</option>
                                </select>
                            </div>
                            <div class="w-full sm:w-1/5">
                                <select v-model="selectedKategori" @change="viewData(1)"
                                    class="w-full border rounded px-3 py-2">
                                    <option value="">-- Semua Kategori Soal --</option>
                                    <option v-for="sesi in parsedSesiSoal" :key="sesi.sesi" :value="sesi.sesi">{{
                                        sesi.sesi
                                        }}</option>
                                </select>
                            </div>
                            <div class="w-full sm:w-1/4">
                                <input type="text" v-model="pagination.search" class="w-full border rounded px-3 py-2"
                                    placeholder="Pencarian..">
                            </div>
                            <div class="w-full sm:w-auto  items-center">
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" v-model="showjawaban"
                                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span>Tampilkan Jawaban</span>
                                </label>
                                <div class="flex items-center space-x-3">
                                    <button @click="toggleMode" :class="[
                                        'w-10 h-5 mt-1 flex items-center rounded-full p-1 transition-colors duration-300',
                                        modeTampilSoal === 'table' ? 'bg-blue-600' : 'bg-gray-300'
                                    ]">
                                        <span :class="[
                                            'bg-white w-4 h-4 rounded-full shadow-md transform transition-transform duration-300',
                                            modeTampilSoal === 'table' ? 'translate-x-6' : 'translate-x-0'
                                        ]"></span>
                                    </button>
                                    <span class="text-sm text-gray-600">Tampil mode {{ modeTampilSoal }}</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <SkeletonTable v-if="loadingView" :rows="10" :columns="fields.length" />
                    <div v-else>
                        <div v-if="modeTampilSoal == 'table'">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th v-for="field in fields" :key="field.key" scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ typeof field === 'object' ? field.label : field }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="item in items" :key="item.soal.id">
                                        <td class="px-6 py-4 whitespace-normal">
                                            <div class="text-sm text-gray-900 wrap-text">{{ item.soal.soal }}</div>
                                            <div class="flex flex-wrap gap-2 mt-2">
                                                <img v-for="img in item.soal.url_fotos" :key="img"
                                                    @click="showModalPrefiewImage(img)" :src="img"
                                                    class="max-w-[100px] cursor-pointer hover:opacity-75">
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{
                                            item.soal.kategori_sesi }}</td>
                                        <td v-for="x in 5" :key="x" class="px-6 py-4 whitespace-normal">
                                            <div v-if="item.jawaban[x - 1]">
                                                <div class="text-sm text-gray-900 wrap-text">{{
                                                    item.jawaban[x - 1].jawaban_opsi }}</div>
                                                <div v-if="item.jawaban[x - 1].images_url"
                                                    class="flex flex-wrap gap-2 mt-2">
                                                    <img v-for="imgj in item.jawaban[x - 1].images_url" :key="imgj"
                                                        @click="showModalPrefiewImage(imgj)" :src="imgj"
                                                        class="max-w-[100px] cursor-pointer hover:opacity-75">
                                                </div>
                                                <span v-if="showjawaban" :class="item.jawaban[x - 1]['skor'] != '0'
                                                    ? 'bg-blue-500 text-white text-xs font-medium mr-2 px-2.5 py-0.5 rounded'
                                                    : 'bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded'
                                                    ">Skor: {{ item.jawaban[x - 1]["skor"] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">

                                            <div class="relative inline-block text-left group">
                                                <button
                                                    class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-gray-800 rounded-md">
                                                    Action
                                                    <svg class="w-5 h-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M10 12l-4-4h8l-4 4z" />
                                                    </svg>
                                                </button>

                                                <div
                                                    class="invisible group-focus-within:visible opacity-0 group-focus-within:opacity-100 transition-all duration-300 absolute right-0 w-48 mt-2 border rounded-md shadow-xl z-50 bg-gray-100">
                                                    <a href="#"
                                                        class="block px-4 py-2 text-sm text-black hover:bg-green-800 hover:text-white"
                                                        @click="editData(item, 'update')">Edit</a>

                                                    <a href="#"
                                                        class="block px-4 py-2 text-sm text-black hover:bg-red-500 hover:text-white"
                                                        @click="editData(item, 'delete')">Delete</a>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else>
                            <div v-for="q in items" :key="q.id" class="mb-3 border rounded-2xl p-4 w-full">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="font-semibold text-gray-900">
                                        # {{ q.soal.soal }}
                                    </div>
                                </div>
                                <div class="mt-3 space-y-2">
                                    <label v-for="(opt, index) in q.jawaban" :key="opt.idjawaban" :class="[
                                        'flex gap-2 p-2 rounded-xl border transition',
                                        opt.skor == '1'
                                            ? 'bg-green-50 border-green-400'
                                            : 'border-gray-100 hover:bg-indigo-50 hover:border-indigo-200'
                                    ]">
                                        <div :class="[
                                            'w-6 h-6 flex items-center justify-center rounded-full font-semibold text-sm',
                                            opt.skor == '1'
                                                ? 'bg-green-500 text-white'
                                                : 'bg-indigo-100 text-indigo-700'
                                        ]">
                                            {{ String.fromCharCode(65 + index) }}
                                        </div>
                                        <div class="flex-1 text-sm leading-relaxed"
                                            :class="opt.skor == '1' ? 'text-green-700 font-medium' : 'text-gray-900'">
                                            {{ opt.jawaban_opsi }}
                                        </div>
                                        <div v-if="opt.skor == '1'" class="text-green-600 font-bold">
                                            ✔
                                        </div>
                                    </label>
                                </div>

                                <div class="w-full flex gap-3 mt-4 justify-end">
                                    <a href="#"
                                        class="px-4 py-2 text-sm text-white bg-blue-500 rounded-xl hover:bg-blue-400 hover:text-white"
                                        @click="editData(q, 'update')">Edit</a>

                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-white rounded-xl bg-red-500 hover:bg-red-400 hover:text-white"
                                        @click="editData(q, 'delete')">Delete</a>
                                </div>


                            </div>
                        </div>

                        <PaginationTail :pagination="pagination" @page-changed="viewData" />
                    </div>
                </div>
            </div>
        </section>


        <!-- Modal Soal -->
        <div class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center" v-if="soalModal"
            ref="modal">
            <div class="bg-white rounded-lg w-11/12 md:w-2/3 max-w-4xl overflow-auto max-h-[90vh]">
                <form @submit.prevent="postData()">
                    <div class="border-b px-6 py-4 flex justify-between items-center">
                        <h5 class="text-lg font-semibold">Soal Ujian</h5>
                        <button type="button" @click="hideModal()" class="text-gray-500 hover:text-gray-700"
                            aria-label="Close">✖</button>
                    </div>
                    <div class="px-6 py-4 bg-white text-black">
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Modul Ujian</label>
                            <select v-model="selectedModule" @change="sesisoal_handler"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option disabled value="">-- Pilih Modul Ujian --</option>
                                <option v-for="mod in optionModule" :key="mod.id" :value="mod.id">{{ mod.nama_modul }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Semua Sesi Soal</label>
                            <select v-model="formFied.selectedSesi" @change="sesisoal_handler"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option disabled value="">--Sesi Soal --</option>
                                <option v-for="sesi in optionSesi" :key="sesi.value" :value="sesi.value">{{ sesi.text }}
                                </option>
                            </select>
                        </div>

                        <div :class="['mb-4', tampilkanSoal]">
                            <label class="block text-sm font-medium mb-1">Soal</label>
                            <textarea v-model="formFied.soal" placeholder="Soal.." @input="validateForm"
                                :class="['w-full border border-gray-300 rounded-md px-3 py-2', errorsField.soal ? 'border-red-500' : '']"></textarea>
                            <label class="block text-sm font-medium mb-1 mt-2">Gambar pendukung soal (jika ada)</label>
                            <input type="file" ref="fileInputs" @change="handleGambarSoalChange" accept="image/*"
                                multiple
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none file:mr-4 file:py-1 file:px-1 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>

                        <div class="space-y-4">
                            <div v-for="n in jumlah_opsi" :key="`opsi-${n}`" class="border rounded-md p-4">
                                <p class="font-semibold mb-2">Opsi Jawaban {{ n }}</p>
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                                    <div class="md:col-span-6">
                                        <label class="block text-sm font-medium mb-1">Teks Jawaban</label>
                                        <input v-model="formFied.jawaban[n - 1]" type="text" placeholder="Opsi Jawaban"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2">
                                    </div>
                                    <div class="md:col-span-4">
                                        <label class="block text-sm font-medium mb-1">Gambar (jika ada)</label>
                                        <input type="file" @change="onFileJawabanChange($event, n - 1)"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none file:mr-4 file:py-1 file:px-1 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    </div>
                                    <div :class="['md:col-span-2', tampilkanSoal]">
                                        <label class="block text-sm font-medium mb-1">Skor</label>
                                        <input v-model="formFied.skor[n - 1]" type="text" placeholder="Skor"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="border-t px-6 py-4 flex justify-end gap-2">
                        <div v-if="loadingAct" class="spinner-grow text-info" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded"
                            @click="hideModal()">Tutup</button>
                        <button v-if="btnActionMode == 'delete'" @click="deleteData" type="button"
                            class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
                        <button v-if="btnActionMode != 'delete'" @click="postData" type="button"
                            class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Import/Export -->
        <div class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center" v-if="importSoalModal">
            <div class="bg-white rounded-lg w-full md:w-1/2 max-w-lg">
                <div class="border-b px-6 py-4 flex justify-between items-center">
                    <h5 class="text-lg font-semibold">Import/ Export Soal Ujian</h5>
                    <button type="button" @click="importSoalModal = false" class="text-gray-500 hover:text-gray-700"
                        aria-label="Close">✖</button>
                </div>
                <div class="px-6 py-4">
                    <div class="mb-4">
                        <div class="flex justify-center space-x-2">
                            <button @click="modeImportExport = 'Import'"
                                :class="['px-4 py-2 rounded', modeImportExport === 'Import' ? 'bg-blue-600 text-white' : 'bg-gray-200']">Import</button>
                            <button @click="modeImportExport = 'Export'"
                                :class="['px-4 py-2 rounded', modeImportExport === 'Export' ? 'bg-blue-600 text-white' : 'bg-gray-200']">Export</button>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Modul Ujian</label>
                        <select v-model="formImportSoal.selectedModule"
                            class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option disabled value="">-- Pilih Modul Ujian --</option>
                            <option v-for="mod in optionModule" :key="mod.id" :value="mod.id">{{ mod.nama_modul }}
                            </option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Sesi Soal</label>
                        <select v-model="formImportSoal.selectedSesi"
                            class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option disabled value="">-- Semua Sesi --</option>
                            <option v-for="sesi in parsedSesiSoal" :key="sesi.sesi" :value="sesi.sesi">{{ sesi.sesi }}
                            </option>
                        </select>
                    </div>
                    <div class="mb-4" v-if="modeImportExport === 'Import'">
                        <label class="block text-sm font-medium mb-1">File ({{ fileAcceptImport }})</label>
                        <input type="file" :accept="fileAcceptImport" :disabled="!formImportSoal.selectedModule"
                            @change="handleFileSoalChange($event)"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                </div>
                <div class="border-t px-6 py-4 flex justify-end gap-2">
                    <div v-if="loadingPostImportSoal" class="spinner-grow text-info" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <button type="button" @click="importSoalModal = false"
                        class="bg-gray-500 text-white px-4 py-2 rounded">Tutup</button>
                    <button v-if="modeImportExport === 'Import'" @click="postImportSoal()"
                        class="bg-blue-600 text-white px-4 py-2 rounded">Import</button>
                    <button v-if="modeImportExport === 'Export'" @click="postExportSoal()"
                        class="bg-green-600 text-white px-4 py-2 rounded">Export</button>
                </div>
            </div>
        </div>


        <!-- Modal Preview Image -->
        <div class="fixed inset-0 z-50 bg-black bg-opacity-75 flex items-center justify-center"
            v-if="previewImageModal">
            <div class="bg-white rounded-lg p-4 max-w-3xl max-h-[80vh] relative">
                <button @click="previewImageModal = false"
                    class="absolute -top-4 -right-4 bg-white rounded-full p-1 text-black">✖</button>
                <img :src="preview.img" class="max-w-full max-h-full object-contain">
            </div>
        </div>

    </div>
</template>

<script>
import RequestManager from "../../helper/RequestManager";
import toastService from "../../helper/toastService";
import SkeletonTable from '@/components/global/SkeletonTable.vue';
import PaginationTail from '@/components/global/PaginationTail.vue';

const PAGE_URL = "_a/course/soal";
export default {
    components: {
        SkeletonTable,
        PaginationTail,
    },
    data() {
        return {
            soalModal: false,
            importSoalModal: false,
            previewImageModal: false,
            loadingView: false,
            loadingPostImportSoal: false,
            showjawaban: false,
            modeTampilSoal: "list",
            formFied: {
                soal: "",
                gambarSoal: [],
                selectedSesi: "",
                jawaban: [],
                jawabanGambar: [],
                jawabanid: [],
                skor: [],
                id: null,
            },
            errorsField: {},
            loadingAct: false,
            btnActionMode: null,
            items: [],
            fields: [
                { key: "soal", label: "Soal" },
                { key: "kategori_sesi", label: "Sesi Soal" },
                { key: "jawaban1", label: "Opsi 1" },
                { key: "jawaban2", label: "Opsi 2" },
                { key: "jawaban3", label: "Opsi 3" },
                { key: "jawaban4", label: "Opsi 4" },
                "Aksi",
            ],
            optionSesi: [],
            selectedModule: "",
            selectedKategori: "",
            optionModule: [],
            mapingCharacter: ['a', 'b', 'c', 'd', 'e', 'f', 'g'],
            keyTableMoneyTest: ['gengsi', 'boros', 'konflik', 'kacau', 'martir', 'hemat_ketat', 'tunai', 'pengumpul'],
            keyFormMoneyTest: {},
            formImportSoal: {
                selectedModule: "",
                selectedSesi: "",
                fileSoal: null
            },
            pagination: {
                current_page: 1,
                last_page: 0,
                total: 0,
                per_page: 10,
                search: ''
            },
            jumlah_opsi: 0,
            modeImportExport: "Import",
            preview: {
                img: ""
            },
            selectedOption: null,
            parsedSesiSoal: [],
        };
    },
    computed: {

        toggleMode() {
            this.modeTampilSoal =
                this.modeTampilSoal === 'list' ? 'table' : 'list'
        },
        tampilkanSoal() {
            if (this.formFied.selectedSesi == 'APT') {
                return 'hidden';
            } else {
                return '';
            }
        },

        fileAcceptImport() {
            let umum_txt = ["MPT", "APT"];
            if (umum_txt.includes(this.formImportSoal.selectedSesi)) {
                return ".xlsx";
            } else {
                return ".txt"
            }
        }
    },
    watch: {
        selectedModule(newVal) {
            this.updateParsedSesiSoal(newVal);
        },
        "formImportSoal.selectedModule"(newVal) {
            this.updateParsedSesiSoal(newVal);
        },
        soalModal(newVal) {
            if (!newVal) {
                this.onModalHidden();
            }
        },
        importSoalModal(newVal) {
            if (!newVal) {
                this.onModalHidden();
            }
        },

        'pagination.search'(newVal, oldVal) {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.viewData(1);
            }, 1500);
        }

    },
    mounted() {
        this.viewModule();
        this.viewAttribute();
    },
    methods: {
        updateParsedSesiSoal(moduleId) {
            this.selectedOption = this.optionModule.find((m) => m.id === moduleId);
            if (this.selectedOption && this.selectedOption.pembobotan) {
                try {
                    this.parsedSesiSoal = this.selectedOption.pembobotan;
                } catch (e) {
                    console.error("JSON parsing error:", e);
                    this.parsedSesiSoal = [];
                }
            } else {
                this.parsedSesiSoal = [];
            }
        },
        handleGambarSoalChange(event) {
            this.formFied.gambarSoal = event.target.files;
        },
        sesisoal_handler() {
            this.formFied.skor = [];
            if (this.formFied.selectedSesi == 'MPT') {
                this.jumlah_opsi = 5;
                for (let ix = 0; ix < this.jumlah_opsi; ix++) {
                    this.formFied.skor[ix] = this.mapingCharacter[ix];
                }
            } else if (this.formFied.selectedSesi == 'APT') {
                this.jumlah_opsi = 2;
                for (let ix = 0; ix < this.jumlah_opsi; ix++) {
                    this.formFied.skor[ix] = '';
                }
            } else { // ATI and others
                this.jumlah_opsi = 4;
                for (let ix = 0; ix < this.jumlah_opsi; ix++) {
                    this.formFied.skor[ix] = '';
                }
            }
        },
        handleFileSoalChange(event) {
            let fileSoal = event.target.files[0];
            if (fileSoal) {
                this.formImportSoal.fileSoal = fileSoal;
            }
        },
        onFileJawabanChange(event, index) {
            const file = event.target.files[0];
            if (!this.formFied.jawabanGambar) {
                this.formFied.jawabanGambar = [];
            }
            this.formFied.jawabanGambar[index] = file || null;
        },
        showModalImportSoal() {
            this.btnActionMode = "add";
            this.importSoalModal = true;
        },
        showModalPrefiewImage(img) {
            this.preview.img = img;
            this.previewImageModal = true;
        },
        showModal() {
            this.resetForm();
            this.btnActionMode = "add";
            this.soalModal = true;
        },
        hideModal() {
            this.soalModal = false;
            this.resetForm();
        },
        resetForm() {
            this.formFied = {
                soal: "",
                gambarSoal: [],
                selectedSesi: "",
                jawaban: [],
                jawabanid: [],
                jawabanGambar: [],
                skor: [],
                id: null,
            }
            this.keyFormMoneyTest = {}
            if (this.$refs.fileInputs) {
                if (Array.isArray(this.$refs.fileInputs)) {
                    this.$refs.fileInputs.forEach(el => (el.value = ""))
                } else {
                    this.$refs.fileInputs.value = ""
                }
            }
        },
        onModalHidden() {
            this.resetForm();
        },
        async viewAttribute(search) {
            let parameter = {
                action: "attribute",
                column: ["general_sesi_soal"],
                search: search,
            };
            try {
                const response = await RequestManager("global/attribute", parameter);
                if (response.data.type == "success") {
                    this.optionSesi = response.data.data.general_sesi_soal;
                } else {
                    toastService.show("Gagal", response.data.message, "error");
                }
            } catch (error) {
                toastService.show("Error", "Gagal memuat atribut", "error");
            }
        },
        editData(data, act) {
            this.btnActionMode = act;
            this.formFied.id = data.soal["id"];
            this.formFied.selectedSesi = data.soal["kategori_sesi"];
            this.formFied.soal = data.soal["soal"];
            this.jumlah_opsi = data.jawaban.length;

            this.formFied.jawaban = data.jawaban.map(j => j.jawaban_opsi);
            this.formFied.jawabanid = data.jawaban.map(j => j.idjawaban);
            this.formFied.skor = data.jawaban.map(j => j.skor);

            this.soalModal = true;
            this.validateForm();
        },
        validateForm() {
            this.errorsField = {};
            if (!this.selectedModule) {
                this.errorsField.selectedModule = "Modul is required.";
            }
            return Object.keys(this.errorsField).length === 0;
        },
        async viewModule() {
            this.loadingView = true;
            let parameter = { action: "viewModule" };
            try {
                const response = await RequestManager(PAGE_URL, parameter);
                if (response.data.type == "success") {
                    this.optionModule = response.data.data;
                } else {
                    toastService.show("Gagal", response.data.message, "error");
                }
            } catch (err) {
                toastService.show("Error", "Gagal memuat modul", "error");
            } finally {
                this.loadingView = false;
            }
        },
        async viewData(page = 1) {
            this.loadingView = true;
            let parameter = {
                action: "view",
                selectedModule: this.selectedModule,
                kategori: this.selectedKategori,
                page: page,
                perPage: this.pagination.per_page,
                search: this.pagination.search
            };

            try {
                const response = await RequestManager(PAGE_URL, parameter);
                if (response.data.type == "success") {
                    this.items = response.data.data.data;
                    this.pagination.current_page = response.data.data.current_page;
                    this.pagination.total = response.data.data.total;
                    this.pagination.per_page = response.data.data.per_page;
                    this.pagination.last_page = response.data.data.last_page;
                } else {
                    toastService.show("Gagal", response.data.message, "error");
                }
            } catch (err) {
                toastService.show("Error", "Gagal memuat data soal", "error");
            } finally {
                this.loadingView = false;
            }
        },
        async postData() {
            if (!this.validateForm()) return;

            let formData = new FormData();
            formData.append("action", "post");
            formData.append("id", this.formFied.id);
            formData.append("kategori_sesi", this.formFied.selectedSesi);
            formData.append("soal", this.formFied.soal);
            formData.append("selectedModule", this.selectedModule);
            formData.append("jawaban", JSON.stringify(this.formFied.jawaban));
            formData.append("jawabanid", JSON.stringify(this.formFied.jawabanid));
            formData.append("skor", JSON.stringify(this.formFied.skor));
            formData.append("keyFormMoneyTest", JSON.stringify(this.keyFormMoneyTest));

            for (let index = 0; index < this.formFied.gambarSoal.length; index++) {
                formData.append("gambarSoal[]", this.formFied.gambarSoal[index]);
            }

            if (this.formFied.jawabanGambar.length > 0) {
                this.formFied.jawabanGambar.forEach((file, idx) => {
                    if (file) {
                        formData.append(`jawaban_gambar[${idx}]`, file);
                    }
                });
            }

            this.loadingAct = true;
            try {
                const response = await RequestManager(PAGE_URL, formData);
                if (response.data.type == "success") {
                    this.hideModal();
                    this.viewData(this.pagination.current_page);
                    toastService.show("Sukses", response.data.message);
                } else {
                    toastService.show("Gagal", response.data.message, "error");
                }
            } catch (err) {
                toastService.show("Error", "Gagal menyimpan data", "error");
            } finally {
                this.loadingAct = false;
            }
        },
        async deleteData() {
            if (!this.validateForm()) return;
            this.loadingAct = true;
            let parameter = { action: "delete", id: this.formFied.id };
            try {
                const response = await RequestManager(PAGE_URL, parameter);
                if (response.data.type == "success") {
                    this.hideModal();
                    this.viewData(this.pagination.current_page);
                    toastService.show("Sukses", response.data.message);
                } else {
                    toastService.show("Gagal", response.data.message, "error");
                }
            } catch (err) {
                toastService.show("Error", "Gagal menghapus data", "error");
            } finally {
                this.loadingAct = false;
            }
        },
        formatTextFirstUpper(text) {
            return text.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()).join(' ');
        },
        develompentFeature() {
            toastService.show("Message", "features under development");
        },
        async postImportSoal() {
            if (!this.formImportSoal.selectedModule || !this.formImportSoal.fileSoal) {
                toastService.show("Perhatian", "Kolom dan file tidak boleh kosong", "warning");
                return;
            }
            if (this.loadingPostImportSoal) return;

            this.loadingPostImportSoal = true;
            const formData = new FormData();
            formData.append("action", "importsoal");
            formData.append("file", this.formImportSoal.fileSoal);
            formData.append("selectedModule", this.formImportSoal.selectedModule);
            formData.append("selectedSesi", this.formImportSoal.selectedSesi);
            try {
                const response = await RequestManager(PAGE_URL, formData);
                if (response.data.type == "success") {
                    toastService.show("Sukses", response.data.message);
                    this.viewData();
                    this.importSoalModal = false;
                } else {
                    toastService.show("Gagal", response.data.message, "error");
                }
            } catch (err) {
                toastService.show("Error", "Gagal mengimpor soal", "error");
            } finally {
                this.loadingPostImportSoal = false;
            }
        },
        async postExportSoal() {
            if (!this.formImportSoal.selectedModule) {
                toastService.show("Perhatian", "Silahkan pilih modul soal", "warning");
                return;
            }
            if (this.loadingPostImportSoal) return;
            this.loadingPostImportSoal = true;
            let parameter = {
                action: "exportsoal",
                modul: this.formImportSoal.selectedModule,
                selectedSesi: this.formImportSoal.selectedSesi
            };
            try {
                const response = await RequestManager(PAGE_URL, parameter);
                if (response.data.type == "success" && response.data.data) {
                    window.open(response.data.data, "_blank");
                } else {
                    toastService.show("Message", response.data.message || "URL tidak ditemukan", "error");
                }
            } catch (err) {
                toastService.show("Message", "Gagal meng-export soal", "error");
            } finally {
                this.loadingPostImportSoal = false;
            }
        }
    },
};
</script>

<style scoped>
.wrap-text {
    white-space: normal;
    word-wrap: break-word;
}

.modemobile {
    width: 100%;
}

@media screen and (max-width: 768px) {
    .modemobile {
        min-width: 85%;
        max-width: 86%;
    }
}
</style>
