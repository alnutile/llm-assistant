<script setup>
import { Link } from "@inertiajs/vue3"
defineProps({
    href: {
        type: String,
    },
});
</script>

<template>
    <Link :href="href"
            class="inline-flex items-center px-4 py-2 bg-pink-500 border border-pink-900 font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
        <slot />
    </Link>
</template>
