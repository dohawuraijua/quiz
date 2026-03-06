<template>
    <div>
        <section class="py-2 px-4 sm:px-2 lg:px-8">
            <div class="max-w-8xl mx-auto">
                <div class="w-full rounded  p-0">
                    <div class="flex flex-wrap w-full justify-between items-start gap-3 mb-3">
                    </div>
                    <SkeletonTable v-if="loadingView" :rows="10" :columns="columns.length" />
                    <div v-else>


                        <SortableTableTail :data="items" :columns="columns" :hasActions="false">

                            <template #actions="{ row }">
                                <span class="url text-2xl cursor-pointer" @click="saveAnswer(row)">💾</span>
                            </template>
                            <template #custom="{ row, column }">
                                <span v-if="column.key == 'indonesian_word'">
                                    {{ row.indonesian_word }}
                                </span>

                                <span v-if="column.key == 'english_word'">
                                    {{ row.english_word }}
                                </span>
                                <span v-if="column.key == 'score'" :class="scoreBadge(row.score)"
                                    class="px-2 py-1 text-xs font-semibold rounded">
                                    {{ row.score }}
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

const PAGE_URL = "quiz/isian-hasil";
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
                { key: "score", label: "Score" },
                { key: "ai_feedback", label: "Feedback" },
            ],
            pagination: {},
        };
    },
    computed: {
    },
    mounted() {
        this.viewData();
    },
    methods: {

        async viewData(page = 1) {
            this.loadingView = true;
            let parameter = {
                action: "view",
                page: page
            };

            try {
                const response = await RequestManager(PAGE_URL, parameter);
                if (response.data.type == "success") {
                    this.items = response.data.data.data;
                    this.pagination = response.data.data;
                } else {
                    toastService.show("Gagal", response.data.message, "error");
                }
            } catch (err) {
                console.log(err);

                toastService.show("Error", "Gagal memuat data soal", "error");
            } finally {
                this.loadingView = false;
            }
        },

        scoreBadge(score) {

            if (score === 100) {
                return 'bg-green-500 text-white'
            }

            if (score >= 90) {
                return 'bg-green-400 text-white'
            }

            if (score >= 75) {
                return 'bg-blue-500 text-white'
            }

            if (score >= 60) {
                return 'bg-yellow-400 text-black'
            }

            if (score >= 40) {
                return 'bg-orange-400 text-white'
            }

            if (score > 0) {
                return 'bg-red-500 text-white'
            }

            return 'bg-red-700 text-white'
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
