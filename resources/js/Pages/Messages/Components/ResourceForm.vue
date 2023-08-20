<template>
    <div class="w-full">
        <HTwo class="mb-2">Message</HTwo>
        <TextArea v-model="modelValue.content" rows="25"
                  placeholder="Below is a job posting on Upwork please make a reply for me..."
                  class="w-full"/>
        <InputError :message="modelValue.errors.content"></InputError>
    </div>
</template>

<script setup>
import HTwo from "@/Components/HTwo.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import TextArea from "@/Components/TextArea.vue";
import Checkbox from "@/Components/Checkbox.vue";
import Select from "@/Components/Select.vue";
defineEmits(['update:modelValue']);

const props = defineProps({
    modelValue: Object
})
</script>

<style scoped>

</style>
