<template>
<AppLayout title="Start your thread">
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📢 Message Thread #{{message.data.id}}
        </h2>
    </template>
    <main>
        <div class="mx-auto max-w-7xl px-4 py-4 sm:px-2 lg:px-2 rounded-t">
            <div class="mx-auto grid max-w-2xl grid-cols-1 grid-rows-1 items-start gap-x-8 gap-y-8 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                <div class="lg:col-start-3 lg:row-end-1 rounded border-gray-200 border dark:border-gray-500 p-4">
                    <HTwo>Start your Thread</HTwo>
                    <Copy section="messages" copy="create_info"/>
                </div>
                <div class="-mx-4 px-4 py-2 shadow-sm ring-1 ring-gray-900/5 sm:mx-0 sm:rounded-lg sm:px-8 sm:pb-14 lg:col-span-2 lg:row-span-2 lg:row-end-2 xl:px-16 xl:pb-20 xl:pt-2">
                    <Card :message="message.data"/>
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
import Card from "./Components/Card.vue";

const props = defineProps({
    message: Object
})

const form = useForm({
    message: ""
})

const submit = () => {

}
</script>

<style scoped>

</style>
