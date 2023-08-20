<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    label: {
      type: String,
      default: "Choose One"
    },
    modelValue: String,
    options: Object
});

defineEmits(['update:modelValue']);


</script>

<template>

    <select
        class="bg-input focus:border-ring ring-1 ring-ring"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
        <option disabled value="">{{label}}</option>
        <option v-for="option in options"
                :key="option.id"
        :value="option.id">{{option.name}}</option>
    </select>


</template>
