<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="block text-xs font-semibold uppercase tracking-[0.24em] text-gray-500">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
