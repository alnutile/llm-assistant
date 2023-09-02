<template>
    <div class="dark:border-gray-600 border-gray-400 border p-4 rounded">
        <div class="mb-4 overflow-auto prose" v-html="message.content_formatted">
        </div>

        <div class="flex justify-end gap-1 items-center">
            <div>{{ message.created_at}}</div>
            <div>
                <span>
                    <img class="h-8 w-8 rounded-full object-cover" :src="usePage().props.profile_photo_url" alt="" />
                </span>
            </div>
            <SecondaryButton
                v-if="!route().current('messages.show', {
                    message: message.id
                })"
                :href="route('messages.show', {
                message: message.id
            })">view</SecondaryButton>
            <Rerun :message="message"/>
        </div>

        <div class="justify-start flex gap-2 hidden sm:block" v-if="pills">
            Meta Data:
            <MetaDataLabel v-for="meta in message.meta_data" :key="meta.id" :meta_data="meta"/>
        </div>

        <div class="justify-start flex gap-2 mt-2 hidden sm:block" v-if="pills">
            Tags:
            <TagLabel
                v-for="tag in message.tags" :key="tag.id" :tag="tag"/>
        </div>

        <div class="justify-start flex gap-2 mt-2 hidden sm:block" v-if="pills">
            Functions:
            <LlmFunctionLabel
                v-for="llm_function in message.llm_functions" :key="llm_function.id" :llm_function="llm_function"/>
        </div>
    </div>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButtonLink.vue";
import {computed} from "vue";
import Rerun from "./Rerun.vue";
import MetaDataLabel from "./MetaDataLabel.vue";
import TagLabel from "./TagLabel.vue";
import LlmFunctionLabel from "./LlmFunctionLabel.vue";

const props = defineProps({
    message: Object,
    truncate: {
        default: false
    },
    pills: {
        default: true
    }
})

const messageContent = computed(() => {
    if(props.truncate) {
        return _.truncate(props.message.content_formatted, {
            length: 350
        })
    }
    return props.message.content_formatted;
})
</script>

<style scoped>

</style>
