<!-- components/SkeletonTable.vue -->
<template>
    <table class="tablestack table-bordered table-skeleton" v-auto-label>
        <thead>
            <tr>
                <th v-for="i in columns" :key="'th-' + i">
                    <div class="skeleton skeleton-cell"></div>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="n in rows" :key="'tr-' + n">
                <td v-for="i in columns" :key="'td-' + n + '-' + i">
                    <div class="skeleton skeleton-cell"></div>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    name: 'SkeletonTable',
    props: {
        rows: {
            type: Number,
            default: 3,
        },
        columns: {
            type: Number,
            default: 3,
        },
    },
};
</script>

<style scoped>
@keyframes skeleton-loading {
    0% {
        background-position: -200px 0;
    }

    100% {
        background-position: calc(200px + 100%) 0;
    }
}

.skeleton {
    animation: skeleton-loading 1.2s infinite linear;
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 37%, #f0f0f0 63%);
    background-size: 400% 100%;
    border-radius: 4px;
}

.table-skeleton {
    width: 100%;
    border-collapse: collapse;
}

.skeleton-cell {
    height: 20px;
    width: 100%;
}
</style>
