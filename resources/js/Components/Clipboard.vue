<template>
    <button @click="copyToClipboard(content)"
            class="py-1 px-2 bg-red-600 rounded-lg hover:bg-red-500 text-sm
            font-semibold
            text-white">
        Copy
    </button>
</template>

<script setup>

import {useToast} from "vue-toastification";
const toast = useToast();

const props = defineProps({
    content: String
})

const copyToClipboard = (content) => {
    navigator.clipboard
        .writeText(content)
        .then(() => {
            toast("Copied")
        })
        .catch((err) => {
            console.error("Failed to copy to clipboard:", err);
        });
};
</script>
