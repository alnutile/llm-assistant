<template>
    <div class="dark:border-gray-600 border-gray-400 border p-4 rounded">
        <div class="mb-4">
            {{ messageContent }}
        </div>

        <div class="flex justify-end gap-2 items-center">
            <div>{{ message.created_at}}</div>
            <SecondaryButton
                v-if="!route().current('messages.show', {
                    message: message.id
                })"
                :href="route('messages.show', {
                message: message.id
            })">view</SecondaryButton>
        </div>

        <div class="justify-start flex gap-2">
            Meta Data:
            <div v-for="meta in message.meta_data" :key="meta.id" class="bg-indigo-500 text-white py-0.5 px-1.5">
                {{ meta.label }}
            </div>
        </div>
    </div>
</template>

<script setup>
import SecondaryButton from "@/Components/SecondaryButtonLink.vue";
import {computed} from "vue";

const props = defineProps({
    message: Object,
    truncate: {
        type: Boolean,
        default: false
    }
})

const messageContent = computed(() => {
    if(props.truncate) {
        return _.truncate(props.message.content, {
            length: 350
        })
    }
    return props.message.content;
})
</script>

<style scoped>

</style>
