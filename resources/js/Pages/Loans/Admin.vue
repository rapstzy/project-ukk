<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps({
    loans: {
        type: Object,
        required: true,
    },
    stats: {
        type: Object,
        default: () => ({}),
    },
});

const cancelLoan = (loanId) => {
    if (confirm('Batalkan peminjaman ini?')) {
        router.post(route('loans.admin.cancel', loanId), {}, {
            preserveScroll: true,
            onSuccess: () => router.reload({ preserveScroll: true }),
        });
    }
};

const verifyLoan = (loanId) => {
    router.post(route('loans.admin.verify', loanId), {}, {
        preserveScroll: true,
        onSuccess: () => router.reload({ preserveScroll: true }),
    });
};

const completeReturn = (loanId) => {
    router.post(route('loans.admin.complete-return', loanId), {}, {
        preserveScroll: true,
        onSuccess: () => router.reload({ preserveScroll: true }),
    });
};

const getStatusColor = (status) => {
    const colors = {
        borrowed: 'border-sky-900/40 bg-sky-950/50 text-sky-300',
        verified: 'border-violet-900/40 bg-violet-950/50 text-violet-300',
        returned: 'border-emerald-900/40 bg-emerald-950/50 text-emerald-300',
        late: 'border-rose-900/40 bg-rose-950/50 text-rose-300',
        completed: 'border-emerald-900/40 bg-emerald-950/50 text-emerald-300',
        cancelled: 'border-gray-800 bg-gray-900 text-gray-300',
    };

    return colors[status] || 'border-gray-800 bg-gray-900 text-gray-300';
};

const getStatusLabel = (status) => {
    const labels = {
        borrowed: 'Borrowed',
        verified: 'Verified',
        returned: 'Returned',
        late: 'Late',
        completed: 'Completed',
        cancelled: 'Cancelled',
    };

    return labels[status] || status;
};

const formatDate = (date) => {
    if (!date) return '-';

    return new Intl.DateTimeFormat('id-ID', {
        year: 'numeric',
        month: 'short',
        day: '2-digit',
    }).format(new Date(date));
};

const formatMoney = (value) => `Rp ${Number(value || 0).toLocaleString('id-ID')}`;

</script>

