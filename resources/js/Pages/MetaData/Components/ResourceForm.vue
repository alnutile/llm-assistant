<template>
    <div>
        <HTwo>Label</HTwo>
        <TextInput class="w-full"
                   placeholder="Social Links"
                   type="text" v-model="modelValue.label"></TextInput>
    </div>
    <div class="w-full">
        <HTwo class="mb-2">Content</HTwo>
        <TextArea v-model="modelValue.content" rows="25"
                  placeholder="Write out meta data like social links or websites to consider etc"
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
