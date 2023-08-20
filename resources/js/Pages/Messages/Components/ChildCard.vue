<template>
    <div class="dark:border-gray-600 border-gray-400 border p-4 rounded" >
        <div class="mb-4" v-html="messageContent">
        </div>

        <div class="flex justify-end gap-2 items-center">
            <div>{{ message.created_at}}</div>
            <div v-if="message.role === 'user'">
                <span>
                    <img class="h-8 w-8 rounded-full object-cover" :src="usePage().props.profile_photo_url" alt="" />
                </span>
            </div>
            <div v-else>
                <span>
                    <img class="h-8 w-8 rounded-full object-cover" src="/images/robot.png" alt="" />
                </span>
            </div>
        </div>
    </div>
</template>

<script setup>
import SecondaryButton from "@/Components/SecondaryButtonLink.vue";
import {computed} from "vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    message: Object,
    truncate: {
        type: Boolean,
        default: false
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
