


<script setup>
import { onMounted, ref } from 'vue';
defineProps({
    modelValue: String,
});
defineEmits(['update:modelValue']);
</script>

<template>
    <textarea
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm dark:bg-gray-300 text-black placeholder:text-gray-500"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>
