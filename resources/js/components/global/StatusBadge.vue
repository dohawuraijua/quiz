<script setup>
import { computed } from 'vue';

const props = defineProps({
    status: {
        type: Number,
        required: true
    }
});

// Mapping label dan warna berdasarkan status code
const statusConfig = {
    100: { label: 'Pending', class: 'bg-yellow-500 text-white border-yellow-200' },
    200: { label: 'Ready', class: 'bg-blue-400 text-white border-blue-200' },
    300: { label: 'Success', class: 'bg-green-500 white border-green-200' },
    400: { label: 'Failed', class: 'bg-red-300 white border-red-200' },
};

const currentStatus = computed(() => {
    return statusConfig[props.status] || { label: 'Unknown', class: 'bg-gray-100 text-gray-800 border-gray-200' };
});
</script>

<template>
    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium border" :class="currentStatus.class">
        {{ currentStatus.label }}
    </span>
</template>