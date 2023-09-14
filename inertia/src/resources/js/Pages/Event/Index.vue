<script setup lang="ts">
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import moment from 'moment'

import SecondaryButton from '../../../../vendor/laravel/jetstream/stubs/inertia/resources/js/Components/SecondaryButton.vue'
import DangerButton from '../../../../vendor/laravel/jetstream/stubs/inertia/resources/js/Components/DangerButton.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import ConfirmationModal from '@/Components/ConfirmationModal.vue'
import DialogModal from '@/Components/DialogModal.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import DateRangePicker from '@/Components/DateRangePicker.vue'
import DateInput from '@/Components/DateInput.vue'
import TextInput from '@/Components/TextInput.vue'

type Event = {
    id: number
    title: string
    start_date: string
    end_date: string
}

const props = defineProps({
    events: {
        type: Array<{
            id: number
            title: string
            start_date: string
            end_date: string
        }>,
        default: [],
    },
    currentFilter: {
        type: String,
        default: 'upcoming',
    },
})

const selectedOption = ref(props.currentFilter)
// Actions IDs
const editingEventId = ref(0)
const deletingEventId = ref(0)
// Modals
const creatingEvent = ref(false)
const editingEvent = ref(false)
const deletingEvent = ref(false)

const form = reactive({
    title: '',
    startDate: '',
    endDate: '',
})

const prepareEditingEvent = (id: number) => {
    editingEvent.value = true
    editingEventId.value = id

    const event = props.events.find((event) => event.id === id)
    form.title = event.title
    form.startDate = formatDateUS(event.start_date)
    form.endDate = formatDateUS(event.end_date)
}

const prepareDeletingEvent = (id: number) => {
    deletingEvent.value = true
    deletingEventId.value = id
}

const fetchUpcomingEvents = () => {
    router.get('/events')
    selectedOption.value = 'upcoming'
}

const fetchAllEvents = () => {
    router.get('/events', { all: true })
    selectedOption.value = 'all'
}

const filterByDateRange = (dates: Array<string>) => {
    router.get(`/events?start_date=${dates[0]}&end_date=${dates[1]}`)
}

const createEvent = () => {
    router.post('/events', {
        title: form.title,
        start_date: form.startDate,
        end_date: form.endDate,
    })
    form.title = ''
    form.startDate = ''
    form.endDate = ''
    creatingEvent.value = false
    selectedOption.value = 'upcoming'
}

const editEvent = (id: number) => {
    router.put(`/events/${id}`, {
        title: form.title,
        start_date: form.startDate,
        end_date: form.endDate,
    })
    editingEventId.value = null
    editingEvent.value = false
    form.title = ''
    form.startDate = ''
    form.endDate = ''
    creatingEvent.value = false
    selectedOption.value = 'upcoming'
}

const deleteEvent = (id: number) => {
    router.delete(`/events/${id}`)
    deletingEvent.value = false
    deletingEventId.value = null
    selectedOption.value = 'upcoming'
}

const formatDateEU = (date) => {
    return moment(date).format('DD/MM/YYYY')
}

