<template>
    <div>
        <section class="py-2 px-4">
            <div class="max-w-8xl mx-auto">
                <div class="w-full bg-white rounded shadow p-4">
                    <div class="flex flex-wrap w-full justify-between items-start gap-3 mb-3">
                        <div class="flex flex-wrap w-full sm:w-1/2 items-start gap-3 mb-3">
                            <div class="w-full sm:w-1/4">
                                <select class="w-full border rounded px-3 py-2" v-model="pagination.per_page"
                                    @change="viewItem()">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="200">200</option>
                                </select>
                            </div>

                            <div class="w-full sm:w-2/4">
                                <input type="search" class="w-full border rounded px-3 py-2" v-model="search"
                                    placeholder="Cari..">
                            </div>
                        </div>
                        <div class="w-auto">
                            <button @click="openModal" class="btn-primary text-white text-sm px-3 py-2 rounded">
                                Tambah
                            </button>
                        </div>
                    </div>
                    <SkeletonTable v-if="loadingView" :rows="10" :columns="5" />

                    <SortableTableTail v-else :data="items" :columns="columns" :hasActions="true"
                        style="min-height: 30vh;">
                        <template #actions="{ row }">
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
                                        @click="toEdit(row)">Edit</a>

                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-black hover:bg-red-500 hover:text-white"
                                        @click="deleteItem(row.id)">Delete</a>
                                </div>
                            </div>



                        </template>
                        <template #custom="{ row, column }">
                            <span v-if="column.key == 'jadwal_bebas'">
                                <span v-if="row.jadwal_bebas">
                                    Ya
                                </span>
                                <span v-else>Tidak</span>
                            </span>
                            <span v-if="column.key == 'lama_pengerjaan'">{{ row.lama_pengerjaan }} Menit</span>
                            <span v-if="column.key == 'created_at'">
                                <TanggalIndo :longdate="false" :tanggal="row.created_at" :time="true"></TanggalIndo>
                            </span>
                        </template>
                    </SortableTableTail>

                    <PaginationTail :pagination="pagination" @page-changed="viewItem" />
                </div>

                <!-- Modal -->
                <div class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center" v-if="showModal"
                    ref="modal">
                    <div class="bg-white rounded-lg w-7/10 md:w-2/3 max-w-4xl overflow-auto max-h-[90vh]">

                        <form @submit.prevent="createItem()">
                            <div class="border-b px-6 py-4 flex justify-between items-center">
                                <h5 class="text-lg font-semibold">Kategori</h5>
                                <button type="button" class="text-gray-500 hover:text-gray-700"
                                    aria-label="Close">✖</button>
                            </div>
                            <div ref="notaContent" class="px-6 py-4 bg-white text-black">
                                <div class="flex flex-wrap justify-between gap-4 mb-6">
                                    <div class="w-full">
                                        <div class="w-full">
                                            <label class="block text-sm font-medium mb-1">Nama Kategori</label>
                                            <div class="w-full">
                                                <input type="text" v-model="form.nama_modul"
                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                                    placeholder="Nama Modul..." />
                                            </div>
                                        </div>
                                        <div class="w-full mt-3">
                                            <label class="block text-sm font-medium mb-1">Parent</label>
                                            <div class="w-full">
                                                <select v-model="form.parent"
                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                                    <option value="">.:Pilih Parent:.</option>
                                                    <option value="Quiz">Quiz</option>
                                                    <option value="Diklat">Diklat</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="w-full mt-3">
                                            <label class="block text-sm font-medium mb-1">Deskripsi</label>
                                            <div class="w-full">
                                                <textarea type="text" rows="4" v-model="form.deskripsi"
                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                                    placeholder="Deskripsi..."></textarea>
                                            </div>
                                        </div>

                                        <div class="w-full mt-3">
                                            <label class="block text-sm font-medium mb-1">Jumlah Soal</label>
                                            <div class="w-full">
                                                <input type="number" v-model="form.jumlah_soal"
                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                                    placeholder="Jumlah Soal..." />
                                            </div>
                                        </div>

                                        <div class="w-full mt-3">
                                            <label class="block text-sm font-medium mb-1">Lama Pengerjaan
                                                (Menit)</label>
                                            <div class="w-full">
                                                <input type="number" v-model="form.lama_pengerjaan"
                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                                    placeholder="Dalam Menit..." />
                                            </div>
                                        </div>

                                        <div class="w-full mt-3">
                                            <div class="flex items-center mb-1">
                                                <label class="block text-sm font-medium">Jadwal Bebas</label>

                                                <div class="group relative ml-2 cursor-pointer">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-4 h-4 text-gray-400 hover:text-blue-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                                    </svg>

                                                    <div
                                                        class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block w-48 p-2 bg-gray-800 text-white text-sm rounded shadow-lg z-10">
                                                        Tidak mengikuti waktu akhir di jadwal tetapi lama pengerjaan
                                                        tetap ditentukan
                                                        <div
                                                            class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-800">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex items-center space-x-4">
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="radio" name="jadwal_bebas" v-model="form.jadwal_bebas"
                                                        :value="0" class="mr-2"> Tidak
                                                </label>
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="radio" name="jadwal_bebas" v-model="form.jadwal_bebas"
                                                        :value="1" class="mr-2"> Ya
                                                </label>
                                            </div>
                                        </div>
                                        <!-- 
                                        <div class="w-full mt-3">
                                            <label class="block text-sm font-medium mb-1">Pembobotan</label>
                                            <div v-for="bobot in pilihanBobot" :key="bobot.value"
                                                class="w-full items-center space-x-1 text-sm">
                                                <div class="border-b px-6 py-4 flex justify-between items-center">
                                                    <label
                                                        class="w-full md:1/4 border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">{{
                                                            bobot.label }}</label>

                                                    <input type="text" v-model="bobot.value"
                                                        class="w-full md:1/4 border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                                        placeholder="Nama Modul..." />
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="border-t px-6 py-4 flex justify-end gap-2">
                                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded"
                                    @click="closeModal()">Tutup</button>
                                <button type="button" @click="createItem"
                                    class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>


