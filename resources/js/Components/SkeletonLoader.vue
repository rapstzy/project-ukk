<script setup>
defineProps({
    count: {
        type: Number,
        default: 1
    },
    type: {
        type: String,
        default: 'card' // card, text, avatar
    }
});
</script>

<template>
    <div class="space-y-4">
        <template v-for="i in count" :key="i">
            <!-- Card Skeleton -->
            <div v-if="type === 'card'" class="bg-gray-800 rounded-lg p-4 border border-gray-700">
                <div class="aspect-video bg-gray-700 rounded-lg mb-4 animate-pulse"></div>
                <div class="space-y-3">
                    <div class="h-4 bg-gray-700 rounded w-3/4 animate-pulse"></div>
                    <div class="h-3 bg-gray-700 rounded w-1/2 animate-pulse"></div>
                    <div class="h-8 bg-gray-700 rounded animate-pulse mt-4"></div>
                </div>
            </div>

            <!-- Text Skeleton -->
            <div v-else-if="type === 'text'" class="space-y-2">
                <div class="h-4 bg-gray-700 rounded w-full animate-pulse"></div>
                <div class="h-4 bg-gray-700 rounded w-5/6 animate-pulse"></div>
                <div class="h-4 bg-gray-700 rounded w-4/5 animate-pulse"></div>
            </div>

            <!-- Avatar Skeleton -->
            <div v-else-if="type === 'avatar'" class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gray-700 rounded-full animate-pulse"></div>
                <div class="flex-1 space-y-2">
                    <div class="h-3 bg-gray-700 rounded w-2/3 animate-pulse"></div>
                    <div class="h-2 bg-gray-700 rounded w-1/2 animate-pulse"></div>
                </div>
            </div>
        </template>
    </div>
</template>
