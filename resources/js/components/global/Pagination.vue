<template>
    <div class="mt-3" v-if="pagination && totalPages > 1">
        <div
            class="d-flex justify-content-between align-items-center mb-2 flex-column flex-md-row text-center text-md-left">
            <p class="mb-2 mb-md-0">
                Menampilkan {{ startItem }}–{{ endItem }} dari {{ pagination.total }} data
            </p>
            <p class="mb-2 mb-md-0">
                Halaman {{ currentPage }} dari {{ totalPages }}
            </p>
        </div>

        <div class="d-flex justify-content-center">
            <ul class="pagination">
                <li class="page-item" :class="{ disabled: isFirstPage }">
                    <a class="page-link" href="#" @click.prevent="changePage(1)">First</a>
                </li>

                <li class="page-item" :class="{ disabled: isFirstPage }">
                    <a class="page-link" href="#" @click.prevent="changePage(currentPage - 1)">Previous</a>
                </li>

                <li class="page-item" v-for="(page, index) in paginationRange" :key="index"
                    :class="{ active: page === currentPage, disabled: page === '...' }">
                    <a v-if="page !== '...'" class="page-link" href="#" @click.prevent="changePage(page)">
                        {{ page }}
                    </a>
                    <span v-else class="page-link">…</span>
                </li>

                <li class="page-item" :class="{ disabled: isLastPage }">
                    <a class="page-link" href="#" @click.prevent="changePage(currentPage + 1)">Next</a>
                </li>

                <li class="page-item" :class="{ disabled: isLastPage }">
                    <a class="page-link" href="#" @click.prevent="changePage(totalPages)">Last</a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    name: "Pagination",
    props: {
        pagination: {
            type: Object,
            required: true,
        },
    },
    computed: {
        currentPage() {
            return this.pagination.current_page;
        },
        totalPages() {
            return this.pagination.last_page;
        },
        perPage() {
            return this.pagination.per_page || this.pagination.perPage || 10;
        },
        totalItems() {
            return this.pagination.total;
        },
        startItem() {
            return (this.currentPage - 1) * this.perPage + 1;
        },
        endItem() {
            const end = this.currentPage * this.perPage;
            return end > this.totalItems ? this.totalItems : end;
        },
        isFirstPage() {
            return this.currentPage === 1;
        },
        isLastPage() {
            return this.currentPage === this.totalPages;
        },
        paginationRange() {
            const total = this.totalPages;
            const current = this.currentPage;
            const delta = 2;
            const range = [];
            const rangeWithDots = [];
            let l;

            for (let i = 1; i <= total; i++) {
                if (i === 1 || i === total || (i >= current - delta && i <= current + delta)) {
                    range.push(i);
                }
            }

            for (let i of range) {
                if (l) {
                    if (i - l === 2) {
                        rangeWithDots.push(l + 1);
                    } else if (i - l > 2) {
                        rangeWithDots.push("...");
                    }
                }
                rangeWithDots.push(i);
                l = i;
            }

            return rangeWithDots;
        },
    },
    methods: {
        changePage(page) {
            if (page !== "..." && page >= 1 && page <= this.totalPages) {
                this.$emit("page-changed", page);
            }
        },
    },
};
</script>
