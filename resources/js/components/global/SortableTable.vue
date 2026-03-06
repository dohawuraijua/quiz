<template>
    <table class="tablestack table-hover" ref="autolabelTable" v-auto-label>
        <thead>
            <tr>
                <th>#</th>
                <th v-for="(col, i) in columns" :key="i" @click="handleSort(col)" style="cursor:pointer">
                    {{ col.label }}
                    <span v-if="col.sortable">
                        <i v-if="sortKey === col.key && sortOrder === 'asc'" class="fas fa-sort-up"></i>
                        <i v-else-if="sortKey === col.key && sortOrder === 'desc'" class="fas fa-sort-down"></i>
                        <i v-else class="fas fa-sort"></i>
                    </span>
                </th>
                <th v-if="hasActions">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(row, index) in sortedData" :key="row.id || index">
                <td>{{ index + 1 }}</td>
                <td v-for="(col, j) in columns" :key="j">
                    <slot name="custom" :row="row" :column="col">
                        {{ getNestedValue(row, col.key) }}
                    </slot>
                </td>
                <td nowrap v-if="hasActions">
                    <slot name="actions" :row="row" />
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    name: "SortableTable",
    props: {
        data: {
            type: Array,
            required: true,
        },
        columns: {
            type: Array,
            required: true,
        },
        hasActions: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            sortKey: null,
            sortOrder: "asc",
        };
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
            // Re-apply auto label logic manually (in case v-auto-label is not reactive)
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
th {
    user-select: none;
}
</style>
