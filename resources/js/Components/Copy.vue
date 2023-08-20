<template>
    <div :class="classValue">
        {{ output }}
    </div>
</template>

<script setup>
import {computed} from "vue";
import { Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    section: String,
    copy: String,
    classValue: {
        type: String,
        default: "text-gray-500 dark:text-gray-400 text-sm"
    }
})

const output = computed(() => {
    return usePage().props.copy[props.section][props.copy]
})

</script>

<style scoped>

</style>
