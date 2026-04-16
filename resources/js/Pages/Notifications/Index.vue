<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    notifications: {
        type: Object,
        required: true,
    },
});

const markRead = (id) => {
    router.post(route('notifications.read', id), {}, {
        preserveScroll: true,
    });
};

const markAllRead = () => {
    router.post(route('notifications.readAll'), {}, {
        preserveScroll: true,
    });
};

const formatDate = (value) => {
    if (!value) return '-';

    return new Intl.DateTimeFormat('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    }).format(new Date(value));
};

const iconFor = (kind) => {
    const icons = {
        loan_borrowed: 'bg-sky-500',
        loan_ticket_ready: 'bg-violet-500',
        loan_verified: 'bg-violet-500',
        password_reset_requested: 'bg-amber-500',
        password_changed_by_admin: 'bg-emerald-500',
    };

    return icons[kind] || 'bg-gray-500';
};

const actionLabel = (item) => (item.data?.kind === 'loan_ticket_ready' ? 'Buka tiket' : 'Buka');
</script>

<template>
    <Head title="Notifikasi" />

    <AppLayout>
        <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
            <section class="rounded-[2rem] border border-gray-800 bg-[radial-gradient(circle_at_top_left,_rgba(29,155,240,0.16),_transparent_35%),linear-gradient(160deg,_#040404_0%,_#0a0a0a_55%,_#111111_100%)] p-8 shadow-2xl shadow-black/30 sm:p-10">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <div class="inline-flex rounded-full border border-gray-800 bg-black/60 px-4 py-2 text-xs font-semibold uppercase tracking-[0.35em] text-gray-400">
                            Notification center
                        </div>
                        <h1 class="mt-5 text-4xl font-black leading-[0.95] tracking-tight sm:text-6xl">
                            Semua notifikasi
                        </h1>
                        <p class="mt-4 max-w-2xl text-sm leading-6 text-gray-400 sm:text-base">
                            Lihat pembaruan peminjaman, verifikasi admin, dan permintaan reset password di satu tempat.
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="markAllRead"
                        class="rounded-full border border-gray-800 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:border-white/20 hover:bg-white/10"
                    >
                        Tandai semua dibaca
                    </button>
                </div>
            </section>

            <div class="mt-8 space-y-4">
                <div v-if="!notifications.data || notifications.data.length === 0" class="rounded-[2rem] border border-dashed border-gray-800 bg-[#0b0b0b] p-10 text-center text-gray-500">
                    Belum ada notifikasi.
                </div>

                <article
                    v-for="item in notifications.data"
                    :key="item.id"
                    class="rounded-[2rem] border border-gray-800 bg-[#0b0b0b] p-6 shadow-2xl shadow-black/20 transition hover:border-white/20"
                >
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                        <div class="flex min-w-0 flex-1 gap-4">
                            <span :class="['mt-1 h-3 w-3 shrink-0 rounded-full', iconFor(item.data?.kind)]" />
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-3">
                                    <h2 class="text-xl font-bold text-white">{{ item.data?.title || 'Notifikasi' }}</h2>
                                    <span
                                        v-if="!item.read_at"
                                        class="rounded-full border border-sky-900/40 bg-sky-950/50 px-3 py-1 text-xs font-bold uppercase tracking-[0.24em] text-sky-300"
                                    >
                                        Baru
                                    </span>
                                </div>
                                <p class="mt-2 max-w-3xl text-sm leading-6 text-gray-400">
                                    {{ item.data?.message || '-' }}
                                </p>
                                <div class="mt-3 text-xs uppercase tracking-[0.24em] text-gray-500">
                                    {{ formatDate(item.created_at) }}
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <a
                                v-if="item.data?.action_url"
                                :href="item.data.action_url"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="rounded-full border border-gray-800 bg-white/5 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/10"
                            >
                                {{ actionLabel(item) }}
                            </a>
                            <button
                                v-if="!item.read_at"
                                type="button"
                                @click="markRead(item.id)"
                                class="rounded-full border border-gray-800 bg-black/60 px-4 py-2 text-sm font-semibold text-gray-300 transition hover:border-white/20 hover:bg-white/10"
                            >
                                Tandai dibaca
                            </button>
                        </div>
                    </div>
                </article>
            </div>

            <div v-if="notifications.links" class="mt-6 flex flex-wrap justify-center gap-2 border-t border-gray-800 pt-6">
                <button
                    v-for="link in notifications.links"
                    :key="link.url || link.label"
                    :disabled="!link.url || link.active"
                    :class="[
                        'rounded-full border px-4 py-2 text-sm font-semibold transition',
                        link.active
                            ? 'border-white bg-white text-black'
                            : link.url
                            ? 'border-gray-800 bg-white/5 text-gray-300 hover:bg-white/10 hover:text-white'
                            : 'cursor-not-allowed border-gray-900 bg-black text-gray-700'
                    ]"
                    @click="link.url && router.visit(link.url)"
                    v-html="link.label"
                />
            </div>
        </div>
    </AppLayout>
</template>
