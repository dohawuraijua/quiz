<template>
    <div>
        <section class="py-2 px-4">
            <div class="max-w-8xl mx-auto">
                <div class="w-full ">
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

                            <div class="w-full sm:w-2/4">
                                <div class="w-full">
                                    <v-select :options="itemsKantorInduk" :loading="isLoadingKantor" label="nama"
                                        class="w-full" v-model="searchkantorInduk" :reduce="item => item.id"
                                        placeholder="Kantor induk.."
                                        @search="(search, loading) => onSearchKantor(search, loading, 'kantor induk')">
                                        <template #no-options="{ search, searching, loading }">
                                            <div v-if="loading">
                                                Memuat hasil...
                                            </div>
                                            <div v-else-if="searching">
                                                Tidak ada hasil ditemukan untuk "{{ search }}".
                                            </div>
                                            <div v-else>
                                                Mulai mengetik untuk mencari data.
                                            </div>
                                        </template>
                                    </v-select>
                                </div>
                            </div>
                        </div>
                        <div class="w-auto">
                            <button @click="viewItem" class="btn-primary text-white text-sm px-3 m-1 py-2 rounded">
                                Reload
                            </button>
                            <button @click="openModal" class="btn-primary text-white text-sm px-3 m-1 py-2 rounded">
                                Tambah
                            </button>
                            <button @click="openModalSinkronSIM"
                                class="btn-primary text-white text-sm px-3 py-2 m-1 rounded">
                                Sinkron SIM
                            </button>
                        </div>
                    </div>

                    <SkeletonTable v-if="loadingView" :rows="10" :columns="5" />
                    <SortableTableTail v-else :data="items" :columns="columns" :hasActions="true">
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
                            <span v-if="column.key == 'jabatans'">
                                {{ row?.jabatans?.nama }}
                            </span>
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
                    <div class="bg-white rounded-lg w-6/10 md:w-1/3 max-w-4xl overflow-auto max-h-[90vh]">

                        <form @submit.prevent="createItem()">
                            <div class="border-b px-6 py-4 flex justify-between items-center">
                                <h5 class="text-lg font-semibold">User</h5>
                                <button type="button" @click="closeModal" class="text-gray-500 hover:text-gray-700"
                                    aria-label="Close">✖</button>
                            </div>
                            <div ref="notaContent" class="px-6 py-4 bg-white text-black">

                                <div class="px-6 pt-3 border-b mb-4 justify-center flex">
                                    <div class="flex gap-4">
                                        <button type="button" @click="activeTab = 'form'" :class="activeTab === 'form'
                                            ? 'border-b-2 border-blue-600 text-blue-600'
                                            : 'text-gray-500'" class="pb-2 font-medium">
                                            Input Lengkap
                                        </button>

                                        <button type="button" @click="activeTab = 'npp'" :class="activeTab === 'npp'
                                            ? 'border-b-2 border-blue-600 text-blue-600'
                                            : 'text-gray-500'" class="pb-2 font-medium">
                                            Paste NPP
                                        </button>
                                    </div>
                                </div>



                                <div v-if="activeTab === 'npp'" class="px-6 py-4">
                                    <label class="block text-sm font-medium mb-2">
                                        Paste NPP
                                    </label>

                                    <textarea v-model="form.nppBulk" rows="8"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                        placeholder="
