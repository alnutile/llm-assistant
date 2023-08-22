<template>
<AppLayout title="Start your thread">
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center justify-between">
            <div>
                📢 Message Thread #{{message.data.id}}
            </div>
            <div class="flex justify-end items-center gap-2 z-50">
                <SecondaryButtonLink :href="route('messages.edit', {
                message: message.data.id
            })">edit</SecondaryButtonLink>

                <Delete :message="message.data"/>
            </div>
        </h2>
    </template>
    <main>
        <div class="mx-auto max-w-7xl px-4 py-4 sm:px-2 lg:px-2 rounded-t">
            <div class="mx-auto grid max-w-2xl grid-cols-1 grid-rows-1 items-start gap-x-8 gap-y-8 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                <div class="lg:col-start-3 lg:row-end-1 rounded border-gray-200 border dark:border-gray-500 p-4">
                    <HTwo>Start your Thread</HTwo>
                    <Copy section="messages" copy="show_info"/>
                </div>

                <div class="lg:col-start-3 lg:row-end-2 p-4">
                    <div class="ap-2">
                        <HTwo class="mb-4">Meta Data:</HTwo>
                        <div class="flex justify-center gap-2">
                            <MetaDataLabel v-for="meta in message.data.meta_data" :key="meta.id" :meta_data="meta"/>
                        </div>
                    </div>

                    <div class="gap-2 mt-2">
                        <HTwo class="mb-4">Tags:</HTwo>
                        <div class="flex justify-center gap-2">
                            <TagLabel
                                v-for="tag in message.data.tags" :key="tag.id" :tag="tag"/>
                        </div>
                    </div>

                    <div class="gap-2 mt-2">
                       <HTwo class="mb-4">Functions:</HTwo>
                        <div class="flex justify-center gap-2">
                            <LlmFunctionLabel
                                v-for="llm_function in message.data.llm_functions" :key="llm_function.id" :llm_function="llm_function"/>
                        </div>
                    </div>
                </div>


                <div class="-mx-4 px-4 py-2 shadow-sm ring-1 ring-gray-900/5 sm:mx-0 sm:rounded-lg sm:px-8 sm:pb-14 lg:col-span-2 lg:row-span-2 lg:row-end-2 xl:px-16 xl:pb-20 xl:pt-2">
                    <Card :message="message.data" :pills="false"/>
                    <div class="mt-4">
                        <div v-if="message.data.children.length === 0">
                            Responses will show here shortly...
                        </div>
                        <div v-else>
                            <div v-for="child in message.data.children" :key="child.id" class="mt-2 mb-2">
                                <ChildCard :message="child"/>
                            </div>
                            <ReplyCard :parent="message.data"/>
                        </div>
                    </div>
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
import {router} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import Card from "./Components/Card.vue";
import ChildCard from "./Components/ChildCard.vue";
import SecondaryButtonLink from "@/Components/SecondaryButtonLink.vue";
import ReplyCard from "./Components/ReplyCard.vue";
import MetaDataLabel from "@/Pages/Messages/Components/MetaDataLabel.vue";
import TagLabel from "@/Pages/Messages/Components/TagLabel.vue";
import LlmFunctionLabel from "@/Pages/Messages/Components/LlmFunctionLabel.vue";
import Delete from "./Components/Delete.vue";
import {onMounted} from "vue";

const props = defineProps({
    message: Object
})

onMounted(() => {
    Echo.private(`message.${props.message.data.id}`)
        .listen('.status', (event) => {
            router.reload({
                preserveScroll: true,
                preserveState:false
            })
        })
})


</script>

<style scoped>

</style>
