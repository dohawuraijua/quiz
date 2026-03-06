<template>
    <div>
        <section class="py-2 px-4">
            <div class="max-w-8xl mx-auto">
                <div class="w-full p-4">

                    <SkeletonTable v-if="loadingView" :rows="10" :columns="5" />
                    <div v-else>
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-4" v-if="items">
                            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                                <div class="p-4">
                                    <YoutubeEmbed :video-id="items.video_id" />
                                    <h3 class="text-lg font-semibold text-gray-800">{{ items.title }}</h3>
                                    <p class="text-gray-600 mt-3">{{ items.description }}</p>

                                    <div class="w-full mt-3 mb-3">
                                        <div v-if="items.files">
                                            <span v-for="dfile in items.files" class="url"
                                                @click="get_cloud_url(dfile.id)">
                                                📎 {{ dfile.file_name }}<br>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap w-full justify-between">
                                        <span>🗓️
                                            <TanggalIndo :time=true :tanggal="items.created_at"
                                                class="w-full sm:w-2/4" />
                                        </span>
                                        <ShareLink :title="items.title" :text="items.description" :url="urlLocation" />

                                    </div>

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
import YoutubeEmbed from '@/components/global/YoutubeEmbed.vue';

import ShareLink from '../../global/ShareLink.vue';
export default {
    data() {
        return {

            items: null,
            loadingView: false,
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
        ShareLink,
        YoutubeEmbed
    },

    computed: {
        idresourches() {
            return this.$route.params.pretty_url;
        },
        urlLocation() {
            return window.location.href;
        }
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
                    action: "viewVideosDetail",
                    id: this.idresourches
                }
                const response = await RequestManager("_b/resources/peraturan", parameter);


                if (response.data.type === "success") {
                    this.items = response.data.data;
                } else {
                    toastService.show("Error", response.data.message, "error");
                    this.$router.push('/resources/videos');
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