123456
123457
123458"></textarea>

                                    <ul class="text-xs text-gray-500 mt-2">
                                        <li>• Paste NPP saja</li>
                                        <li>• Satu NPP per baris</li>
                                    </ul>
                                </div>


                                <div v-else class="flex flex-wrap justify-between gap-4 mb-6">
                                    <div class="w-full">
                                        <div class="w-full">
                                            <label class="block text-sm font-medium mb-1">Nama</label>
                                            <div class="w-full">
                                                <input type="text" v-model="form.nama"
                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                                    placeholder="Nama..." />
                                            </div>
                                        </div>
                                        <div class="w-full mt-3">
                                            <label class="block text-sm font-medium mb-1">NPP</label>
                                            <div class="w-full">
                                                <input type="text" v-model="form.npp"
                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                                    placeholder="NPP..." />
                                            </div>
                                        </div>
                                        <div class="w-full mt-3">
                                            <label class="block text-sm font-medium mb-1">Username</label>
                                            <div class="w-full">
                                                <input type="text" v-model="form.email"
                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                                    placeholder="Username..." />
                                            </div>
                                        </div>

                                        <div class="w-full mt-3">
                                            <label class="block text-sm font-medium mb-1">Password</label>
                                            <div class="w-full">
                                                <input type="text" v-model="form.password"
                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                                    placeholder="password..." />
                                            </div>
                                        </div>
                                        <div class="w-full mt-3">
                                            <label class="block text-sm font-medium mb-1">Kantor</label>
                                            <div class="w-full">
                                                <v-select :options="itemsKantor" :loading="isLoadingKantor" label="nama"
                                                    class="w-full" v-model="form.kantor" placeholder="Pencarian.."
                                                    :reduce="item => item.id"
                                                    @search="(search, loading) => onSearchKantor(search, loading, 'kantor')">
                                                    <template #no-options="{ search, searching, loading }">
                                                        <div v-if="loading">
                                                            Memuat hasil...
                                                        </div>
                                                        <div v-else-if="searching">
                                                            Tidak ada hasil ditemukan untuk "{{ search }}".
                                                        </div>
                                                        <div v-else>
                                                            Mulai mengetik untuk mencari data.
                                                        </div>
                                                    </template>
                                                </v-select>
                                            </div>
                                        </div>

                                        <div class="w-full mt-3">
                                            <label class="block text-sm font-medium mb-1">Kantor Induk</label>
                                            <div class="w-full">
                                                <v-select :options="itemsKantorInduk" :loading="isLoadingKantor"
                                                    label="nama" class="w-full" v-model="form.kantorInduk"
                                                    :reduce="item => item.id" placeholder="Pencarian.."
                                                    @search="(search, loading) => onSearchKantor(search, loading, 'kantor induk')">
                                                    <template #no-options="{ search, searching, loading }">
                                                        <div v-if="loading">
                                                            Memuat hasil...
                                                        </div>
                                                        <div v-else-if="searching">
                                                            Tidak ada hasil ditemukan untuk "{{ search }}".
                                                        </div>
                                                        <div v-else>
                                                            Mulai mengetik untuk mencari data.
                                                        </div>
                                                    </template>
                                                </v-select>
                                            </div>
                                        </div>



                                        <div class="w-full mt-3">
                                            <label class="block text-sm font-medium mb-2">Role User</label>

                                            <div class="space-y-2">
                                                <label v-for="role in rolesList" :key="role.id"
                                                    class="flex items-center space-x-2">
                                                    <input type="checkbox" :value="role.id" v-model="form.roles"
                                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                                                    <span>{{ role.nama_role }}</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="w-full mt-3">
                                            <div class="w-full">
                                                <div class="w-full mt-3">
                                                    <label class="block text-sm font-medium mb-1">Foto Profile</label>
                                                    <div>
                                                        <input ref="fileInput" type="file" accept="image/*"
                                                            @change="handleFileSelect"
                                                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                                                    </div>
                                                    <div v-if="imageUrl"
                                                        class="relative w-64 h-84 mb-4 mt-3 rounded-lg overflow-hidden border border-gray-300 shadow-md">
                                                        <img :src="imageUrl" alt="Image Preview"
                                                            class="w-full h-full object-cover" />

                                                        <button @click="removeFile" type="button"
                                                            class="absolute top-2 right-2 p-1 bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                                                            aria-label="Hapus Gambar">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                                viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
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




                <div class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center"
                    v-if="showModalSinkronSIM" ref="modal">
                    <div class="bg-white rounded-lg w-6/10 md:w-1/3 max-w-8xl overflow-auto max-h-[90vh]">

                        <div class="border-b px-6 py-4 flex justify-between items-center">
                            <h5 class="text-lg font-semibold">Sinkron Data dari SIM</h5>
                            <button type="button" @click="closeModal()" class="text-gray-500 hover:text-gray-700"
                                aria-label="Close">✖</button>
                        </div>
                        <div ref="notaContent" class="px-6 py-4 bg-white text-black">
                            <div class="flex flex-wrap justify-between gap-4 mb-6">
                                <div class="w-full">
                                    <!-- Info -->
                                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative"
                                        role="alert">
                                        <span class="block sm:inline">
                                            Pilih mode sinkronisasi data dari SIM. Sinkronisasi ini akan mengambil data
                                            pegawai dari Sistem Informasi Manajemen (SIM) dan memperbarui data user di
                                            sistem e-learning sesuai dengan data terbaru dari SIM.
                                        </span>
                                    </div>

                                    <!-- Pilihan export -->
                                    <div class="w-full mt-4">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Pilih Mode Sinkronisasi
                                        </label>

                                        <div class="flex flex-col sm:flex-row gap-3">
                                            <!-- Excel -->
                                            <label
                                                class="flex items-center gap-3 p-3 rounded border cursor-pointer hover:bg-gray-50"
                                                :class="modeSinkron === 'all' ? 'border-blue-500 ring-1 ring-blue-300 bg-blue-50' : 'border-gray-300'">
                                                <input type="radio" class="h-4 w-4" name="modeSinkron" value="all"
                                                    v-model="modeSinkron" />
                                                <div>
                                                    <div class="text-sm font-semibold text-gray-800">Semua Pegawai</div>
                                                    <div class="text-xs text-gray-500">Sinkronisasi semua data pegawai
                                                        dari SIM</div>
                                                </div>
                                            </label>

                                            <label
                                                class="flex items-center gap-3 p-3 rounded border cursor-pointer hover:bg-gray-50"
                                                :class="modeSinkron === 'nameNull' ? 'border-blue-500 ring-1 ring-blue-300 bg-blue-50' : 'border-gray-300'">
                                                <input type="radio" class="h-4 w-4" name="modeSinkron" value="nameNull"
                                                    v-model="modeSinkron" />
                                                <div>
                                                    <div class="text-sm font-semibold text-gray-800">Pegawai Tanpa Nama
                                                    </div>
                                                    <div class="text-xs text-gray-500">Sinkronisasi pegawai tanpa nama
                                                        dari SIM</div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="w-full mt-4 flex justify-between items-center gap-3">
                                        <button type="button" @click="closeModal()"
                                            class="bg-gray-200 text-gray-700 px-4 py-2 rounded">
                                            Batal
                                        </button>

                                        <button type="button" @click="sinkronSIM()" :disabled="isSinkronning"
                                            class="bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-60 disabled:cursor-not-allowed">
                                            <span v-if="!isSinkronning">Mulai Sinkronisasi</span>
                                            <span v-else>Memproses...</span>
                                        </button>


                                    </div>
                                </div>
                            </div>
                        </div>
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

