<template>
    <div>
        <section class="py-2 px-4 sm:px-2 lg:px-8">
            <div class="max-w-8xl mx-auto">
                <div class="w-full rounded  p-0">
                    <div class="flex flex-wrap w-full justify-between items-start gap-3 mb-3">
                    </div>
                    <SkeletonTable v-if="loadingView" :rows="10" :columns="columns.length" />
                    <div v-else>


                        <SortableTableTail :data="items" :columns="columns" :hasActions="true">

                            <template #actions="{ row }">
                                <span class="url text-2xl cursor-pointer" @click="saveAnswer(row)">💾</span>
                            </template>
                            <template #custom="{ row, column }">
                                <span v-if="column.key == 'indonesian_word'">
                                    <input type="text" v-model="row.indonesian_word"
                                        class="input-focus w-full px-2 py-1 rounded-lg border-2 border-blue-200 focus:border-blue-500 focus:outline-none transition-all duration-200"
                                        placeholder="answer..">
                                </span>

                                <span v-if="column.key == 'english_word'">
                                    {{ row.english_word }}
                                </span>
                            </template>
                        </SortableTableTail>

                        <PaginationTail :pagination="pagination" @page-changed="viewData" />
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import RequestManager from "../../helper/RequestManager";
import toastService from "../../helper/toastService";
import SkeletonTable from '@/components/global/SkeletonTable.vue';
import PaginationTail from '@/components/global/PaginationTail.vue';
import SortableTableTail from '@/components/global/SortableTableTail.vue';

const PAGE_URL = "quiz/isian";
export default {
    components: {
        SkeletonTable,
        PaginationTail,
        SortableTableTail
    },
    data() {
        return {
            loadingView: false,
            items: [],
            columns: [
                { key: "english_word", label: "Q" },
                { key: "indonesian_word", label: "Ans" },
            ],
        };
    },
    computed: {
    },
    watch: {


    },
    mounted() {
        this.viewData();
    },
    methods: {

        async viewData(page = 1) {
            this.loadingView = true;
            let parameter = {
                action: "view",
            };

            try {
                const response = await RequestManager(PAGE_URL, parameter);
                if (response.data.type == "success") {
                    this.items = response.data.data.map(item => ({ ...item, answer: '' }));
                } else {
                    toastService.show("Gagal", response.data.message, "error");
                }
            } catch (err) {
                toastService.show("Error", "Gagal memuat data soal", "error");
            } finally {
                this.loadingView = false;
            }
        },

        async saveAnswer(row) {
            if (!row.indonesian_word) {
                toastService.show("Info", "Jawaban tidak boleh kosong", "warning");
                return;
            }

            let parameter = {
                action: "save_answer",
                question_id: row.id, // Assuming the question object has an 'id' property
                answer: row.indonesian_word,
            };

            try {
                const response = await RequestManager(PAGE_URL, parameter);
                if (response.data.type == "success") {
                    toastService.show("Berhasil", "Jawaban berhasil disimpan.", "success");
                } else {
                    toastService.show("Gagal", response.data.message, "error");
                }
            } catch (error) {
                toastService.show("Error", "Terjadi kesalahan saat menyimpan jawaban.", "error");
                console.error("Error saving answer:", error);
            }
        },

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
