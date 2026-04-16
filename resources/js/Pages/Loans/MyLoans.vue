<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    loans: {
        type: Object,
        required: true,
    },
});

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
        borrowed: 'Waiting',
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

const sectionTotal = (items = []) => items?.length || 0;

const ticketUrl = (loanId) => `/loans/${loanId}/ticket`;
</script>

<template>
    <Head title="Peminjaman Saya" />

    <AppLayout>
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <section class="rounded-[2rem] border border-gray-800 bg-[radial-gradient(circle_at_top_left,_rgba(29,155,240,0.18),_transparent_35%),linear-gradient(160deg,_#040404_0%,_#0a0a0a_55%,_#111111_100%)] p-8 shadow-2xl shadow-black/30 sm:p-10">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <div class="inline-flex rounded-full border border-gray-800 bg-black/60 px-4 py-2 text-xs font-semibold uppercase tracking-[0.35em] text-gray-400">
                            My loans
                        </div>
                        <h1 class="mt-5 text-4xl font-black leading-[0.95] tracking-tight sm:text-6xl">
                            Borrowing timeline
                        </h1>
                        <p class="mt-4 max-w-2xl text-sm leading-6 text-gray-400 sm:text-base">
                            Pantau seluruh peminjamanmu dalam tampilan dark, ringkas, dan mudah dipindai.
                        </p>
                    </div>

                    <Link
                        :href="route('books.index')"
                        class="rounded-full bg-white px-5 py-3 text-sm font-bold text-black transition hover:bg-gray-200"
                    >
                        Jelajahi buku
                    </Link>
                </div>
            </section>

            <div class="mt-8 grid gap-4 md:grid-cols-4">
                <div class="rounded-3xl border border-gray-800 bg-[#0b0b0b] p-5">
                    <div class="text-xs uppercase tracking-[0.3em] text-gray-500">Pending</div>
                    <div class="mt-3 text-3xl font-black text-white">{{ sectionTotal(loans.pending) }}</div>
                </div>
                <div class="rounded-3xl border border-gray-800 bg-[#0b0b0b] p-5">
                    <div class="text-xs uppercase tracking-[0.3em] text-gray-500">Verified</div>
                    <div class="mt-3 text-3xl font-black text-white">{{ sectionTotal(loans.verified) }}</div>
                </div>
                <div class="rounded-3xl border border-gray-800 bg-[#0b0b0b] p-5">
                    <div class="text-xs uppercase tracking-[0.3em] text-gray-500">Completed</div>
                    <div class="mt-3 text-3xl font-black text-white">{{ sectionTotal(loans.completed) }}</div>
                </div>
                <div class="rounded-3xl border border-gray-800 bg-[#0b0b0b] p-5">
                    <div class="text-xs uppercase tracking-[0.3em] text-gray-500">Cancelled</div>
                    <div class="mt-3 text-3xl font-black text-white">{{ sectionTotal(loans.cancelled) }}</div>
                </div>
            </div>

            <div v-if="!loans.pending?.length && !loans.verified?.length && !loans.completed?.length && !loans.cancelled?.length" class="mt-8 rounded-[2rem] border border-gray-800 bg-[#0b0b0b] p-10 text-center text-gray-400">
                <p class="text-lg font-semibold text-white">Belum ada peminjaman</p>
                <p class="mt-2 text-sm text-gray-500">Mulai dari halaman buku untuk membuat daftar pinjam.</p>
                <Link :href="route('books.index')" class="mt-6 inline-flex rounded-full bg-white px-5 py-3 text-sm font-bold text-black transition hover:bg-gray-200">
                    Jelajahi buku
                </Link>
            </div>

            <div v-else class="mt-8 space-y-8">
                <section v-if="loans.pending?.length > 0" class="rounded-[2rem] border border-gray-800 bg-[#0b0b0b] p-6 sm:p-8">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <div class="text-xs uppercase tracking-[0.35em] text-gray-500">Pending</div>
                            <h2 class="mt-2 text-2xl font-black text-white">Waiting for verification</h2>
                        </div>
                        <span class="rounded-full border border-gray-800 bg-white/5 px-4 py-2 text-xs font-semibold text-gray-300">{{ loans.pending.length }} items</span>
                    </div>

                    <div class="mt-6 space-y-4">
                        <article v-for="loan in loans.pending" :key="loan.id" class="rounded-3xl border border-sky-900/30 bg-black/60 p-5 transition hover:border-sky-500/30 hover:bg-white/[0.04]">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                <div>
                                    <div class="text-xs uppercase tracking-[0.3em] text-gray-500">Loan #{{ loan.id }}</div>
                                    <div class="mt-1 text-xl font-bold text-white">{{ loan.items?.length || 0 }} books</div>
                                    <div class="mt-1 text-sm text-gray-400">Created {{ formatDate(loan.loan_date) }}</div>
                                </div>
                                <span :class="['inline-flex rounded-full border px-3 py-1 text-xs font-bold uppercase tracking-[0.25em]', getStatusColor('borrowed')]">
                                    {{ getStatusLabel('borrowed') }}
                                </span>
                            </div>

                            <div class="mt-5 flex flex-wrap gap-2">
                                <div v-for="item in loan.items" :key="item.id" class="rounded-full border border-gray-800 bg-white/5 px-3 py-2 text-xs text-gray-300">
                                    {{ item.book.title }}
                                </div>
                            </div>

                            <div class="mt-5 grid gap-3 sm:grid-cols-3">
                                <div class="rounded-2xl border border-gray-800 bg-white/5 p-4">
                                    <div class="text-xs uppercase tracking-[0.25em] text-gray-500">Author</div>
                                    <div class="mt-1 text-sm font-semibold text-white">{{ loan.items?.[0]?.book?.author || '-' }}</div>
                                </div>
                                <div class="rounded-2xl border border-gray-800 bg-white/5 p-4">
                                    <div class="text-xs uppercase tracking-[0.25em] text-gray-500">Due date</div>
                                    <div class="mt-1 text-sm font-semibold text-white">{{ formatDate(loan.due_date) }}</div>
                                </div>
                                <div class="rounded-2xl border border-gray-800 bg-white/5 p-4">
                                    <div class="text-xs uppercase tracking-[0.25em] text-gray-500">Items</div>
                                    <div class="mt-1 text-sm font-semibold text-white">{{ loan.items?.length || 0 }}</div>
                                </div>
                            </div>
                        </article>
                    </div>
                </section>

                <section v-if="loans.verified?.length > 0" class="rounded-[2rem] border border-gray-800 bg-[#0b0b0b] p-6 sm:p-8">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <div class="text-xs uppercase tracking-[0.35em] text-gray-500">Verified</div>
                            <h2 class="mt-2 text-2xl font-black text-white">Ready to borrow</h2>
                        </div>
                        <span class="rounded-full border border-gray-800 bg-white/5 px-4 py-2 text-xs font-semibold text-gray-300">{{ loans.verified.length }} items</span>
                    </div>

                    <div class="mt-6 space-y-4">
                        <article v-for="loan in loans.verified" :key="loan.id" class="rounded-3xl border border-violet-900/30 bg-black/60 p-5 transition hover:border-violet-500/30 hover:bg-white/[0.04]">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                <div>
                                    <div class="text-xs uppercase tracking-[0.3em] text-gray-500">Loan #{{ loan.id }}</div>
                                    <div class="mt-1 text-xl font-bold text-white">{{ loan.items?.length || 0 }} books</div>
                                    <div class="mt-1 text-sm text-gray-400">Verified {{ formatDate(loan.updated_at || loan.loan_date) }}</div>
                                </div>
                                <span :class="['inline-flex rounded-full border px-3 py-1 text-xs font-bold uppercase tracking-[0.25em]', getStatusColor('verified')]">
                                    {{ getStatusLabel('verified') }}
                                </span>
                            </div>

                            <div class="mt-5 flex flex-wrap gap-2">
                                <div v-for="item in loan.items" :key="item.id" class="rounded-full border border-gray-800 bg-white/5 px-3 py-2 text-xs text-gray-300">
                                    {{ item.book.title }}
                                </div>
                            </div>

                            <div class="mt-5 grid gap-3 sm:grid-cols-3">
                                <div class="rounded-2xl border border-gray-800 bg-white/5 p-4">
                                    <div class="text-xs uppercase tracking-[0.25em] text-gray-500">Author</div>
                                    <div class="mt-1 text-sm font-semibold text-white">{{ loan.items?.[0]?.book?.author || '-' }}</div>
                                </div>
                                <div class="rounded-2xl border border-gray-800 bg-white/5 p-4">
                                    <div class="text-xs uppercase tracking-[0.25em] text-gray-500">Due date</div>
                                    <div class="mt-1 text-sm font-semibold text-white">{{ formatDate(loan.due_date) }}</div>
                                </div>
                                <div class="rounded-2xl border border-gray-800 bg-white/5 p-4">
                                    <div class="text-xs uppercase tracking-[0.25em] text-gray-500">Items</div>
                                    <div class="mt-1 text-sm font-semibold text-white">{{ loan.items?.length || 0 }}</div>
                                </div>
                            </div>

                            <div class="mt-5 flex flex-wrap gap-2">
                                <a
                                    :href="ticketUrl(loan.id)"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="rounded-full border border-violet-900/40 bg-violet-950/50 px-4 py-2 text-sm font-semibold text-violet-300 transition hover:bg-violet-900/70"
                                >
                                    Lihat tiket
                                </a>
                            </div>
                        </article>
                    </div>
                </section>

                <section v-if="loans.completed?.length > 0" class="rounded-[2rem] border border-gray-800 bg-[#0b0b0b] p-6 sm:p-8">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <div class="text-xs uppercase tracking-[0.35em] text-gray-500">Completed</div>
                            <h2 class="mt-2 text-2xl font-black text-white">Finished loans</h2>
                        </div>
                        <span class="rounded-full border border-gray-800 bg-white/5 px-4 py-2 text-xs font-semibold text-gray-300">{{ loans.completed.length }} items</span>
                    </div>

                    <div class="mt-6 space-y-4">
                        <article v-for="loan in loans.completed" :key="loan.id" class="rounded-3xl border border-emerald-900/30 bg-black/60 p-5 transition hover:border-emerald-500/30 hover:bg-white/[0.04]">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                <div>
                                    <div class="text-xs uppercase tracking-[0.3em] text-gray-500">Loan #{{ loan.id }}</div>
                                    <div class="mt-1 text-xl font-bold text-white">{{ loan.items?.length || 0 }} books</div>
                                    <div class="mt-1 text-sm text-gray-400">Completed {{ formatDate(loan.returned_at) }}</div>
                                </div>
                                <span :class="['inline-flex rounded-full border px-3 py-1 text-xs font-bold uppercase tracking-[0.25em]', getStatusColor('completed')]">
                                    {{ getStatusLabel('completed') }}
                                </span>
                            </div>

                            <div class="mt-5 flex flex-wrap gap-2">
                                <div v-for="item in loan.items" :key="item.id" class="rounded-full border border-gray-800 bg-white/5 px-3 py-2 text-xs text-gray-300">
                                    {{ item.book.title }}
                                </div>
                            </div>
                        </article>
                    </div>
                </section>

                <section v-if="loans.cancelled?.length > 0" class="rounded-[2rem] border border-gray-800 bg-[#0b0b0b] p-6 sm:p-8">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <div class="text-xs uppercase tracking-[0.35em] text-gray-500">Cancelled</div>
                            <h2 class="mt-2 text-2xl font-black text-white">Rejected requests</h2>
                        </div>
                        <span class="rounded-full border border-gray-800 bg-white/5 px-4 py-2 text-xs font-semibold text-gray-300">{{ loans.cancelled.length }} items</span>
                    </div>

                    <div class="mt-6 space-y-4">
                        <article v-for="loan in loans.cancelled" :key="loan.id" class="rounded-3xl border border-red-900/30 bg-black/60 p-5 transition hover:border-red-500/30 hover:bg-white/[0.04]">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                <div>
                                    <div class="text-xs uppercase tracking-[0.3em] text-gray-500">Loan #{{ loan.id }}</div>
                                    <div class="mt-1 text-xl font-bold text-white">{{ loan.items?.length || 0 }} books</div>
                                    <div class="mt-1 text-sm text-gray-400">Created {{ formatDate(loan.loan_date) }}</div>
                                </div>
                                <span :class="['inline-flex rounded-full border px-3 py-1 text-xs font-bold uppercase tracking-[0.25em]', getStatusColor('cancelled')]">
                                    {{ getStatusLabel('cancelled') }}
                                </span>
                            </div>

                            <div class="mt-5 flex flex-wrap gap-2">
                                <div v-for="item in loan.items" :key="item.id" class="rounded-full border border-gray-800 bg-white/5 px-3 py-2 text-xs text-gray-300">
                                    {{ item.book.title }}
                                </div>
                            </div>
                        </article>
                    </div>
                </section>
            </div>
        </div>
    </AppLayout>
</template>
