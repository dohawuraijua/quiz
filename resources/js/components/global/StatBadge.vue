<script setup>
defineProps({
    label: String,
    value: Number,
    color: String
});
</script>

<template>
    <div class="p-4 rounded text-center" :class="'bg-' + color + '-100 text-' + color + '-700'">
        <div class="text-sm">{{ label }}</div>
        <div class="text-2xl font-bold">{{ value }}</div>
    </div>
</template>