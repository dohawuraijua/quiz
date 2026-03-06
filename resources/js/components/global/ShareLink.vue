<template>

    <div>
        <button :class="class" @click="showModal = true">
            Share ➦
        </button>
        <div class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center" v-if="showModal"
            ref="modal">
            <div class="bg-white rounded-lg w-5/10 md:w-1/4 max-w-4xl overflow-auto max-h-[90vh]">
                <div class="border-b px-6 py-4 flex justify-between items-center">
                    <h5 class="text-lg font-semibold">Share to</h5>
                    <button type="button" class="text-gray-500 hover:text-gray-700" @click="showModal = false"
                        aria-label="Close">✖</button>
                </div>
                <div ref="notaContent" class="px-6 py-4 bg-white text-black flex flex-wrap share-links gap-3">



                    <button type="button" class="btn block px-4 py-2 bg-green-500 text-sm text-white rounded"
                        :href="whatsAppUrl" target="_blank" rel="noopener noreferrer" aria-label="Share to WhatsApp">
                        WhatsApp
                    </button>

                    <button type="button" class="btn block px-4 py-2 text-sm text-white bg-blue-500 rounded"
                        :href="facebookUrl" target="_blank" rel="noopener noreferrer" aria-label="Share to Facebook">
                        Facebook
                    </button>

                    <button type="button" class="btn block px-4 py-2 text-sm text-white bg-black rounded"
                        :href="twitterUrl" target="_blank" rel="noopener noreferrer" aria-label="Share to Twitter/X">
                        Twitter/X
                    </button>

                    <button type="button" class="btn block px-4 py-2 text-sm text-black bg-blue-200 rounded"
                        @click="copyLink">
                        {{ copied ? "Copied!" : "Copy link" }}
                    </button>
                    <button type="button" @click="nativeShare" class="block px-4 py-2 text-sm text-black rounded btn"
                        :style="{ display: canNativeShare ? '' : 'none' }">
                        Lainnya
                    </button>
                </div>

            </div>
        </div>


    </div>
</template>

<script setup>
import { computed, ref } from "vue";
import toastService from "../helper/toastService";

const showModal = ref(false);

const props = defineProps({
    url: { type: String, default: () => window.location.href },
    title: { type: String, default: () => document.title || "Shared link" },
    text: { type: String, default: "" }, // opsional, buat WA/X
    class: { type: Boolean, default: "bg-blue-500 btn-primary text-white text-sm px-3 py-2 rounded" }
});

// Pastikan URL absolute
const shareUrl = computed(() => {
    try {
        return new URL(props.url, window.location.origin).toString();
    } catch {
        return window.location.href;
    }
});

const shareText = computed(() => {
    // gabungkan text + url untuk WA/X biar lebih “jadi”
    const t = (props.text || "").trim();
    return t ? `${t}\n${shareUrl.value}` : shareUrl.value;
});

const whatsAppUrl = computed(() => {
    // wa.me lebih universal
    return `https://wa.me/?text=${encodeURIComponent(shareText.value)}`;
});

const facebookUrl = computed(() => {
    // Facebook sharer pakai parameter u
    return `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(
        shareUrl.value
    )}`;
});

const twitterUrl = computed(() => {
    // X intent
    const params = new URLSearchParams();
    params.set("url", shareUrl.value);
    if (props.text) params.set("text", props.text);
    if (props.title && !props.text) params.set("text", props.title);
    return `https://twitter.com/intent/tweet?${params.toString()}`;
});

const copied = ref(false);

async function copyLink() {
    try {
        await navigator.clipboard.writeText(shareUrl.value);
        copied.value = true;
        toastService.show("Success", "Link copied to clipboard", "success");
        setTimeout(() => (copied.value = false), 1200);
    } catch {
        // fallback lama (kalau clipboard API diblokir)
        const ta = document.createElement("textarea");
        ta.value = shareUrl.value;
        ta.setAttribute("readonly", "");
        ta.style.position = "fixed";
        ta.style.opacity = "0";
        document.body.appendChild(ta);
        ta.select();
        document.execCommand("copy");
        document.body.removeChild(ta);

        copied.value = true;
        setTimeout(() => (copied.value = false), 1200);
    }
}

// Web Share API (native share sheet di mobile)
const canNativeShare = computed(() => typeof navigator !== "undefined" && !!navigator.share);

async function nativeShare() {
    if (!canNativeShare.value) return;
    try {
        await navigator.share({
            title: props.title,
            text: props.text || props.title,
            url: shareUrl.value,
        });
    } catch {
        // user cancel / not supported
    }
}
</script>

<style scoped>
.share-links {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
</style>
