<script setup>
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const isLoading = ref(false);
const progress = ref(0);

import { router } from '@inertiajs/vue3';

router.on('start', () => {
    isLoading.value = true;
    progress.value = 10;
});

router.on('progress', (event) => {
    if (event.detail.progress?.percentage) {
        progress.value = event.detail.progress.percentage;
    }
});

router.on('finish', () => {
    progress.value = 100;
    setTimeout(() => {
        isLoading.value = false;
        progress.value = 0;
    }, 500);
});
</script>

<template>
    <Transition>
        <div
            v-if="isLoading && progress > 0"
            class="fixed top-0 left-0 h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-blue-500 z-[999] transition-all duration-300"
            :style="{ width: progress + '%' }"
        ></div>
    </Transition>
</template>

<style scoped>
.v-enter-active,
.v-leave-active {
    transition: opacity 0.3s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