<template>
    <Head title="Kelola Peminjaman" />

    <AppLayout>
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <section class="rounded-[2rem] border border-gray-800 bg-[radial-gradient(circle_at_top_left,_rgba(29,155,240,0.18),_transparent_35%),linear-gradient(160deg,_#040404_0%,_#0a0a0a_55%,_#111111_100%)] p-8 shadow-2xl shadow-black/30 sm:p-10">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <div class="inline-flex rounded-full border border-gray-800 bg-black/60 px-4 py-2 text-xs font-semibold uppercase tracking-[0.35em] text-gray-400">
                            Admin loans
                        </div>
                        <h1 class="mt-5 text-4xl font-black leading-[0.95] tracking-tight sm:text-6xl">
                            Control room
                        </h1>
                        <p class="mt-4 max-w-2xl text-sm leading-6 text-gray-400 sm:text-base">
                            Monitor semua peminjaman dalam tampilan card-based yang cepat dipindai dan tetap tegas seperti X.
                        </p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2 lg:min-w-[360px]">
                        <a
                            :href="route('books.index')"
                            class="rounded-3xl border border-gray-800 bg-white/5 p-5 transition hover:border-white/20 hover:bg-white/10"
                        >
                            <div class="text-xs uppercase tracking-[0.3em] text-gray-500">Catalog</div>
                            <div class="mt-2 text-lg font-semibold text-white">Manage books</div>
                            <div class="mt-2 text-sm leading-6 text-gray-400">Buka koleksi buku.</div>
                        </a>

                        <a
                            :href="route('dashboard')"
                            class="rounded-3xl border border-gray-800 bg-white/5 p-5 transition hover:border-white/20 hover:bg-white/10"
                        >
                            <div class="text-xs uppercase tracking-[0.3em] text-gray-500">Back</div>
                            <div class="mt-2 text-lg font-semibold text-white">Dashboard</div>
                            <div class="mt-2 text-sm leading-6 text-gray-400">Kembali ke ringkasan.</div>
                        </a>
                    </div>
                </div>
            </section>

            <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-3xl border border-gray-800 bg-[#0b0b0b] p-5">
                    <div class="text-xs uppercase tracking-[0.3em] text-gray-500">Total loans</div>
                    <div class="mt-3 text-3xl font-black text-white">{{ stats.total || 0 }}</div>
                </div>
                <div class="rounded-3xl border border-gray-800 bg-[#0b0b0b] p-5">
                    <div class="text-xs uppercase tracking-[0.3em] text-gray-500">Borrowed</div>
                    <div class="mt-3 text-3xl font-black text-white">{{ stats.borrowed || 0 }}</div>
                </div>
                <div class="rounded-3xl border border-gray-800 bg-[#0b0b0b] p-5">
                    <div class="text-xs uppercase tracking-[0.3em] text-gray-500">Returned</div>
                    <div class="mt-3 text-3xl font-black text-white">{{ stats.returned || 0 }}</div>
                </div>
                <div class="rounded-3xl border border-gray-800 bg-[#0b0b0b] p-5">
                    <div class="text-xs uppercase tracking-[0.3em] text-gray-500">Late</div>
                    <div class="mt-3 text-3xl font-black text-white">{{ stats.late || 0 }}</div>
                </div>
            </div>

            <div class="mt-8 grid gap-6 xl:grid-cols-[1.3fr_0.7fr]">
                <section class="rounded-[2rem] border border-gray-800 bg-[#0b0b0b] p-6 shadow-2xl shadow-black/20 sm:p-8">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <div class="text-xs uppercase tracking-[0.35em] text-gray-500">Feed</div>
                            <h2 class="mt-2 text-2xl font-black text-white">Recent loan activity</h2>
                        </div>
                        <span class="rounded-full border border-gray-800 bg-white/5 px-4 py-2 text-xs font-semibold text-gray-300">
                            {{ loans.data?.length || 0 }} items
                        </span>
                    </div>

                    <div v-if="!loans.data || loans.data.length === 0" class="mt-6 rounded-3xl border border-dashed border-gray-800 bg-black/40 p-8 text-center text-gray-500">
                        Belum ada peminjaman
                    </div>

                    <div v-else class="mt-6 space-y-4">
                        <article
                            v-for="loan in loans.data"
                            :key="loan.id"
                            class="rounded-[1.75rem] border border-gray-800 bg-black/60 p-5 transition hover:border-white/20 hover:bg-white/[0.04]"
                        >
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs uppercase tracking-[0.3em] text-gray-500">Loan #{{ loan.id }}</div>
                                    <div class="mt-1 truncate text-xl font-bold text-white">{{ loan.user?.name || '-' }}</div>
                                    <div class="mt-1 text-sm text-gray-400">{{ loan.user?.email || '-' }}</div>
                                </div>

                                <span :class="['inline-flex rounded-full border px-3 py-1 text-xs font-bold uppercase tracking-[0.25em]', getStatusColor(loan.status)]">
                                    {{ getStatusLabel(loan.status) }}
                                </span>
                            </div>

                            <div class="mt-5 flex flex-wrap gap-2">
                                <div v-for="item in loan.items" :key="item.id" class="rounded-full border border-gray-800 bg-white/5 px-3 py-2 text-xs text-gray-300">
                                    {{ item.book.title }}
                                </div>
                            </div>

                            <div class="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                                <div class="rounded-2xl border border-gray-800 bg-white/5 p-4">
                                    <div class="text-xs uppercase tracking-[0.25em] text-gray-500">Borrowed</div>
                                    <div class="mt-1 text-sm font-semibold text-white">{{ formatDate(loan.loan_date) }}</div>
                                </div>
                                <div class="rounded-2xl border border-gray-800 bg-white/5 p-4">
                                    <div class="text-xs uppercase tracking-[0.25em] text-gray-500">Due</div>
                                    <div class="mt-1 text-sm font-semibold text-white">{{ formatDate(loan.due_date) }}</div>
                                </div>
                                <div class="rounded-2xl border border-gray-800 bg-white/5 p-4">
                                    <div class="text-xs uppercase tracking-[0.25em] text-gray-500">Fine</div>
                                    <div class="mt-1 text-sm font-semibold text-white">{{ formatMoney(loan.fine_amount) }}</div>
                                </div>
                                <div class="rounded-2xl border border-gray-800 bg-white/5 p-4">
                                    <div class="text-xs uppercase tracking-[0.25em] text-gray-500">Items</div>
                                    <div class="mt-1 text-sm font-semibold text-white">{{ loan.items?.length || 0 }}</div>
                                </div>
                            </div>

                            <div class="mt-5 flex flex-wrap gap-2">
                                <button
                                    type="button"
                                    v-if="loan.status === 'borrowed'"
                                    @click.prevent="verifyLoan(loan.id)"
                                    class="rounded-full border border-violet-900/40 bg-violet-950/50 px-4 py-2 text-sm font-semibold text-violet-300 transition hover:bg-violet-900/70"
                                >
                                    Verifikasi
                                </button>
                                <button
                                    type="button"
                                    v-if="loan.status === 'borrowed' || loan.status === 'verified'"
                                    @click.prevent="cancelLoan(loan.id)"
                                    class="rounded-full border border-red-900/40 bg-red-950/50 px-4 py-2 text-sm font-semibold text-red-300 transition hover:bg-red-900/70"
                                >
                                    Batalkan
                                </button>
                                <button
                                    type="button"
                                    v-if="loan.status === 'returned' || loan.status === 'late'"
                                    @click.prevent="completeReturn(loan.id)"
                                    class="rounded-full border border-emerald-900/40 bg-emerald-950/50 px-4 py-2 text-sm font-semibold text-emerald-300 transition hover:bg-emerald-900/70"
                                >
                                    Konfirmasi
                                </button>
                            </div>
                        </article>
                    </div>

                    <div v-if="loans.links" class="mt-6 flex flex-wrap justify-center gap-2 border-t border-gray-800 pt-6">
                            <button
                                type="button"
                                v-for="link in loans.links"
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

                <aside class="space-y-6">
                    <section class="rounded-[2rem] border border-gray-800 bg-[#0b0b0b] p-6">
                        <div class="text-xs uppercase tracking-[0.35em] text-gray-500">Quick actions</div>
                        <div class="mt-4 space-y-3">
                            <a :href="route('books.index')" class="flex items-center justify-between rounded-2xl border border-gray-800 bg-white/5 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                                <span>Manage books</span>
                                <span class="text-gray-500">Open</span>
                            </a>
                            <a :href="route('books.create')" class="flex items-center justify-between rounded-2xl border border-gray-800 bg-white/5 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                                <span>Add book</span>
                                <span class="text-gray-500">New</span>
                            </a>
                        </div>
                    </section>

                    <section class="rounded-[2rem] border border-gray-800 bg-[#0b0b0b] p-6">
                        <div class="text-xs uppercase tracking-[0.35em] text-gray-500">Status guide</div>
                        <div class="mt-4 space-y-3 text-sm">
                            <div class="rounded-2xl border border-sky-900/30 bg-sky-950/30 px-4 py-3 text-sky-300">Borrowed - active request</div>
                            <div class="rounded-2xl border border-violet-900/30 bg-violet-950/30 px-4 py-3 text-violet-300">Verified - approved</div>
                            <div class="rounded-2xl border border-emerald-900/30 bg-emerald-950/30 px-4 py-3 text-emerald-300">Returned - closed</div>
                            <div class="rounded-2xl border border-rose-900/30 bg-rose-950/30 px-4 py-3 text-rose-300">Late - needs attention</div>
                        </div>
                    </section>
                </aside>
            </div>
        </div>
    </AppLayout>
</template>
