<script setup>
import { computed, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const open = ref(false);
const page = usePage();

const summary = computed(() => page.props.notificationSummary || {});
const unreadCount = computed(() => summary.value.unreadCount || 0);
const recentNotifications = computed(() => summary.value.recent || []);

const formatDate = (value) => {
    if (!value) {
        return 'Baru saja';
    }

    return new Intl.DateTimeFormat('id-ID', {
        day: '2-digit',
        month: 'short',
        hour: '2-digit',
        minute: '2-digit',
    }).format(new Date(value));
};

const actionLabel = (item) => {
    if (item.data?.kind === 'loan_ticket_ready') return 'Buka tiket';
    if (item.data?.kind === 'password_reset_approved') return 'Atur password';
    return 'Buka';
};
</script>

<template>
    <div class="relative">
        <button
            type="button"
            @click="open = !open"
            class="relative inline-flex h-11 w-11 items-center justify-center rounded-full border border-gray-800 bg-white/5 text-white transition hover:border-white/20 hover:bg-white/10"
            aria-label="Notifikasi"
        >
            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M15 17H9" />
                <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9" />
                <path d="M10 21a2 2 0 0 0 4 0" />
            </svg>

            <span
                v-if="unreadCount"
                class="absolute -right-1 -top-1 inline-flex min-h-5 min-w-5 items-center justify-center rounded-full border border-black bg-rose-500 px-1 text-[10px] font-black text-white"
            >
                {{ unreadCount > 9 ? '9+' : unreadCount }}
            </span>
        </button>

        <Transition name="fade">
            <div
                v-if="open"
                class="absolute right-0 top-full z-50 mt-3 w-[min(340px,calc(100vw-1rem))] overflow-hidden rounded-[1.5rem] border border-gray-800 bg-[#0b0b0b] shadow-2xl shadow-black/40 lg:right-auto lg:left-full lg:top-[calc(50%+22px)] lg:ml-3 lg:mt-0 lg:max-h-[calc(100vh-2rem)] lg:-translate-y-1/2 lg:w-[340px]"
            >
                <div class="flex items-center justify-between border-b border-gray-800 px-4 py-3">
                    <div>
                        <div class="text-sm font-semibold text-white">Notifikasi</div>
                        <div class="text-xs uppercase tracking-[0.25em] text-gray-500">Kotak masuk terbaru</div>
                    </div>
                    <Link
                        :href="route('notifications.index')"
                        class="rounded-full border border-gray-800 bg-white/5 px-3 py-1 text-xs font-semibold text-gray-300 transition hover:bg-white/10"
                        @click="open = false"
                    >
                        Lihat semua
                    </Link>
                </div>

                <div class="max-h-80 space-y-2 overflow-y-auto p-3 lg:max-h-[calc(100vh-6rem)]">
                    <div v-if="recentNotifications.length === 0" class="rounded-2xl border border-dashed border-gray-800 bg-black/50 px-4 py-5 text-sm text-gray-500">
                        Belum ada notifikasi.
                    </div>

                    <Link
                        v-for="item in recentNotifications"
                        :key="item.id"
                        :href="item.data?.action_url || route('notifications.index')"
                        class="block rounded-2xl border border-gray-800 bg-white/5 px-4 py-3 transition hover:border-white/20 hover:bg-white/10"
                        @click="open = false"
                    >
                        <div class="flex items-start gap-3">
                            <span :class="['mt-2 h-2.5 w-2.5 shrink-0 rounded-full', item.read_at ? 'bg-gray-600' : 'bg-sky-400']" />
                            <div class="min-w-0 flex-1">
                                <div class="text-sm font-semibold text-white">
                                    {{ item.data?.title || 'Notifikasi' }}
                                </div>
                                <div class="mt-1 text-xs leading-5 text-gray-400">
                                    {{ item.data?.message || 'Ada pembaruan baru.' }}
                                </div>
                                <div class="mt-2 text-[11px] uppercase tracking-[0.24em] text-gray-500">
                                    {{ formatDate(item.created_at) }}
                                </div>
                                <div class="mt-2 text-[11px] uppercase tracking-[0.24em] text-violet-300">
                                    {{ actionLabel(item) }}
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.18s ease, transform 0.18s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-6px);
}
</style>
