<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex items-center px-4 py-2 bg-indigo-500
    dark:bg-indigo-500 border
    border-transparent font-semibold text-xs text-white uppercase
    tracking-widest hover:bg-indigo-500 focus:bg-indigo-500
    active:bg-indigo-900
    focus:outline-none
    focus:ring-2
    focus:ring-indigo-500 focus:ring-offset-2
    transition ease-in-out duration-150">
        <slot />
    </button>
</template>
