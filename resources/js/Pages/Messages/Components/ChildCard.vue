<template>
    <div class="dark:border-gray-600 border-gray-400 border p-4 rounded" >
        <div class="mb-4" v-html="messageContent">
        </div>

        <div class="flex justify-end gap-2 items-center">
            <div>{{ message.created_at}}</div>
            <div v-if="message.role === 'user'">You Replied</div>
            <div v-else>
                <span>
                    <img class="inline-block h-8 w-8 rounded-full" src="/images/robot.png" alt="" />
                </span>
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
        return _.truncate(props.message.content_formatted, {
            length: 350
        })
    }
    return props.message.content_formatted;
})
</script>

<style scoped>

</style>
