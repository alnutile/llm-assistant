<template>
<AppLayout title="Start your thread">
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📀 Edit LLM Function
        </h2>
    </template>
    <main>
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 rounded-t">
            <div class="mx-auto grid max-w-2xl grid-cols-1 grid-rows-1 items-start gap-x-8 gap-y-8 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                <div class="-mx-4 px-4 py-8 shadow-sm ring-1 ring-gray-900/5 sm:mx-0 sm:rounded-lg sm:px-8 sm:pb-14 lg:col-span-2 lg:row-span-2 lg:row-end-2 xl:px-16 xl:pb-20 xl:pt-2">
                    <form @submit.prevent="submit">
                        <ResourceForm v-model="form"/>
                        <div class="flex items-center justify-end mt-4 gap-4">
                            <ActionMessage :on="form.recentlySuccessful">Updated</ActionMessage>
                            <PrimaryButton>Update</PrimaryButton>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </main>
</AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Copy from "@/Components/Copy.vue";
import HTwo from "@/Components/HTwo.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextArea from "@/Components/TextArea.vue";
import {useForm} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import ActionMessage from "@/Components/ActionMessage.vue";

import {useToast} from "vue-toastification";
import TextInput from "@/Components/TextInput.vue";
import ResourceForm from "./Components/ResourceForm.vue";
const toast = useToast();

const props = defineProps({
    llm_function: Object
})

const form = useForm({
    label: props.llm_function.data.label,
    content: props.llm_function.data.content,
    active: props.llm_function.data.active
})

const submit = () => {
    form.put(route("llm_functions.update", {
        llm_function: props.llm_function.data.id
    }), {
        preserveScroll: true,
        onError: params => {
            toast.error("See validation errors if none then please contact support")
        }
    });
}
</script>

<style scoped>

</style>