const formatDateUS = (date) => {
    return moment(date).format('MM-DD-YYYY')
}
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Events
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div
                        class="relative overflow-x-auto shadow-md sm:rounded-lg p-4"
                    >
                        <div class="flex items-center justify-between p-1">
                            <div class="mb-1">
                                <ul
                                    class="flex flex-row items-center p-2 space-y-1 text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby="dropdownRadioButton"
                                >
                                    <li>
                                        <div
                                            @click="fetchUpcomingEvents"
                                            class="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600"
                                        >
                                            <input
                                                id="filter-radio-upcoming"
                                                type="radio"
                                                value="upcoming"
                                                v-model="selectedOption"
                                                name="filter-radio"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                            />
                                            <label
                                                for="filter-radio-upcoming"
                                                class="w-full ml-2 text-sm font-medium text-gray-900 rounded dark:text-gray-300"
                                                >Upcoming</label
                                            >
                                        </div>
                                    </li>
                                    <li>
                                        <div
                                            @click="fetchAllEvents"
                                            class="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600"
                                        >
                                            <input
                                                id="filter-radio-all"
                                                type="radio"
                                                value="all"
                                                v-model="selectedOption"
                                                name="filter-radio"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                            />
                                            <label
                                                for="filter-radio-all"
                                                class="w-full ml-2 text-sm font-medium text-gray-900 rounded dark:text-gray-300"
                                                >All</label
                                            >
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <date-range-picker
                                @date-range-selected="filterByDateRange"
                            ></date-range-picker>
                        </div>
                        <table
                            class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-4"
                        >
                            <caption
                                class="p-5 text-lg font-semibold text-left text-gray-900 bg-white dark:text-white dark:bg-gray-800"
                            >
                                Events List
                            </caption>
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                            >
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Event title
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Start date
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        End date
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                v-for="event in events as Event[]"
                                :key="event.id"
                            >
                                <tr
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                                >
                                    <th
                                        scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                    >
                                        {{ event.title }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ formatDateEU(event.start_date) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ formatDateEU(event.end_date) }}
                                    </td>
                                    <td class="flex gap-4 px-6 py-4">
                                        <button
                                            @click="
                                                prepareEditingEvent(event.id)
                                            "
                                            class="text-yellow-400"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            @click="
                                                prepareDeletingEvent(event.id)
                                            "
                                            class="text-red-500"
                                        >
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="flex justify-end items-center p-2">
                            <primary-button @click="creatingEvent = true"
                                >Add an event</primary-button
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>

    <!-- Delete Event Confirmation Modal -->
    <ConfirmationModal :show="deletingEvent" @close="deletingEvent = false">
        <template #title> Delete event </template>

        <template #content>
            Are you sure you would like to delete this event?
        </template>

        <template #footer>
            <SecondaryButton @click="deletingEvent = false">
                Cancel
            </SecondaryButton>

            <DangerButton
                class="ml-3"
                @click="deleteEvent(deletingEventId as number)"
            >
                Delete
            </DangerButton>
        </template>
    </ConfirmationModal>

    <!-- Create Event Modal -->
    <DialogModal :show="creatingEvent" @close="creatingEvent = false">
        <template #title> Create Event </template>

        <template #content>
            <div class="flex flex-col gap-1 justify-center items-center mt-4">
                <TextInput
                    v-model="form.title"
                    type="text"
                    class="mt-1 block w-full"
                    required=""
                    placeholder="Title"
                />
                <DateInput
                    v-model="form.startDate"
                    class="w-full"
                    required=""
                />
                <DateInput v-model="form.endDate" class="w-full" required="" />
            </div>
        </template>

        <template #footer>
            <SecondaryButton @click="creatingEvent = false">
                Cancel
            </SecondaryButton>

            <PrimaryButton class="ml-3" @click="createEvent">
                Create
            </PrimaryButton>
        </template>
    </DialogModal>

    <!-- Edit Event Modal -->
    <DialogModal :show="editingEvent" @close="editingEvent = false">
        <template #title> Edit Event </template>

        <template #content>
            <div class="flex flex-col gap-2 justify-center items-center mt-4">
                <TextInput
                    v-model="form.title"
                    type="text"
                    class="block w-full mt-1"
                    required=""
                    placeholder="Title"
                />
                <div class="flex gap-2 items-center w-full">
                    <DateInput
                        v-model="form.startDate"
                        class="w-full"
                        required=""
                    />
                    to
                    <DateInput
                        v-model="form.endDate"
                        class="w-full"
                        required=""
                    />
                </div>
            </div>
        </template>

        <template #footer>
            <SecondaryButton @click="editingEvent = false">
                Cancel
            </SecondaryButton>

            <PrimaryButton
                class="ml-3"
                @click="editEvent(editingEventId as number)"
            >
                Update
            </PrimaryButton>
        </template>
    </DialogModal>
</template>
