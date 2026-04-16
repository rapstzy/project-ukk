<script setup>
defineProps({
    size: {
        type: String,
        default: 'md'
    }
});

const sizeClasses = {
    sm: 'w-4 h-4',
    md: 'w-8 h-8',
    lg: 'w-12 h-12',
    xl: 'w-16 h-16'
};
</script>

<template>
    <div class="flex items-center justify-center">
        <div :class="[sizeClasses[size], 'animate-spin']">
            <div class="w-full h-full rounded-full border-4 border-gray-700 border-t-blue-500 animate-spin"></div>
        </div>
    </div>
</template>

<style scoped>
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
