<template>
    <div>
        <section class="py-2 px-4">
            <div class="max-w-8xl mx-auto">
                <div class="w-full  p-4">
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
                        <div class="w-auto" v-if="hasRoles([4])">
                            <button @click="openModal" class="btn-primary text-white text-sm px-3 py-2 rounded">
                                Tambah
                            </button>
                        </div>
                    </div>
                    <SkeletonTable v-if="loadingView" :rows="10" :columns="5" />
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                        <div class="bg-white shadow-md rounded-lg" v-for="value in items" :key="value.id">
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-800">{{ value.title }}</h3>
                                <p class="text-gray-600 mt-3">{{ value.description }}</p>

                                <div class="w-full mt-3 mb-3">
                                    <div v-if="value.files">
                                        <span v-for="dfile in value.files" class="url" @click="get_cloud_url(dfile.id)">
                                            📎 {{ dfile.file_name }}<br>
                                        </span>
                                    </div>
                                </div>

                                <div v-if="value.keyword" class="flex flex-wrap gap-2 mt-2">
                                    <span v-for="(tag, index) in value.keyword" :key="index"
                                        class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">
                                        {{ tag }}
                                    </span>
                                </div>

                                <div class="flex flex-wrap w-full justify-between">
                                    <span>🗓️
                                        <TanggalIndo :time=true :longdate="false" :tanggal="value.created_at"
                                            class="w-full sm:w-2/4 text-gray-600 text-sm" />
                                    </span>

                                    <div class="relative inline-block text-left group" v-if="hasRoles([4])">

                                        <button
                                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-gray-800 rounded-md">
                                            Action
                                            <svg class="w-5 h-5 ml-2 transition-transform duration-200 group-focus-within:rotate-180"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 12l-4-4h8l-4 4z" />
                                            </svg>
                                        </button>

                                        <div class="absolute right-0 mt-2 w-48 bg-white border rounded-md shadow-lg z-50
               opacity-0 invisible
               transition-all duration-200
               group-hover:opacity-100 group-hover:visible
               group-focus-within:opacity-100 group-focus-within:visible">

                                            <ShareLink :url="urlItem(value)"
                                                class="block px-4 py-2 text-sm text-black hover:bg-blue-100 w-full text-left" />

                                            <a href="#" @click="toEdit(value)"
                                                class="block px-4 py-2 text-sm hover:bg-green-600 hover:text-white">
                                                Edit
                                            </a>

                                            <a href="#" @click="deleteItem(value.id)"
                                                class="block px-4 py-2 text-sm hover:bg-red-500 hover:text-white">
                                                Delete
                                            </a>
                                        </div>

                                    </div>


                                    <div class="flex gap-3 text-left group" v-else>
                                        <ShareLink :url="urlItem(value)"
                                            :class="`block px-4 py-2 text-sm rounded-md text-left hover:bg-blue-500 bg-blue-600 w-full text-white`" />
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                    <PaginationTail :pagination="pagination" @page-changed="viewItem" />
                </div>

                <!-- Modal -->
                <div class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center" v-if="showModal"
                    ref="modal">
                    <div class="bg-white rounded-lg w-7/10 md:w-2/3 max-w-4xl overflow-auto max-h-[90vh]">

                        <form @submit.prevent="createItem()">
                            <div class="border-b px-6 py-4 flex justify-between items-center">
                                <h5 class="text-lg font-semibold">Form</h5>
                                <button type="button" class="text-gray-500 hover:text-gray-700"
                                    aria-label="Close">✖</button>
                            </div>
                            <div ref="notaContent" class="px-6 py-4 bg-white text-black">
                                <div class="flex flex-wrap justify-between gap-4 mb-6">
                                    <div class="w-full">
                                        <div class="w-full">
                                            <label class="block text-sm font-medium mb-1">Judul</label>
                                            <div class="w-full">
                                                <input type="text" v-model="form.judul"
                                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                                    placeholder="Judul..." />
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
                                            <label class="block text-sm font-medium mb-1">Tags</label>
                                            <input type="text" v-model="tagsInput" @input="updateTags"
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                                placeholder="Pisahkan dengan koma. Contoh: Kredit, PADG, Kepatuhan" />
                                            <span class="w-full mt-1 text-xs">Pisahkan dengan koma</span>
                                            <!-- Preview Tags -->
                                            <div v-if="form.keyword.length" class="flex flex-wrap gap-2 mt-2">
                                                <span v-for="(tag, index) in form.keyword" :key="index"
                                                    class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">
                                                    {{ tag }}
                                                </span>
                                            </div>
                                        </div>


                                        <div class="w-full mt-3">
                                            <div class="w-full">
                                                <div class="w-full mt-3">
                                                    <label class="block text-sm font-medium mb-1">Files</label>
                                                    <div @dragover.prevent="isDragging = true"
                                                        @dragleave.prevent="isDragging = false"
                                                        @drop.prevent="handleDrop"
                                                        :class="{ 'border-blue-500 bg-blue-50': isDragging }"
                                                        class="w-full border-2 border-dashed border-gray-300 rounded-md px-6 py-8 text-center transition-colors cursor-pointer hover:border-gray-400"
                                                        @click="$refs.fileInput.click()">
                                                        <svg class="mx-auto h-12 w-12 text-gray-400"
                                                            stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                            <path
                                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                                stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                        <p class="mt-2 text-sm text-gray-600">
                                                            <span class="font-semibold">Click to upload</span> or drag
                                                            and drop
                                                        </p>
                                                        <p class="text-xs text-gray-500 mt-1">Multiple files allowed</p>

                                                        <input ref="fileInput" type="file" multiple
                                                            @change="handleFileSelect" class="hidden" />
                                                    </div>

                                                    <!-- Preview file yang dipilih -->
                                                    <div v-if="form.files.length > 0" class="mt-3 space-y-2">
                                                        <div v-for="(file, index) in form.files" :key="index"
                                                            class="flex items-center justify-between bg-gray-50 px-3 py-2 rounded border">
                                                            <span class="text-sm text-gray-700 truncate">{{ file.name
                                                            }}</span>
                                                            <button @click="removeFile(index)"
                                                                class="text-red-500 hover:text-red-700 ml-2">
                                                                <svg class="w-5 h-5" fill="currentColor"
                                                                    viewBox="0 0 20 20">
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


                            </div>
                            <div class="border-t px-6 py-4 flex justify-end gap-2">
                                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded"
                                    @click="closeModal()">Tutup</button>
                                <button type="button" class="bg-blue-300 text-white px-4 py-2 rounded"
                                    v-if="mengunggah">Mengunggah</button>
                                <button type="button" v-else @click="createItem"
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
import ShareLink from '../../global/ShareLink.vue';
import hasRole from '../../helper/hasRole';
import { MAIN_HOST } from '../../helper/const_string';
export default {
    data() {
        return {
            items: [],
            loadingView: false,
            mengunggah: false,
            tagsInput: '',
            form: {
                id: null,
                judul: "",
                deskripsi: "",
                kategori: "",
                keyword: [],
                files: [

                ]
            },
            isDragging: false,
            isEdit: false,
            showModal: false,
            search: "",
            pagination: {
                per_page: 10,
            },


            columns: [
                { label: "Judul", key: "title", sortable: true },
                { label: "Deskripsi", key: "description", sortable: true },
                { label: "Files", key: "files", sortable: true },
                { label: "Updated", key: "created_at" }
            ],

        };
    },
    components: {
        SortableTableTail,
        SkeletonTable,
        TanggalIndo,
        PaginationTail,
        ShareLink
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
        hasRoles(rl) {
            return hasRole(rl);
        },

        updateTags() {
            if (!this.tagsInput) { // jika null, undefined, atau string kosong
                this.form.keyword = [] // kosongkan
                return
            }

            const tags = this.tagsInput
                .split(',')
                .map(tag => tag.trim())
                .filter(tag => tag.length > 0)

            this.form.keyword = [...new Set(tags)] // hapus duplikat
        },
        openModal() {

            this.showModal = true
        },
        closeModal() {
            this.showModal = false;
            this.form = {
                id: null,
                judul: "",
                deskripsi: "",
                kategori: "",
                keyword: [],
                files: [

                ]
            }
        },

        handleDrop(e) {
            this.isDragging = false;
            const files = Array.from(e.dataTransfer.files);
            this.form.files.push(...files);
        },
        handleFileSelect(e) {
            const files = Array.from(e.target.files);
            this.form.files.push(...files);
        },
        removeFile(index) {
            this.form.files.splice(index, 1);
        },

        urlItem(row) {
            let pathto = MAIN_HOST + "/resources/peraturan/"
            let pretty = row?.pretty_url;
            return pathto + pretty;
        },
        enkripsiString(str) {
            return (str);
        },

        toEdit(val) {
            this.form.id = val.id;
            this.form.judul = val.title;
            this.form.deskripsi = val.description;
            this.form.keyword = val.keyword ?? [];
            this.tagsInput = (val.keyword ?? []).join(', ');
            this.openModal();
        },

        async viewItem(page = 1) {
            try {
                let parameter = {
                    action: "viewPeraturan",
                    page: page,
                    per_page: this.pagination.per_page,
                    search: this.search
                }
                const response = await RequestManager("_a/resources/peraturan", parameter);
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
            this.mengunggah = true;
            try {
                // A. Simpan metadata dulu untuk dapat ID
                const saveRes = await RequestManager("_a/resources/peraturan", {
                    action: "store_metadata",
                    data: JSON.stringify(this.form),
                });

                if (saveRes.data.type !== "success") {
                    toastService.show("Error", saveRes.data.message, "error");
                    this.mengunggah = false;
                    return;
                }

                const resourceId = saveRes.data.data.id;
                const files = this.form.files || [];

                // Jika tidak ada file, proses selesai
                if (files.length === 0) {
                    toastService.show("Success", "Data berhasil disimpan.");
                    this.mengunggah = false;
                    this.closeModal();
                    this.viewItem();
                    return;
                }

                // B. Minta signed URL untuk tiap file
                const signRes = await RequestManager("_a/resources/peraturan", {
                    action: "signed_urls",
                    resource_id: resourceId,
                    files: files.map(f => ({
                        name: f.name,
                        contentType: f.type || "application/octet-stream",
                    })),
                });

                if (signRes.data.type !== "success") {
                    toastService.show("Error", signRes.data.message, "error");
                    this.mengunggah = false;
                    return;
                }

                const uploads = signRes.data.data.uploads;

                // C. Upload langsung ke GCS via PUT
                for (let i = 0; i < uploads.length; i++) {
                    const u = uploads[i];
                    const file = files[i];
                    const put = await fetch(u.url, {
                        method: "PUT",
                        headers: { "Content-Type": u.contentType },
                        body: file,
                    });
                    if (!put.ok) {
                        this.mengunggah = false;
                        throw new Error(`Upload gagal untuk ${u.name}. HTTP ${put.status}`);
                    }
                }

                // D. Finalize: simpan path file ke DB
                toastService.show("Uploading", "Mengunggah file, mohon tunggu...", "info");
                const finalizeRes = await RequestManager("_a/resources/peraturan", {
                    action: "finalize_upload",
                    resource_id: resourceId,
                    files: uploads.map(u => ({
                        path: u.object,
                        name: u.name,
                        contentType: u.contentType
                    })),
                });

                if (finalizeRes.data.type === "success") {
                    toastService.show("Success", finalizeRes.data.message);
                    this.mengunggah = false;
                    this.closeModal();
                    this.viewItem();
                } else {
                    this.mengunggah = false;
                    toastService.show("Error", finalizeRes.data.message, "error");
                }

            } catch (error) {
                this.mengunggah = false;
                toastService.show("Error", error?.message || String(error), "error");
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
                        action: "delete",
                        id: id
                    }

                    // Lanjutkan proses penghapusan ke API
                    const response = await RequestManager("_a/resources/peraturan", parameter);

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
                "_a/resources/peraturan",
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
