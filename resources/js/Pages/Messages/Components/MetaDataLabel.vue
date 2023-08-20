<template>
    <div class="bg-indigo-500 text-white py-0.5 px-1.5">
        {{ meta_data.label }}
    </div>
</template>

<script setup>
const props = defineProps({
    meta_data: Object
})
</script>

<style scoped>

</style>
