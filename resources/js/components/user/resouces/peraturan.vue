<template>
    <div>
        <section class="py-2 px-4">
            <div class="max-w-8xl mx-auto">
                <div class="w-full p-4">
                    <div class="flex flex-wrap bg-white p-3 w-full justify-between items-start gap-3 mb-3">
                        <div class="flex flex-wrap w-full sm:w-1/2 items-start gap-3 ">
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
                    </div>

                    <SkeletonTable v-if="loadingView" :rows="10" :columns="5" />

                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                        <div class="bg-white shadow-md rounded-lg overflow-hidden" v-for="value in items"
                            :key="value.id">
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

                                <div class="flex flex-wrap w-full justify-between">
                                    <span>🗓️
                                        <TanggalIndo :time=true :tanggal="value.created_at" class="w-full sm:w-2/4" />
                                    </span>
                                    <router-link :to="`/resources/peraturan/${enkripsiString(value.pretty_url)}`"
                                        class="url">Baca
                                        Selengkapnya</router-link>
                                </div>

                            </div>
                        </div>
                    </div>
                    <PaginationTail :pagination="pagination" @page-changed="viewItem" />
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
import { enkripsi } from '../../helper/crypt';
export default {
    data() {
        return {
            items: [],
            loadingView: false,
            form: {
                id: null,
                judul: "",
                deskripsi: "",
                kategori: "",
                keyword: "",
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

        enkripsiString(str) {
            return (str);
        },

        openModal() {

            this.showModal = true
        },
        closeModal() {

            this.showModal = false
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

        async viewItem(page = 1) {
            this.loadingView = true;
            try {
                let parameter = {
                    action: "viewPeraturan",
                    page: page,
                    per_page: this.pagination.per_page,
                    search: this.search
                }
                const response = await RequestManager("_b/resources/peraturan", parameter);
                if (response.data.type === "success") {
                    this.items = response.data.data.data;
                    this.pagination = response.data.data;
                } else {
                    toastService.show("Error", response.data.message, "error");
                }
                this.loadingView = false;
            } catch (error) {
                this.loadingView = false;
                toastService.show("Error", error, "error");
            }
        },

        async get_cloud_url(id) {
            let parameter = {
                action: "get_cloud_url",
                id: id,
            };
            const response = await RequestManager(
                "_b/resources/peraturan",
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
