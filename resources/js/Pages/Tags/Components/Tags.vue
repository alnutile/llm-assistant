<template>

    <div class="flex justify-between items-center mt-2">
        <HTwo>Filter by Tags</HTwo>
        <SecondaryButton @click="toggleTagForm" type="button">add tag</SecondaryButton>
    </div>
    <div>
        <div v-if="show_tags_form" class="mt-4">
            <AddTag @created="created"></AddTag>
        </div>
    </div>
</template>

<script setup>

import HTwo from "@/Components/HTwo.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {ref} from "vue";
import AddTag from "./AddTag.vue";


const props = defineProps({
    tags: Array
})

const show_tags_form = ref(false)

const toggleTagForm = () => {
    show_tags_form.value = !show_tags_form.value
}

const created = () => {
    show_tags_form.value = false;
}


</script>

<style scoped>

</style>
