<template>
    <div>
        <HTwo>Label</HTwo>
        <TextInput class="w-full"
                   placeholder="get_current_weather"
                   type="text" v-model="modelValue.label">
        </TextInput>
        <div>make sure this is the name of the function in the app/helpers.php file</div>
    </div>
    <div class="w-full">
        <HTwo class="mb-2">Content</HTwo>
        <TextArea v-model="modelValue.content" rows="25"
                  placeholder="'functions' => [
        [
            'name' => 'get_current_weather',
            'description' => 'Get the current weather in a given location',
            'parameters' => [
                'type' => 'object',
                'properties' => [
                    'location' => [
                        'type' => 'string',
                        'description' => 'The city and state, e.g. San Francisco, CA',
                    ],
                    'unit' => [
                        'type' => 'string',
                        'enum' => ['celsius', 'fahrenheit']
                    ],
                ],
                'required' => ['location'],
            ],
        ]
    ]"
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
