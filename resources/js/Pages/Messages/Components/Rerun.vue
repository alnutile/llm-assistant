<template>
<button @click="run" class="text-gray-500 italic ml-2 underline">rerun</button>
</template>

<script setup>
import {useForm} from "@inertiajs/vue3";
import DeleteButton from "@/Components/DeleteButton.vue";

const props = defineProps({
    message: Object
})

const form = useForm({})

const run = () => {
    form.post(route('messages.rerun', {
        message: props.message.id
    }))
}

</script>

<style scoped>

</style>
