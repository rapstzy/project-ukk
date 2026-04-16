<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="block w-full rounded-2xl border border-gray-800 bg-[#0a0a0a] px-4 py-3 text-sm text-white shadow-sm transition placeholder:text-gray-600 focus:border-white focus:outline-none focus:ring-2 focus:ring-white/10"
        v-model="model"
        ref="input"
    />
</template>
