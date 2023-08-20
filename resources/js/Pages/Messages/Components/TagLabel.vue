<template>
    <div class="bg-pink-500 text-white py-0.5 px-1.5">
        {{ tag.label }}
    </div>
</template>

<script setup>
const props = defineProps({
    tag: Object
})
</script>

<style scoped>

</style>
