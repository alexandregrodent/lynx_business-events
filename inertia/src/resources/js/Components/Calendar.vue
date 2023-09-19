<script setup lang="ts">
import { ref, computed } from 'vue';
import moment from 'moment';

const currentDate = ref(new Date());
const tempStartDate = ref(null as Date | null);
const tempEndDate = ref(null as Date | null);
const hoverDate = ref(null as Date | null);
const monthNames = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];

const emit = defineEmits(['selected']);

const daysInMonth = computed(() => {
    const month = currentDate.value.getMonth();
    const year = currentDate.value.getFullYear();
    return new Date(year, month + 1, 0).getDate();
});

const monthName = computed(() => monthNames[currentDate.value.getMonth()]);

const selectDate = (day: number) => {
    const selectedDate = moment.utc([currentDate.value.getFullYear(), currentDate.value.getMonth(), day]);
    if (!tempStartDate.value || (tempEndDate.value && tempStartDate.value)) {
        tempStartDate.value = selectedDate.toDate();
        tempEndDate.value = null;
        hoverDate.value = null; // Reset hoverDate when a start date is selected
        emit('selected', [selectedDate.toDate()]);
    } else if (tempStartDate.value && !tempEndDate.value && selectedDate.isAfter(tempStartDate.value)) {
        tempEndDate.value = selectedDate.toDate();
        emit('selected', [tempStartDate.value, tempEndDate.value]);
    }
};

const nextMonth = () => {
    currentDate.value = new Date(currentDate.value.setMonth(currentDate.value.getMonth() + 1));
};

const previousMonth = () => {
    currentDate.value = new Date(currentDate.value.setMonth(currentDate.value.getMonth() - 1));
};

const isDateInRange = (day: number): boolean => {
    const checkDate = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth(), day);
    if (tempStartDate.value && !tempEndDate.value && hoverDate.value) {
        return checkDate >= tempStartDate.value && checkDate <= hoverDate.value;
    }
    if (!tempStartDate.value || !tempEndDate.value) return false;
    return checkDate >= tempStartDate.value && checkDate <= tempEndDate.value;
};

const dayIsStartDate = (day: number): boolean => {
    const checkDate = moment.utc([currentDate.value.getFullYear(), currentDate.value.getMonth(), day]);
    return +checkDate.toDate() === +tempStartDate.value;
};

const dayIsEndDate = (day: number): boolean => {
    const checkDate = moment.utc([currentDate.value.getFullYear(), currentDate.value.getMonth(), day]);
    return +checkDate.toDate() === +tempEndDate.value;
};

const handleMouseOver = (day: number) => {
    if (tempStartDate.value && !tempEndDate.value) {
        hoverDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth(), day);
    }
};
</script>

<style scoped>
.selected {
    background-color: #cce7ff;
}
</style>


<template>
    <div class="calendar p-5 border rounded-md shadow-lg">
        <header class="flex justify-between items-center mb-4">
            <button @click="previousMonth" class="px-3 py-1 bg-blue-500 text-white rounded-full">←</button>
            <span>{{ monthName }} {{ currentDate.getFullYear() }}</span>
            <button @click="nextMonth" class="px-3 py-1 bg-blue-500 text-white rounded-full">→</button>
        </header>
        <div class="grid grid-cols-7 gap-2">
            <!-- Days of the week -->
            <div class="font-bold text-gray-600">Lun</div>
            <div class="font-bold text-gray-600">Mar</div>
            <div class="font-bold text-gray-600">Mer</div>
            <div class="font-bold text-gray-600">Jeu</div>
            <div class="font-bold text-gray-600">Ven</div>
            <div class="font-bold text-gray-600">Sam</div>
            <div class="font-bold text-gray-600">Dim</div>

            <!-- Empty cells for starting days -->
            <template
                v-for="emptyDay in (new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).getDay() || 7) - 1"
                :key="emptyDay"
            >
                <div class="border p-2"></div>
            </template>

            <!-- Days of the month -->
            <div
                v-for="day in daysInMonth"
                :key="day"
                @click="selectDate(day)"
                @mouseover="handleMouseOver(day)"
                :class="{
        'selected': isDateInRange(day) || dayIsStartDate(day) || dayIsEndDate(day),
        'flex justify-center items-center border p-2 hover:bg-blue-100 cursor-pointer': true
    }">
                {{ day }}
            </div>
        </div>
    </div>
</template>
