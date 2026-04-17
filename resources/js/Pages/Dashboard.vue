<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    loans: {
        type: Array,
        default: () => []
    },
    stats: {
        type: Object,
        default: () => ({})
    },
    upcomingDue: {
        type: Array,
        default: () => []
    },
    overdueLoans: {
        type: Array,
        default: () => []
    },
    isAdmin: {
        type: Boolean,
        default: false
    }
});

const formatDate = (value) => {
    if (!value) return '-';

    return new Intl.DateTimeFormat('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    }).format(new Date(value));
};

const formatCurrency = (value) => `Rp ${Number(value || 0).toLocaleString('id-ID')}`;

const statusLabel = (status) => {
    const labels = {
        borrowed: 'Menunggu',
        verified: 'Terverifikasi',
        returned: 'Dikembalikan',
        late: 'Terlambat',
        completed: 'Selesai',
        cancelled: 'Dibatalkan',
    };

    return labels[status] || status;
};

const statusClass = (status) => {
    const classes = {
        borrowed: 'bg-sky-950/80 text-sky-300 border-sky-900/50',
        verified: 'bg-violet-950/80 text-violet-300 border-violet-900/50',
        returned: 'bg-emerald-950/80 text-emerald-300 border-emerald-900/50',
        late: 'bg-rose-950/80 text-rose-300 border-rose-900/50',
        completed: 'bg-emerald-950/80 text-emerald-300 border-emerald-900/50',
        cancelled: 'bg-gray-900 text-gray-300 border-gray-800',
    };

    return classes[status] || 'bg-gray-900 text-gray-300 border-gray-800';
};

const loanBooks = (loan) => {
    return (loan?.items || [])
        .map((item) => item?.book?.title)
        .filter(Boolean)
        .join(', ');
};

const loanCount = (loan) => loan?.items?.length || 0;

const statCards = computed(() => {
    if (props.isAdmin) {
        return [
            { label: 'Total Pinjaman', value: props.stats.total || 0, note: 'Semua transaksi pinjam' },
            { label: 'Pinjaman Aktif', value: props.stats.borrowed || 0, note: 'Sedang berjalan' },
            { label: 'Buku', value: props.stats.books || 0, note: 'Koleksi buku aktif' },
            { label: 'Pengguna', value: props.stats.users || 0, note: 'Pengguna terdaftar' },
        ];
    }

    return [
        { label: 'Dipinjam', value: props.stats.borrowed || 0, note: 'Sedang dipinjam' },
        { label: 'Kembali', value: props.stats.returned || 0, note: 'Sudah kembali' },
        { label: 'Terlambat', value: props.stats.late || 0, note: 'Perlu perhatian' },
        { label: 'Jatuh Tempo', value: props.stats.overdue || 0, note: 'Lewat batas waktu' },
    ];
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>
        <div class="min-h-full bg-black text-white">
            <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <section class="rounded-[2rem] border border-gray-800 bg-[radial-gradient(circle_at_top_left,_rgba(29,155,240,0.18),_transparent_32%),linear-gradient(160deg,_#040404_0%,_#0a0a0a_55%,_#111111_100%)] p-8 shadow-2xl shadow-black/30 sm:p-10">
                    <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                        <div class="max-w-3xl">
                            <div class="inline-flex rounded-full border border-gray-800 bg-black/60 px-4 py-2 text-xs font-semibold uppercase tracking-[0.35em] text-gray-400">
                                {{ isAdmin ? 'Tampilan Admin' : 'Tampilan Pembaca' }}
                            </div>
                            <h1 class="mt-5 text-4xl font-black leading-[0.95] tracking-tight sm:text-6xl">
                                {{ isAdmin ? 'Ruang Kendali' : 'Beranda Anda' }}
                            </h1>
                            <p class="mt-4 max-w-2xl text-sm leading-6 text-gray-400 sm:text-base">
                                <template v-if="isAdmin">
                                    Pantau transaksi peminjaman, koleksi buku, dan status sistem dari satu layar yang tegas dan ringkas.
                                </template>
                                <template v-else>
                                    Lihat status peminjamanmu, buku yang mendekati jatuh tempo, dan ringkasan aktivitas dengan tampilan yang fokus.
                                </template>
                            </p>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2 lg:min-w-[360px]">
                            <Link
                                :href="route('books.index')"
                                class="rounded-3xl border border-gray-800 bg-white/5 p-5 transition hover:border-white/20 hover:bg-white/10"
                            >
                                <div class="text-xs uppercase tracking-[0.3em] text-gray-500">Katalog</div>
                                <div class="mt-2 text-lg font-semibold text-white">{{ isAdmin ? 'Kelola Buku' : 'Cari Buku' }}</div>
                                <div class="mt-2 text-sm leading-6 text-gray-400">{{ isAdmin ? 'Kelola semua koleksi.' : 'Cari buku yang siap dipinjam.' }}</div>
                            </Link>

                            <Link
                                :href="isAdmin ? route('loans.admin') : route('loans.myLoans')"
                                class="rounded-3xl border border-gray-800 bg-white/5 p-5 transition hover:border-white/20 hover:bg-white/10"
                            >
                                <div class="text-xs uppercase tracking-[0.3em] text-gray-500">Aktivitas</div>
                                <div class="mt-2 text-lg font-semibold text-white">{{ isAdmin ? 'Panel Pinjam' : 'Pinjaman Saya' }}</div>
                                <div class="mt-2 text-sm leading-6 text-gray-400">{{ isAdmin ? 'Masuk ke panel verifikasi.' : 'Lihat daftar pinjamanmu.' }}</div>
                            </Link>
                        </div>
                    </div>
                </section>

                <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div
                        v-for="card in statCards"
                        :key="card.label"
                        class="rounded-3xl border border-gray-800 bg-[#0c0c0c] p-6 shadow-lg shadow-black/20"
                    >
                        <div class="text-xs uppercase tracking-[0.3em] text-gray-500">{{ card.label }}</div>
                        <div class="mt-3 text-4xl font-black text-white">{{ card.value }}</div>
                        <div class="mt-2 text-sm leading-6 text-gray-400">{{ card.note }}</div>
                    </div>
                </div>

                <div class="mt-8 grid gap-6 xl:grid-cols-[1.25fr_0.75fr]">
                    <section class="rounded-[2rem] border border-gray-800 bg-[#0b0b0b] p-6 shadow-2xl shadow-black/20 sm:p-8">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-xs uppercase tracking-[0.35em] text-gray-500">
                                    {{ isAdmin ? 'Aliran Sistem' : 'Pinjaman Saya' }}
                                </div>
                                <h2 class="mt-2 text-2xl font-black text-white">
                                    {{ isAdmin ? 'Aktivitas Peminjaman Terbaru' : 'Timeline Peminjaman' }}
                                </h2>
                            </div>
                            <span class="rounded-full border border-gray-800 bg-white/5 px-3 py-1 text-xs font-semibold text-gray-300">
                                {{ loans.length }} item
                            </span>
                        </div>

                        <div v-if="!loans || loans.length === 0" class="mt-6 rounded-3xl border border-dashed border-gray-800 bg-black/50 p-8 text-center text-gray-500">
                            {{ isAdmin ? 'Belum ada aktivitas peminjaman.' : 'Kamu belum memiliki peminjaman.' }}
                        </div>

                        <div v-else class="mt-6 space-y-4">
                            <article
                                v-for="loan in loans.slice(0, 6)"
                                :key="loan.id"
                                class="rounded-3xl border border-gray-800 bg-black/60 p-5 transition hover:border-white/20 hover:bg-white/[0.04]"
                            >
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <div class="text-xs uppercase tracking-[0.25em] text-gray-500">
                                            Pinjaman #{{ loan.id }}
                                        </div>
                                        <div class="mt-1 truncate text-lg font-semibold text-white">
                                            {{ isAdmin ? (loan.user?.name || 'Pengguna tidak dikenal') : (loanBooks(loan) || 'Tidak ada buku') }}
                                        </div>
                                        <div v-if="isAdmin" class="mt-1 text-sm text-gray-400">
                                            {{ loan.user?.email || '-' }}
                                        </div>
                                        <div v-else class="mt-1 text-sm text-gray-400">
                                            {{ loanCount(loan) }} buku dipinjam
                                        </div>
                                    </div>

                                    <span :class="['rounded-full border px-3 py-1 text-xs font-bold', statusClass(loan.status)]">
                                        {{ statusLabel(loan.status) }}
                                    </span>
                                </div>

                                <div class="mt-4 flex flex-wrap gap-3 text-sm text-gray-400">
                                    <span>Pinjam: {{ formatDate(loan.loan_date) }}</span>
                                    <span>Kembali: {{ formatDate(loan.due_date) }}</span>
                                    <span v-if="loan.fine_amount > 0">Denda: {{ formatCurrency(loan.fine_amount) }}</span>
                                </div>
                            </article>
                        </div>
                    </section>

                    <aside class="space-y-6">
                        <section v-if="isAdmin" class="rounded-[2rem] border border-gray-800 bg-[#0b0b0b] p-6">
                            <div class="text-xs uppercase tracking-[0.35em] text-gray-500">Pintasan</div>
                            <div class="mt-4 space-y-3">
                                <Link
                                    :href="route('books.index')"
                                    class="flex items-center justify-between rounded-2xl border border-gray-800 bg-white/5 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10"
                                >
                                    <span>Kelola Buku</span>
                                    <span class="text-gray-500">Buka</span>
                                </Link>
                                <Link
                                    :href="route('loans.admin')"
                                    class="flex items-center justify-between rounded-2xl border border-gray-800 bg-white/5 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10"
                                >
                                    <span>Panel Pinjam</span>
                                    <span class="text-gray-500">Buka</span>
                                </Link>
                                <Link
                                    :href="route('books.create')"
                                    class="flex items-center justify-between rounded-2xl border border-gray-800 bg-white/5 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10"
                                >
                                    <span>Tambah Buku</span>
                                    <span class="text-gray-500">Buat</span>
                                </Link>
                            </div>
                        </section>

                        <section v-if="isAdmin" class="rounded-[2rem] border border-gray-800 bg-[#0b0b0b] p-6">
                            <div class="text-xs uppercase tracking-[0.35em] text-gray-500">Status Sistem</div>
                            <div class="mt-5 space-y-4 text-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-400">Total Buku</span>
                                    <span class="font-semibold text-white">{{ stats.books || 0 }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-400">Total Pengguna</span>
                                    <span class="font-semibold text-white">{{ stats.users || 0 }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-400">Pinjaman Aktif</span>
                                    <span class="font-semibold text-white">{{ stats.borrowed || 0 }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-400">Late loans</span>
                                    <span class="font-semibold text-white">{{ stats.late || 0 }}</span>
                                </div>
                            </div>
                        </section>

                        <template v-else>
                            <section class="rounded-[2rem] border border-gray-800 bg-[#0b0b0b] p-6">
                                <div class="text-xs uppercase tracking-[0.35em] text-gray-500">Due soon</div>
                                <div class="mt-4 space-y-3">
                                    <div v-if="upcomingDue.length === 0" class="rounded-2xl border border-gray-800 bg-white/5 p-4 text-sm text-gray-500">
                                        No books are due in the next 3 days.
                                    </div>
                                    <article
                                        v-for="loan in upcomingDue.slice(0, 3)"
                                        :key="loan.id"
                                        class="rounded-2xl border border-gray-800 bg-white/5 p-4"
                                    >
                                        <div class="text-sm font-semibold text-white">{{ loanBooks(loan) || `Loan #${loan.id}` }}</div>
                                        <div class="mt-1 text-sm text-gray-400">Due {{ formatDate(loan.due_date) }}</div>
                                    </article>
                                </div>
                            </section>

                            <section class="rounded-[2rem] border border-gray-800 bg-[#0b0b0b] p-6">
                                <div class="text-xs uppercase tracking-[0.35em] text-gray-500">Overdue</div>
                                <div class="mt-4 space-y-3">
                                    <div v-if="overdueLoans.length === 0" class="rounded-2xl border border-gray-800 bg-white/5 p-4 text-sm text-gray-500">
                                        No overdue loans right now.
                                    </div>
                                    <article
                                        v-for="loan in overdueLoans.slice(0, 3)"
                                        :key="loan.id"
                                        class="rounded-2xl border border-rose-900/40 bg-rose-950/20 p-4"
                                    >
                                        <div class="text-sm font-semibold text-white">{{ loanBooks(loan) || `Loan #${loan.id}` }}</div>
                                        <div class="mt-1 text-sm text-rose-300">Overdue since {{ formatDate(loan.due_date) }}</div>
                                    </article>
                                </div>
                            </section>

                            <section class="rounded-[2rem] border border-gray-800 bg-[#0b0b0b] p-6">
                                <div class="text-xs uppercase tracking-[0.35em] text-gray-500">Borrowing rules</div>
                                <ul class="mt-4 space-y-3 text-sm text-gray-300">
                                    <li class="rounded-2xl border border-gray-800 bg-white/5 px-4 py-3">Maksimal 3 buku sekaligus.</li>
                                    <li class="rounded-2xl border border-gray-800 bg-white/5 px-4 py-3">Periode pinjam 7 hari.</li>
                                    <li class="rounded-2xl border border-gray-800 bg-white/5 px-4 py-3">Perpanjang 1x untuk 7 hari lagi.</li>
                                    <li class="rounded-2xl border border-gray-800 bg-white/5 px-4 py-3">Denda: Rp 2.000/hari.</li>
                                </ul>
                            </section>
                        </template>
                    </aside>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
