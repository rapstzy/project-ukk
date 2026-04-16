<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center justify-center rounded-full border border-gray-800 bg-white/5 px-4 py-2 text-xs font-semibold uppercase tracking-[0.22em] text-gray-300 shadow-sm transition duration-150 ease-in-out hover:border-gray-700 hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-white/10 disabled:opacity-25"
    >
        <slot />
    </button>
</template>
