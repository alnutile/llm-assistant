<template>
    <form @submit.prevent="submit">
        <div class="w-full mt-5">
            <div class="flex justify-end">
                <HTwo class="mb-2">Reply</HTwo>
            </div>
            <TextArea v-model="form.content" rows="10"
                      placeholder="update the initial request by replying here..."
                      class="w-full"/>
            <InputError :message="form.errors.content"></InputError>
        </div>
        <div class="flex justify-end mt-2">
            <SecondaryButton type="submit">submit</SecondaryButton>
        </div>
    </form>
</template>

<script setup>
import {useForm} from "@inertiajs/vue3";
import HTwo from "@/Components/HTwo.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import TextArea from "@/Components/TextArea.vue";
import Checkbox from "@/Components/Checkbox.vue";
import Select from "@/Components/Select.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    parent: Object
})

const form = useForm({
    content: ""
})

const submit = () => {
    form.put(route("message_reply.reply", {
        message: props.parent.id
    }), {
        preserveScroll: false
    })
}
</script>

<style scoped>

</style>
