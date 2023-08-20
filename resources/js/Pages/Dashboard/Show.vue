<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex justify-between">
                Dashboard
                <SecondaryButtonLink
                    class="z-50"
                    :href="route('messages.create')">new message</SecondaryButtonLink>
            </h2>
        </template>
        <main>

        <div class="mx-auto max-w-7xl px-4 py8 sm:px-6 lg:px-8 rounded-t">
            <div class="mx-auto grid max-w-2xl grid-cols-1 grid-rows-1 items-start gap-x-8 gap-y-8 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                <!-- Schedule -->
                <div class="lg:col-start-3 lg:row-end-1">
                    <Calendar/>

                </div>

                <!-- Messages -->
                <div class="-mx-4 px-4 py-8 shadow-sm ring-1 ring-gray-900/5 sm:mx-0 sm:rounded-lg sm:px-8 sm:pb-14 lg:col-span-2 lg:row-span-2 lg:row-end-2 xl:px-16 xl:pb-20 xl:pt-16">
                    <h2 class="text-base font-semibold leading-6 text-gray-900 dark:text-gray-200">Messages</h2>

                    <div v-if="messages.length ===0" class="border border-gray-500 mt-2 rounded p-4">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                            </svg>

                            <h3 class="mt-2 text-sm font-semibold text-gray-900">No Messages</h3>
                            <p class="mt-1 text-sm text-gray-500">Start a new topic/thread with the assistant </p>
                            <div class="mt-6">
                                <Link :href="route('messages.create')" type="button" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                    <PlusIcon class="-ml-0.5 mr-1.5 h-5 w-5" aria-hidden="true" />
                                    New Message Thread
                                </Link>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4" v-else>
                        <div v-for="message in messages.data" :key="message.id">
                            <Card class="mb-2" :message="message" truncate="true"></Card>
                        </div>
                    </div>
                </div>

                <div class="lg:col-start-3">
                    <Tags :tags="tags.data"></Tags>
                    <Picker :selectables="tags.data" :selected="filters.tags" @selectedEmit="tagSelected"></Picker>
                </div>
            </div>
        </div>
    </main>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Card from "@/Pages/Messages/Components/Card.vue"
import Tags from "@/Pages/Tags/Components/Tags.vue"
import { router } from "@inertiajs/vue3";
import Picker from "@/Components/Picker.vue";
const props = defineProps({
    messages: Object,
    tags: Object,
    filters: Object
})
import { Link } from "@inertiajs/vue3";

import { ref } from 'vue'

import {
    Bars3Icon,
    CalendarDaysIcon,
    CreditCardIcon,
    EllipsisVerticalIcon,
    FaceFrownIcon,
    FaceSmileIcon,
    PlusIcon,
    FireIcon,
    HandThumbUpIcon,
    HeartIcon,
    PaperClipIcon,
    UserCircleIcon,
    XMarkIcon as XMarkIconMini,
} from '@heroicons/vue/20/solid'
import { BellIcon, XMarkIcon as XMarkIconOutline } from '@heroicons/vue/24/outline'
import { CheckCircleIcon } from '@heroicons/vue/24/solid'
import Calendar from "./Components/Calendar.vue";
import Activities from "./Components/Activities.vue";
import SecondaryButtonLink from "@/Components/SecondaryButtonLink.vue";

const tagSelected = (items) => {
    const tags = items.map(item => item.id);
    router.get(route("dashboard"), {
        tags: tags
    }, {
        preserveScroll: true
    })
}
</script>

<style scoped>

</style>
