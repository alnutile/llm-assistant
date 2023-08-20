<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>

    <button
        :type="type"
        class="bg-red-500 hover:bg-red-600 text-white rounded-lg h-8 px-4 text-sm">
        <slot />
    </button>
</template>
