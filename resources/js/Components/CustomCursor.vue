<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const cursorX = ref(0);
const cursorY = ref(0);
const ringX = ref(0);
const ringY = ref(0);
const isHoveringButton = ref(false);

let animationFrameId;

onMounted(() => {
    const handleMouseMove = (e) => {
        cursorX.value = e.clientX;
        cursorY.value = e.clientY;

        if (!animationFrameId) {
            animationFrameId = requestAnimationFrame(() => {
                ringX.value += (cursorX.value - ringX.value) * 0.3;
                ringY.value += (cursorY.value - ringY.value) * 0.3;
                animationFrameId = null;
            });
        }
    };

    const handleMouseOver = (e) => {
        if (e.target.tagName === 'BUTTON' || e.target.closest('button')) {
            isHoveringButton.value = true;
        }
    };

    const handleMouseOut = (e) => {
        if (e.target.tagName === 'BUTTON' || e.target.closest('button')) {
            isHoveringButton.value = false;
        }
    };

    document.addEventListener('mousemove', handleMouseMove);
    document.addEventListener('mouseover', handleMouseOver);
    document.addEventListener('mouseout', handleMouseOut);

    return () => {
        document.removeEventListener('mousemove', handleMouseMove);
        document.removeEventListener('mouseover', handleMouseOver);
        document.removeEventListener('mouseout', handleMouseOut);
    };
});

onUnmounted(() => {
    if (animationFrameId) {
        cancelAnimationFrame(animationFrameId);
    }
});
</script>

<template>
    <!-- Thick Ring (Back Layer) -->
    <div
        class="pointer-events-none fixed rounded-full mix-blend-lighten z-[1000] transition-all duration-300"
        style="width: 48px; height: 48px; border: 4px solid; box-shadow: 0 0 20px rgba(59, 130, 246, 0.3)"
        :style="{
            left: ringX - 24 + 'px',
            top: ringY - 24 + 'px',
            transform: isHoveringButton ? 'scale(1.4)' : 'scale(1)',
            borderColor: isHoveringButton ? '#2563eb' : '#3b82f6',
            opacity: isHoveringButton ? 1 : 0.7,
            boxShadow: isHoveringButton
                ? '0 0 30px rgba(37, 99, 235, 0.5)'
                : '0 0 20px rgba(59, 130, 246, 0.3)',
        }"
    ></div>

    <!-- Thick Dot (Center) -->
    <div
        class="pointer-events-none fixed rounded-full mix-blend-lighten z-[1001] transition-all duration-150"
        style="width: 10px; height: 10px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); box-shadow: 0 0 15px rgba(59, 130, 246, 0.6)"
        :style="{
            left: cursorX - 5 + 'px',
            top: cursorY - 5 + 'px',
        }"
    ></div>
</template>
