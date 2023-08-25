<template>
    <div>
        <HTwo>Label</HTwo>
        <TextInput class="w-full"
                   placeholder="get_current_weather"
                   type="text" v-model="modelValue.label">
        </TextInput>
        <div>make sure this is the name of the function in the app/helpers.php file</div>
    </div>
    <div>
        <HTwo>Description</HTwo>
        <TextArea class="w-full"
                   placeholder="Some info for the LLM about the function and for the user"
                    v-model="modelValue.description">
        </TextArea>
        <div>This will help the user and llm understand the function</div>
    </div>
    <div class="w-full">
        <HTwo class="mb-2">Parameters</HTwo>
        <TextArea v-model="modelValue.parameters" rows="25"
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
