<template>
    <form @submit.prevent="submit">
        <div class="flex justify-start gap-4">
            <div>
                <TextInput type="text" v-model="form.label" placeholder="job_posting"/>
                <InputError :message="form.errors.label"/>
            </div>
            <PrimaryButton type="submit">save</PrimaryButton>
        </div>
    </form>
</template>

<script setup>
import {useToast} from "vue-toastification";
const toast = useToast();
import {useForm} from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";

const emit = defineEmits(['created'])

const form = useForm({
    label: ""
})

const submit = () => {
    form.post(route('tags.store'), {
        preserveScroll: true,
        onSuccess: params => {
            emit("created")
        },
        onError: params => {
            toast.error("See validation Errors")
        }
    });
}
</script>

<style scoped>

</style>