<script>
import SortableTableTail from '@/components/global/SortableTableTail.vue';
import SkeletonTable from '@/components/global/SkeletonTable.vue';
import TanggalIndo from '@/components/global/TanggalIndo.vue';
import toastService from '../../helper/toastService';
import RequestManager from '../../helper/RequestManager';
import Swal from 'sweetalert2';
import PaginationTail from '@/components/global/PaginationTail.vue';

const PAGE_URL = "_a/course/modul-ujian";
export default {
    data() {
        return {
            items: [],
            loadingView: false,
            pilihanBobot: [
                { label: 'Sangat Rendah', value: 1 },
                { label: 'Rendah', value: 2 },
                { label: 'Sedang', value: 3 },
                { label: 'Tinggi', value: 4 },
                { label: 'Sangat Tinggi', value: 5 },
            ],
            form: {
                id: null,
                nama_modul: "",
                deskripsi: "",
                parent: "",
                use_pembobotan: 0,
                jumlah_soal: "",
                lama_pengerjaan: "",
                pembobotan: [],
                jadwal_bebas: 0
            },
            imageUrl: "",
            isDragging: false,
            isEdit: false,
            showModal: false,
            search: "",
            pagination: {
                per_page: 10,
            },


            columns: [
                { label: "ID Import", key: "id", sortable: true },
                { label: "Nama Modul", key: "nama_modul", sortable: true },
                { label: "Parent", key: "parent", sortable: true },
                { label: "Jumlah Soal", key: "jumlah_soal", sortable: true },
                { label: "Lama Pengerjaan", key: "lama_pengerjaan", sortable: true },
                { label: "Jadwal Bebas", key: "jadwal_bebas", sortable: true },
                { label: "Deskripsi", key: "deskripsi", sortable: true },
                { label: "Updated", key: "created_at" }
            ],

        };
    },
    components: {
        SortableTableTail,
        SkeletonTable,
        TanggalIndo,
        PaginationTail
    },

    mounted() {
        this.viewItem();
    },
    watch: {
        'search'(newVal, oldVal) {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.viewItem();
            }, 1500);
        }
    },
    methods: {

        openModal() {
            this.resetForm();
            this.showModal = true
        },
        closeModal() {

            this.showModal = false
        },
        resetForm() {
            this.form = {
                id: null,
                nama_modul: "",
                deskripsi: "",
                parent: "",
                use_pembobotan: 0,
                jumlah_soal: "",
                lama_pengerjaan: "",
                pembobotan: [],
                jadwal_bebas: 0
            }
        },

        toEdit(row) {
            this.form = {
                id: row.id,
                nama_modul: row.nama_modul,
                deskripsi: row.deskripsi,
                parent: row.parent,
                use_pembobotan: row.use_bobot,
                jumlah_soal: row.jumlah_soal,
                lama_pengerjaan: row.lama_pengerjaan,
                pembobotan: [],
                jadwal_bebas: row.jadwal_bebas
            },
                this.showModal = true
        },



        handleFileSelect(event) {
            const file = event.target.files[0];

            if (file && file.type.startsWith('image/')) {
                this.form.files = file;

                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imageUrl = e.target.result; // Data URL (base64)
                };
                reader.readAsDataURL(file);

            } else {
                // Jika file tidak valid atau dibatalkan
                removeImage();
            }
        },
        removeFile() {
            this.form.files = null;
            this.imageUrl = null;
        },

        async viewItem(page = 1) {
            try {
                let parameter = {
                    action: "view",
                    page: page,
                    per_page: this.pagination.per_page,
                    search: this.search
                }
                const response = await RequestManager(PAGE_URL, parameter);
                if (response.data.type === "success") {
                    this.items = response.data.data.data;
                    this.pagination = response.data.data;
                } else {
                    toastService.show("Error", response.data.message, "error");
                }
            } catch (error) {
                toastService.show("Error", error, "error");
            }
        },

        async createItem() {

            try {
                let parameter = {
                    action: "store",
                    id: this.form.id,
                    nama_modul: this.form.nama_modul,
                    parent: this.form.parent,
                    deskripsi: this.form.deskripsi,
                    use_bobot: this.form.use_bobot,
                    pembobotan: this.form.pembobotan,
                    jumlah_soal: this.form.jumlah_soal,
                    lama_pengerjaan: this.form.lama_pengerjaan,
                    jadwal_bebas: this.form.jadwal_bebas
                }

                const response = await RequestManager(PAGE_URL, parameter);
                if (response.data.type === "success") {
                    toastService.show("Success", response.data.message);
                    this.closeModal();
                    this.viewItem();
                } else {
                    toastService.show("Error", response.data.message, "error");
                }
            } catch (error) {
                toastService.show("Error", error, "error");
            }
        },


        async deleteItem(id) {
            const result = await Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Anda tidak akan bisa mengembalikan data ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33', // Merah untuk Delete
                cancelButtonColor: '#3085d6', // Biru
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            });

            // 2. Cek Hasil Konfirmasi
            if (result.isConfirmed) {
                try {
                    let parameter = {
                        action: "destroy",
                        id: id
                    }

                    // Lanjutkan proses penghapusan ke API
                    const response = await RequestManager(PAGE_URL, parameter);

                    if (response.data.type === "success") {
                        // Tampilkan notifikasi sukses dan refresh data
                        toastService.show("Terhapus!", response.data.message, "success");
                        this.viewItem();
                    } else {
                        // Tampilkan notifikasi error dari server
                        toastService.show("Error", response.data.message, "error");
                    }

                } catch (error) {
                    // Tampilkan notifikasi error umum
                    toastService.show("Error", error.message || "Gagal menghubungi server", "error");
                }
            }
        },

        async get_cloud_url(id) {
            let parameter = {
                action: "get_cloud_url",
                id: id,
            };
            const response = await RequestManager(
                PAGE_URL,
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
/* Optional: custom styles */
</style>
