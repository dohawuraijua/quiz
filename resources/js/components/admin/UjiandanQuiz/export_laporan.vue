<template>
    <div>
        <section class="py-2 px-4">
            <div class="max-w-6xl mt-3 mx-auto">
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

                    </div>

                    <SkeletonTable v-if="loadingView" :rows="10" :columns="5" />

                    <SortableTableTail v-else :data="items" :columns="columns" :hasActions="false"
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

                            <span v-if="column.key == 'created_at'">
                                <TanggalIndo :longdate="false" :tanggal="row.created_at" :time="true"></TanggalIndo>
                            </span>

                            <span v-if="column.key == 'status'">
                                <StatusBadge :status="row.status"></StatusBadge>
                            </span>

                            <span v-if="column.key == 'file'">
                                <span class="url" @click="get_cloud_url(row)">File</span>
                            </span>

                        </template>
                    </SortableTableTail>

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
import StatusBadge from '../../global/StatusBadge.vue';

const PAGE_URL = "_a/course/export";
export default {
    data() {
        return {
            items: [],
            loadingView: false,
            search: "",
            pagination: {
                per_page: 10,
            },


            columns: [
                { label: "Nama", key: "nama_file", sortable: true },
                { label: "File", key: "file", sortable: true },
                { label: "Status", key: "status", sortable: true },
                { label: "Created At", key: "created_at", sortable: true },
            ],

        };
    },
    components: {
        SortableTableTail,
        SkeletonTable,
        TanggalIndo,
        PaginationTail,
        StatusBadge
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

        async get_cloud_url(row) {
            let parameter = {
                action: "get_cloud_url",
                id: row.id,
                kategori: row.kategori

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
