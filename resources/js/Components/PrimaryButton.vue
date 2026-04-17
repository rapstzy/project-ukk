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
        class="inline-flex items-center justify-center gap-2 rounded-full bg-white px-5 py-2.5 text-xs font-bold uppercase tracking-[0.22em] text-black transition duration-150 ease-in-out hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-white/20 active:bg-gray-300 disabled:opacity-25"
    >
        <Spinner v-if="loading" size="sm" />
        <slot />
    </button>
</template>
