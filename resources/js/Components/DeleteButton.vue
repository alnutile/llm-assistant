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
        class="text-white h-8 px-4 text-sm hover:text-red-100">
        <slot />
    </button>
</template>
