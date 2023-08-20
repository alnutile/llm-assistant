<template>
    <AppLayout title="Your Meta Data">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Your Meta Data
            </h2>
        </template>
        <main>
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-2 lg:px-2 rounded-t">
                <div class="mx-auto grid max-w-2xl grid-cols-1 grid-rows-1 items-start gap-x-8 gap-y-8 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                    <div class="lg:col-start-3 lg:row-end-1 rounded border-gray-200 border dark:border-gray-500 p-4">
                        <HTwo>Your Meta Data</HTwo>
                        <Copy section="meta_data" copy="create_info"/>
                    </div>
                    <div class="-mx-4 px-4 py-2 shadow-sm ring-1 ring-gray-900/5 sm:mx-0 sm:rounded-lg sm:px-8 sm:pb-14 lg:col-span-2 lg:row-span-2 lg:row-end-2 xl:px-16 xl:pb-20 xl:pt-2">
                        <div class="dark:border-gray-600 border-gray-400 border p-4 rounded">
                            <div v-if="meta_data.data.length ===0" >
                                <div class="text-center">
                                    <svg
                                        class="mx-auto h-12 w-12 text-gray-400"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0112 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5" />
                                    </svg>


                                    <h3 class="mt-2 text-sm font-semibold text-gray-900">No Meta Data?</h3>
                                    <p class="mt-1 text-sm text-gray-500">Add some Meta-Data to add to your assistant questions</p>
                                    <div class="mt-6">
                                        <Link :href="route('meta_data.create')" type="button" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                            <PlusIcon class="-ml-0.5 mr-1.5 h-5 w-5" aria-hidden="true" />
                                            Add Meta Data
                                        </Link>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <div class="flex justify-between items-center mb-4">
                                    <HTwo>Your MetaData Labels</HTwo>
                                    <div><SecondaryButton :href="route('meta_data.create')">add</SecondaryButton></div>
                                </div>

                                <div class="mt-4 flex flex-wrap gap-4 justify-center">
                                    <div v-for="meta_data in meta_data.data" class="bg-indigo-500 text-white min-w-[100px] max-w-[200px] rounded p-2 flex justify-between items-center">
                                        {{ meta_data.label }}
                                        <Link :href="route('meta_data.edit', {
                                            meta_data: meta_data.id
                                        })">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </AppLayout>
</template>

<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import Copy from "@/Components/Copy.vue";
import HTwo from "@/Components/HTwo.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextArea from "@/Components/TextArea.vue";
import {useForm, Link} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";

import {
    PlusIcon,
} from '@heroicons/vue/20/solid'
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButtonLink.vue";

const props = defineProps({
    meta_data: Object
})


</script>

<style scoped>

</style>
