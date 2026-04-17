<script setup>
import Spinner from '@/Components/Spinner.vue';

defineProps({
    loading: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <button
        :disabled="loading"
        class="inline-flex items-center justify-center gap-2 rounded-full border border-red-900/40 bg-red-950/40 px-4 py-2 text-xs font-semibold uppercase tracking-[0.22em] text-red-300 transition duration-150 ease-in-out hover:border-red-800/60 hover:bg-red-900/60 hover:text-red-100 focus:outline-none focus:ring-2 focus:ring-red-500/20 active:bg-red-900 disabled:opacity-25"
    >
        <Spinner v-if="loading" size="sm" />
        <slot />
    </button>
</template>
