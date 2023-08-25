<template>

    <div class="w-full">
        <div v-for="task in tasks" :key="task.id" class="w-full flex justify-start border border-gray-700 p-4 mb-1">
            {{ task.description }} {{ task.due}}
        </div>
    </div>

</template>

<script setup>
const props = defineProps({
    tasks: Array
})
</script>

<style scoped>

</style>
