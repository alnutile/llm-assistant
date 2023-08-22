<template>
<DeleteButton @click="deleteForm">delete</DeleteButton>
</template>

<script setup>
import {useForm} from "@inertiajs/vue3";
import DeleteButton from "@/Components/DeleteButton.vue";

const props = defineProps({
    message: Object
})

const form = useForm({})

const deleteForm = () => {
    form.delete(route('messages.delete', {
        message: props.message.id
    }))
}

</script>

<style scoped>

</style>
