<script setup lang="ts">
import { reactive } from 'vue';
import DateInput from "@/Components/DateInput.vue";

const emit = defineEmits(['date-range-selected']);

const state = reactive({
  startDate: '',
  endDate: '',
});

const updateDate = (name: string, value: string) => {
  state[name] = value;
};

const applyDateRange = () => {
  emit('date-range-selected', [state.startDate, state.endDate]);
}

</script>

<template>
  <div class="flex items-center" id="date-rangepicker">
    <DateInput :name="state.startDate" @update:name="value => updateDate('startDate', value)" />
    <span class="mx-2 text-gray-500">to</span>
    <DateInput :name="state.endDate" @update:name="value => updateDate('endDate', value)" />
    <button @click="applyDateRange" class="rounded hover:bg-green-200 m-1">
      <svg class="scale-75 m-1 w-6 h-6 text-green-500 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
      </svg>
    </button>
  </div>
</template>
