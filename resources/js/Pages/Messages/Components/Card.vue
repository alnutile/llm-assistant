<template>
    <div class="dark:border-gray-600 border-gray-400 border p-4 rounded">
        <div class="mb-4">
            {{ messageContent }}
        </div>

        <div class="flex justify-end gap-2 items-center">
            <div>{{ message.created_at}}</div>
            <SecondaryButton :href="route('messages.show', {
                message: message.id
            })">view</SecondaryButton>
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
