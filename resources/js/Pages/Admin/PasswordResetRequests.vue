<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import Spinner from '@/Components/Spinner.vue';

const props = defineProps({
    requests: {
        type: Object,
        required: true,
    },
});

const loadingId = ref(null);

const approve = (id) => {
    loadingId.value = id;
    router.post(route('admin.password-resets.approve', id), {}, {
        preserveScroll: true,
        onFinish: () => loadingId.value = null,
    });
};

const reject = (id) => {
    if (confirm('Tolak permintaan reset password ini?')) {
        loadingId.value = id;
        router.post(route('admin.password-resets.reject', id), {}, {
            preserveScroll: true,
            onFinish: () => loadingId.value = null,
        });
    }
};

const getStatusColor = (status) => {
    const colors = {
        pending: 'border-sky-900/40 bg-sky-950/50 text-sky-300',
        approved: 'border-emerald-900/40 bg-emerald-950/50 text-emerald-300',
        rejected: 'border-rose-900/40 bg-rose-950/50 text-rose-300',
        completed: 'border-violet-900/40 bg-violet-950/50 text-violet-300',
    };
    return colors[status] || 'border-gray-800 bg-gray-900 text-gray-300';
};

const getStatusLabel = (status) => {
    const labels = {
        pending: 'Menunggu',
        approved: 'Disetujui',
        rejected: 'Ditolak',
        completed: 'Selesai',
    };
    return labels[status] || status;
};

const formatDate = (date) => {
    if (!date) return '-';
    return new Intl.DateTimeFormat('id-ID', {
        year: 'numeric',
        month: 'short',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
    }).format(new Date(date));
};
</script>

<template>
    <Head title="Permintaan Reset Password" />

    <AppLayout>
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <section class="rounded-[2rem] border border-gray-800 bg-[radial-gradient(circle_at_top_left,_rgba(139,92,246,0.16),_transparent_35%),linear-gradient(160deg,_#040404_0%,_#0a0a0a_55%,_#111111_100%)] p-8 shadow-2xl shadow-black/30 sm:p-10">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <div class="inline-flex rounded-full border border-gray-800 bg-black/60 px-4 py-2 text-xs font-semibold uppercase tracking-[0.35em] text-gray-400">
                            Pusat Keamanan
                        </div>
                        <h1 class="mt-5 text-4xl font-black leading-[0.95] tracking-tight sm:text-6xl">
                            Reset password
                        </h1>
                        <p class="mt-4 max-w-2xl text-sm leading-6 text-gray-400 sm:text-base">
                            Verifikasi dan setujui permintaan pengaturan ulang kata sandi dari pengguna secara manual untuk keamanan ekstra.
                        </p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-1 lg:min-w-[200px]">
                        <Link
                            :href="route('admin.users.index')"
                            class="rounded-3xl border border-gray-800 bg-white/5 p-5 transition hover:border-white/20 hover:bg-white/10"
                        >
                            <div class="text-xs uppercase tracking-[0.3em] text-gray-500">Kembali</div>
                            <div class="mt-2 text-lg font-semibold text-white">Kelola User</div>
                            <div class="mt-2 text-sm leading-6 text-gray-400">Kembali ke daftar akun.</div>
                        </Link>
                    </div>
                </div>
            </section>

            <div class="mt-8">
                <section class="rounded-[2rem] border border-gray-800 bg-[#0b0b0b] p-6 shadow-2xl shadow-black/20 sm:p-8">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <div class="text-xs uppercase tracking-[0.35em] text-gray-500">Permintaan</div>
                            <h2 class="mt-2 text-2xl font-black text-white">Daftar permintaan</h2>
                        </div>
                        <span class="rounded-full border border-gray-800 bg-white/5 px-4 py-2 text-xs font-semibold text-gray-300">
                            {{ requests.total || 0 }} total
                        </span>
                    </div>

                    <div v-if="!requests.data || requests.data.length === 0" class="mt-6 rounded-3xl border border-dashed border-gray-800 bg-black/40 p-8 text-center text-gray-500">
                        Belum ada permintaan reset password.
                    </div>

                    <div v-else class="mt-6 space-y-4">
                        <article
                            v-for="req in requests.data"
                            :key="req.id"
                            class="rounded-[1.75rem] border border-gray-800 bg-black/60 p-5 transition hover:border-white/20 hover:bg-white/[0.04]"
                        >
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs uppercase tracking-[0.3em] text-gray-500">Permintaan #{{ req.id }}</div>
                                    <div class="mt-1 truncate text-xl font-bold text-white">{{ req.user?.name || '-' }}</div>
                                    <div class="mt-1 text-sm text-gray-400">{{ req.user?.email || '-' }}</div>
                                    <div class="mt-2 text-xs text-gray-500">Diminta pada: {{ formatDate(req.created_at) }}</div>
                                </div>

                                <div class="flex flex-col items-end gap-3">
                                    <span :class="['inline-flex rounded-full border px-3 py-1 text-xs font-bold uppercase tracking-[0.25em]', getStatusColor(req.status)]">
                                        {{ getStatusLabel(req.status) }}
                                    </span>
                                    
                                    <div v-if="req.status === 'pending'" class="flex gap-2">
                                        <button
                                            @click="approve(req.id)"
                                            :disabled="loadingId === req.id"
                                            class="inline-flex items-center gap-2 rounded-full border border-emerald-900/40 bg-emerald-950/50 px-4 py-2 text-sm font-semibold text-emerald-300 transition hover:bg-emerald-900/70 disabled:opacity-50"
                                        >
                                            <Spinner v-if="loadingId === req.id" size="sm" />
                                            Setujui
                                        </button>
                                        <button
                                            @click="reject(req.id)"
                                            :disabled="loadingId === req.id"
                                            class="inline-flex items-center gap-2 rounded-full border border-rose-900/40 bg-rose-950/50 px-4 py-2 text-sm font-semibold text-rose-300 transition hover:bg-rose-900/70 disabled:opacity-50"
                                        >
                                            <Spinner v-if="loadingId === req.id" size="sm" />
                                            Tolak
                                        </button>
                                    </div>

                                    <div v-if="req.status === 'approved'" class="text-xs text-emerald-400/70">
                                        Berlaku hingga: {{ formatDate(req.expires_at) }}
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>

                    <div v-if="requests.links" class="mt-6 flex flex-wrap justify-center gap-2 border-t border-gray-800 pt-6">
                        <button
                            v-for="link in requests.links"
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
                </section>
            </div>
        </div>
    </AppLayout>
</template>