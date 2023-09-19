<script setup lang="ts">
import { ref, computed } from 'vue';
import moment from 'moment';
import Calendar from '@/Components/Calendar.vue';
import CalendarIcon from "@/Components/Icons/CalendarIcon.vue";
import CheckIcon from "@/Components/Icons/CheckIcon.vue";

const showCalendar = ref(false);
const selectedStartDate = ref(null as Date | null);
const selectedEndDate = ref(null as Date | null);

const emit = defineEmits(['date-range-selected']);

const formatDateHTML = (date: Date | null) => {
    return date ? moment(date).format('YYYY-MM-DD') : '';
};

const updateDates = (dates: Date[]) => {
    selectedStartDate.value = dates[0];
    selectedEndDate.value = null;

    if (dates.length > 1) {
        selectedEndDate.value = dates[1];
        showCalendar.value = false;
    }
};

const applyDateRange = () => {
    emit('date-range-selected', [formatDateHTML(selectedStartDate.value), formatDateHTML(selectedEndDate.value)]);
};

</script>

<template>
    <div class="flex flex-col gap-4">
        <div class="flex items-center" id="date-rangepicker">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <CalendarIcon />
                </div>
                <input
                    ref="startDateInput"
                    @click="showCalendar = true"
                    :value="formatDateHTML(selectedStartDate)"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    type="date"
                    readonly
                    placeholder="Start Date"
                />
            </div>
            <span class="mx-2 text-gray-500">to</span>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <CalendarIcon />
                </div>
                <input
                    ref="endDateInput"
                    @click="showCalendar = true"
                    :value="formatDateHTML(selectedEndDate)"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    type="date"
                    readonly
                    placeholder="End Date"
                />
            </div>
            <button @click="applyDateRange" class="rounded hover:bg-green-200 m-1">
                <CheckIcon />
            </button>
        </div>
        <Calendar class="absolute bg-white mt-12" v-if="showCalendar" @selected="updateDates" />
    </div>
</template>
