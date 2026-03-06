<template>
    <div>
        <table ref="autolabelTable" v-auto-label class="min-w-full rounded-lg text-sm text-left text-gray-700">
            <thead class="bg-gray-100 text-gray-800">
                <tr>
                    <th class="px-4 py-2 border-b font-semibold">#</th>
                    <th v-for="(col, i) in columns" :key="i" @click="handleSort(col)"
                        class="px-4 py-2 border-b font-semibold cursor-pointer select-none hover:bg-gray-200 transition">
                        <div class="flex items-center gap-1">
                            <span>{{ col.label }}</span>
                            <span v-if="col.sortable">
                                <i v-if="sortKey === col.key && sortOrder === 'asc'"
                                    class="fas fa-sort-up text-blue-500"></i>
                                <i v-else-if="sortKey === col.key && sortOrder === 'desc'"
                                    class="fas fa-sort-down text-blue-500"></i>
                                <i v-else class="fas fa-sort text-gray-400"></i>
                            </span>
                        </div>
                    </th>
                    <th v-if="hasActions" class="px-4 py-2 border-b font-semibold text-center">
                        Actions
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(row, index) in sortedData" :key="index" class="hover:bg-gray-50 even:bg-gray-50 transition">
                    <td class="px-4 py-2 border-b text-gray-600">{{ index + 1 }}</td>
                    <td v-for="(col, j) in columns" :key="j" class="px-4 py-2 border-b text-gray-700">
                        <slot name="custom" :row="row" :column="col">
                            {{ getNestedValue(row, col.key) }}
                        </slot>
                    </td>
                    <td v-if="hasActions" class="px-4 py-2 border-b whitespace-nowrap text-center">
                        <slot name="actions" :row="row" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: "SortableTable",
    props: {
        data: { type: Array, required: true },
        columns: { type: Array, required: true },
        hasActions: { type: Boolean, default: false },
    },
    data() {
        return { sortKey: null, sortOrder: "asc" };
    },
    computed: {
        sortedData() {
            if (!this.sortKey) return this.data;
            return [...this.data].sort((a, b) => {
                const aVal = this.getNestedValue(a, this.sortKey);
                const bVal = this.getNestedValue(b, this.sortKey);
                if (aVal === null || aVal === undefined) return 1;
                if (bVal === null || bVal === undefined) return -1;
                if (typeof aVal === "number" && typeof bVal === "number") {
                    return this.sortOrder === "asc" ? aVal - bVal : bVal - aVal;
                }
                const aStr = String(aVal).toLowerCase();
                const bStr = String(bVal).toLowerCase();
                return this.sortOrder === "asc"
                    ? aStr.localeCompare(bStr)
                    : bStr.localeCompare(aStr);
            });
        },
    },
    watch: {
        sortedData() {
            this.applyAutoLabel();
        },
    },
    mounted() {
        this.applyAutoLabel();
    },
    updated() {
        this.applyAutoLabel();
    },
    methods: {
        getNestedValue(obj, path) {
            return path.split(".").reduce((o, p) => (o ? o[p] : null), obj);
        },
        handleSort(col) {
            if (!col.sortable) return;
            if (this.sortKey === col.key) {
                this.sortOrder = this.sortOrder === "asc" ? "desc" : "asc";
            } else {
                this.sortKey = col.key;
                this.sortOrder = "asc";
            }
        },
        applyAutoLabel() {
            const el = this.$refs.autolabelTable;
            if (!el) return;
            const headers = Array.from(el.querySelectorAll("thead th")).map((th) =>
                th.textContent.trim()
            );
            el.querySelectorAll("tbody tr").forEach((row) => {
                Array.from(row.children).forEach((td, i) => {
                    if (headers[i]) {
                        td.setAttribute("data-label", headers[i]);
                    }
                });
            });
        },
    },
};
</script>

<style scoped>
/* 🌙 RESPONSIVE STACKED MODE */
@media (max-width: 768px) {
    thead {
        display: none;
    }

    tbody tr {
        display: block;
        margin-bottom: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        background: white;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    tbody td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #f3f4f6;
    }

    tbody td:last-child {
        border-bottom: none;
    }

    tbody td::before {
        content: attr(data-label);
        font-weight: 600;
        color: #374151;
        text-transform: capitalize;
    }
}
</style>
