<template>
    <div :class="['youtube-container', classes]">
        <div class="aspect-ratio-box">
            <iframe :src="embedUrl" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen class="youtube-iframe"></iframe>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    // ID video YouTube (misalnya: "dQw4w9WgXcQ")
    videoId: {
        type: String,
        required: true,
    },
    // Opsi tambahan untuk URL embed (misalnya: autoplay=1)
    query: {
        type: String,
        default: '',
    },
    // Kelas Tailwind CSS opsional untuk container (misalnya: w-full, max-w-xl)
    classes: {
        type: String,
        default: 'w-full',
    },
});

// Computed property untuk membangun URL embed yang lengkap
const embedUrl = computed(() => {
    // Base URL embed YouTube
    let url = `https://www.youtube.com/embed/${props.videoId}?rel=0`;

    // Tambahkan query options jika ada
    if (props.query) {
        url += `&${props.query}`;
    }

    return url;
});
</script>

<style scoped>
/* CSS ini bisa Anda pindahkan ke file CSS global jika Anda tidak menggunakan Tailwind JIT untuk rasio aspek */

.youtube-container {
    /* Hanya memastikan container menggunakan lebar penuh */
}

/* Kotak untuk mengatur Aspect Ratio 16:9 */
.aspect-ratio-box {
    position: relative;
    width: 100%;
    padding-top: 56.25%;
    /* 16:9 = 9/16 * 100% = 56.25% */
    height: 0;
    overflow: hidden;
    background-color: #000;
}

/* Iframe diposisikan absolut untuk mengisi kotak aspek rasio */
.youtube-iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
</style>