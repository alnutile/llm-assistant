<template>
    <div>

        <h3 class="flex justify-center mt-4 font-bold">Meta Data</h3>
        <div class="flex flex-wrap gap-2 mt-4 justify-center">
            <button
                type="button"
                :class="isInToggles(item) ? 'bg-pink-500 text-white' : 'bg-indigo-700 text-white'"
                class="
                border p-4 text-left flex gap-4 justify-between hover:opacity-90"
                @click="toggleSelectables(item)"
                v-for="item in selectables" :key="item.id">
                <h3 class="font-semibold text-sm">
                    {{ item.label }}
                </h3>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.5"
                     stroke="currentColor"
                     :class="isInToggles(item) ? 'text-gray-100' : 'text-gray-300'"
                     class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </button>
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref} from "vue";
import DeleteButton from "@/Components/DeleteButton.vue";

const props = defineProps({
    selected: {
        type: Array,
        default: []
    },
    selectables: Array
})

const emit = defineEmits(['selectedEmit'])

const selectedItems = ref(new Set)

onMounted(() => {
    if(props.selected.length > 0) {
        props.selected.forEach(item => {
            toggleSelectables(item)
        })
    }
})


const toggleSelectables = (item) => {
    if (selectedItems.value.has(item)) {
        selectedItems.value.delete(item);
    } else {
        selectedItems.value.add(item);
    }

    emit("selectedEmit", Array.from(selectedItems.value))
}


const isInToggles = (item) => {
    if (selectedItems.value.has(item)) {
        return true;
    }

    return false;
}
</script>

<style scoped>

</style>
