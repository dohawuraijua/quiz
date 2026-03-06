<template>
    <div class="mt-3" v-if="pagination && totalPages > 1">
        <div class="flex flex-col md:flex-row justify-between items-center text-center md:text-left mb-2">
            <p class="mb-2 text-sm md:mb-0">
                Menampilkan {{ startItem }}–{{ endItem }} dari {{ pagination.total }} data
            </p>
            <p class="mb-2 text-sm md:mb-0">
                Halaman {{ currentPage }} dari {{ totalPages }}
            </p>
        </div>

        <div class="flex justify-center md:justify-center">
            <ul class="flex space-x-1">
                <li :class="{ 'opacity-50 pointer-events-none': isFirstPage }">
                    <a href="#" class="px-3 py-2 rounded-md border border-gray-300 text-sm hover:bg-gray-100 transition"
                        @click.prevent="changePage(1)">
                        «
                    </a>
                </li>

                <li :class="{ 'opacity-50 pointer-events-none': isFirstPage }">
                    <a href="#" class="px-3 py-2 rounded-md border border-gray-300 text-sm hover:bg-gray-100 transition"
                        @click.prevent="changePage(currentPage - 1)">
                        ‹
                    </a>
                </li>

                <li v-for="(page, index) in paginationRange" :key="index" :class="[
                    'text-sm',
                    page === currentPage ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100',
                    page === '...' ? 'pointer-events-none text-gray-400' : '',
                    'px-3 py-2 border border-gray-300 rounded-md transition'
                ]" @click.prevent="changePage(page)">
                    <a v-if="page !== '...'" href="#">
                        {{ page }}
                    </a>
                    <span v-else>{{ page }}</span>
                </li>

                <li :class="{ 'opacity-50 pointer-events-none': isLastPage }">
                    <a href="#" class="px-3 py-2 rounded-md border border-gray-300 text-sm hover:bg-gray-100 transition"
                        @click.prevent="changePage(currentPage + 1)">
                        ›
                    </a>
                </li>

                <li :class="{ 'opacity-50 pointer-events-none': isLastPage }">
                    <a href="#" class="px-3 py-2 rounded-md border border-gray-300 text-sm hover:bg-gray-100 transition"
                        @click.prevent="changePage(totalPages)">
                        »
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    name: "PaginationTail",
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

            // Check for mobile width. This is not fully reactive but works on load.
            const isMobile = window.innerWidth < 1768;

            if (isMobile && total > 5) {
                const maxPagesToShow = 5;
                let startPage = Math.max(1, current - 2);
                let endPage = Math.min(total, startPage + maxPagesToShow - 1);

                if (endPage === total) {
                    startPage = Math.max(1, total - maxPagesToShow + 1);
                }

                const range = [];
                for (let i = startPage; i <= endPage; i++) {
                    range.push(i);
                }
                return range;
            }

            // Original desktop logic
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
            window.scrollTo({
                top: 0,
                behavior: "smooth" // Untuk efek halus
            });
        },
    },
};
</script>
