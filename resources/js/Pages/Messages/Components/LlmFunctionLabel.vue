<template>
    <div class="bg-purple-900 text-white py-0.5 px-1.5">
        {{ llm_function.label }}
    </div>
</template>

<script setup>
const props = defineProps({
    llm_function: Object
})
</script>

<style scoped>

</style>
