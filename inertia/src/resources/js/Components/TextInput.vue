<script setup lang="ts">
import { onMounted, ref, computed } from 'vue';

interface PropsType {
    modelValue?: string;
    [key: string]: string | undefined;
}

const props = defineProps<PropsType>();

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

const inputAttributes = computed(() => {
    const { modelValue, ...otherProps } = props;
    return otherProps;
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        v-bind="inputAttributes"
    />
</template>
