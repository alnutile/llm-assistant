<template>
<AppLayout title="Start your thread">
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🚀 Start a message thread
        </h2>
    </template>
    <main>
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 rounded-t">
            <div class="mx-auto grid max-w-2xl grid-cols-1 grid-rows-1 items-start gap-x-8 gap-y-8 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                <div class="lg:col-start-3 lg:row-end-1 rounded p-4">
                    <div class="lg:col-start-3 lg:row-end-1 rounded border-gray-200 border dark:border-gray-500 p-4">
                        <HTwo>Start your Thread</HTwo>
                        <Copy section="messages" copy="create_info"/>
                    </div>
                    <div class="mt-4">
                        <HTwo class="flex justify-center">Attach some meta Data</HTwo>
                        <Picker @selectedEmit="selectedEmit" :selectables="meta_data"></Picker>
                    </div>
                    <div class="mt-4">
                        <HTwo class="flex justify-center">Tags</HTwo>
                        <TagPicker @selectedEmit="selectedTags" :selectables="tags"></TagPicker>

                        <div class="mt-4 flex justify-end">
                            <SecondaryButton @click="toggleShowTagForm" type="button">
                                <span v-if="!showTagForm">add tag</span>
                                <span v-else>hide tag form</span>
                            </SecondaryButton>
                        </div>
                        <div class="mt-4 flex justify-center" v-if="showTagForm">
                            <AddTag @created="created"></AddTag>
                        </div>
                    </div>
                </div>
                <div class="-mx-4 px-4 py-8 shadow-sm ring-1 ring-gray-900/5 sm:mx-0 sm:rounded-lg sm:px-8 sm:pb-14 lg:col-span-2 lg:row-span-2 lg:row-end-2 xl:px-16 xl:pb-20 xl:pt-2">
                    <form @submit.prevent="submit">
                        <ResourceForm v-model="form"/>
                        <div class="flex items-center justify-end mt-4 gap-4">
                            <ActionMessage :on="form.processing">Saving</ActionMessage>
                            <PrimaryButton>Start!</PrimaryButton>
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
import Picker from "@/Pages/MetaData/Components/Picker.vue";
import TagPicker from "@/Components/Picker.vue";
import TextArea from "@/Components/TextArea.vue";
import {useForm} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import ActionMessage from "@/Components/ActionMessage.vue";
import ResourceForm from "./Components/ResourceForm.vue"

import {useToast} from "vue-toastification";
import AddTag from "@/Pages/Tags/Components/AddTag.vue";
import {ref} from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
const toast = useToast();

const props = defineProps({
    meta_data: Array,
    tags: []
})

const showTagForm = ref(false)

const toggleShowTagForm = () => {
    showTagForm.value = !showTagForm.value
}

const created = () => {
    showTagForm.value = false
}

const form = useForm({
    content: "",
    meta_data: [],
    tags: []
})

const submit = () => {
    form.post(route("messages.store"), {
        preserveScroll: true,
        onError: params => {
            toast.error("See validation errors if none then please contact support")
        }
    });
}

const selectedTags = (item) => {
    form.tags = item;
}

const selectedEmit = (item) => {
    form.meta_data = item;
}
</script>

<style scoped>

</style>