const PAGE_URL = "_a/settings/users";
export default {
    data() {
        return {
            items: [],
            itemsKantor: [],
            itemsKantorInduk: [],
            rolesList: [],
            loadingView: false,
            isSinkronning: false,
            activeTab: 'form',
            modeSinkron: 'all',
            form: {
                id: null,
                nama: "",
                email: "",
                npp: "",
                nppBulk: "",
                password: "",
                kantor: "",
                kantorInduk: "",
                roles: [],
                status: "",
                foto: null
            },
            imageUrl: "",
            isDragging: false,
            isEdit: false,
            showModal: false,
            showModalSinkronSIM: false,
            isLoadingKantor: false,
            search: "",
            searchkantorInduk: "",
            pagination: {
                per_page: 10,
            },


            columns: [
                { label: "Nama", key: "name", sortable: true },
                { label: "NPP", key: "npp", sortable: true },
                { label: "Username", key: "email", sortable: true },
                { label: "Kantor", key: "kantor", sortable: true },
                { label: "Kantor Induk", key: "kantorinduk", sortable: true },
                { label: "Jabatan", key: "jabatans", sortable: false },
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
        this.getRoles();
    },
    watch: {
        'search'(newVal, oldVal) {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.viewItem();
            }, 1500);
        },
        'searchkantorInduk'(newVal, oldVal) {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.viewItem();
            }, 1500);
        },

    },
    methods: {

        openModal() {
            this.resetForm();
            this.showModal = true
        },
        openModalSinkronSIM() {
            this.modeSinkron = 'all';
            this.showModalSinkronSIM = true
        },
        closeModal() {

            this.showModal = false
            this.showModalSinkronSIM = false
        },
        resetForm() {
            this.form = {
                id: null,
                nama: "",
                email: "",
                npp: "",
                nppBulk: "",
                password: "",
                kantor: "",
                kantorInduk: "",
                status: "",
                foto: null,
                roles: []
            }
            this.removeFile();
        },

        toEdit(row) {
            this.itemsKantor = [];
            this.itemsKantorInduk = [];
            this.form = {
                id: row.id,
                nama: row.name,
                npp: row.npp,
                email: row.email,
                kantor: row.kantor_id,
                kantorInduk: row.kantor_induk_id,
                password: "",
                status: row.status,
                roles: row.roles
            }

            this.showModal = true;
            this.fetchKantor('', 'kantor');
            this.fetchKantor('', 'kantor induk')
        },

        handleFileSelect(event) {
            const file = event.target.files[0];

            if (file && file.type.startsWith('image/')) {
                this.form.foto = file;

                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imageUrl = e.target.result; // Data URL (base64)
                };
                reader.readAsDataURL(file);

            } else {
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
                    search: this.search,
                    searchkantorInduk: this.searchkantorInduk
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

        async getRoles() {
            try {
                let parameter = {
                    action: "getRoles"
                }
                const response = await RequestManager(PAGE_URL, parameter);
                if (response.data.type === "success") {
                    this.rolesList = response.data.data;
                } else {
                    toastService.show("Error", response.data.message, "error");
                }
            } catch (error) {
                toastService.show("Error", error, "error");
            }
        },


        async sinkronSIM(page = 1) {
            this.isSinkronning = true;
            try {
                let parameter = {
                    action: "sinkronSIM",
                    modeSinkron: this.modeSinkron,
                }
                const response = await RequestManager(PAGE_URL, parameter);
                if (response.data.type === "success") {
                    toastService.show("Success", response.data.message, "success");
                    this.closeModal();
                } else {
                    toastService.show("Error", response.data.message, "error");
                }
                this.isSinkronning = false;
            } catch (error) {
                this.isSinkronning = false;
                toastService.show("Error", error, "error");
            }
        },

        async createItem() {

            try {

                let formData = new FormData();

                formData.append("action", "store");
                formData.append("data", JSON.stringify(this.form));
                formData.append('foto', this.form.foto);
                formData.append("nppBulk", this.form.nppBulk);
                formData.append("activeTab", this.activeTab);

                const response = await RequestManager(PAGE_URL, formData);
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
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
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
                    const response = await RequestManager(PAGE_URL, parameter);

                    if (response.data.type === "success") {
                        toastService.show("Terhapus!", response.data.message, "success");
                        this.viewItem();
                    } else {
                        toastService.show("Error", response.data.message, "error");
                    }

                } catch (error) {
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


        onSearchKantor(search, loading, mode) {
            if (search.length < 3) {
                this.dataResults = [];
                return;
            }
            this.isLoading = true;
            if (this.debounceTimeout) {
                clearTimeout(this.debounceTimeout);
            }

            this.debounceTimeout = setTimeout(() => {
                this.fetchKantor(search, mode);
            }, 500);
        },
        async fetchKantor(query, mode) {
            try {
                let parameter = {
                    action: "view",
                    search: query
                }
                const response = await RequestManager("global-attribute/kantor", parameter);
                if (response.data.type === "success") {
                    if (mode == "kantor") {
                        this.itemsKantor = response.data.data;
                    } else if (mode == "kantor induk") {
                        this.itemsKantorInduk = response.data.data;
                    }

                } else {
                    toastService.show("Error", response.data.message, "error");
                }
            } catch (error) {
                toastService.show("Error", error, "error");
            }
        },

    },
};
</script>

<style scoped>
/* Optional: custom styles */
</style>
