<script setup>
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    loan: {
        type: Object,
        required: true,
    },
    ticketNumber: {
        type: String,
        required: true,
    },
    issuedAt: {
        type: String,
        required: true,
    },
});

const formatDate = (value) => {
    if (!value) return '-';

    return new Intl.DateTimeFormat('id-ID', {
        year: 'numeric',
        month: 'long',
        day: '2-digit',
    }).format(new Date(value));
};

const formatDateTime = (value) => {
    if (!value) return '-';

    return new Intl.DateTimeFormat('id-ID', {
        year: 'numeric',
        month: 'short',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
    }).format(new Date(value));
};

const printTicket = () => {
    window.print();
};

const backToLoans = () => {
    const targetUrl = '/loans/my-loans';
    window.location.href = targetUrl;
};
</script>

<template>
    <Head :title="`Tiket ${ticketNumber}`" />

    <div class="ticket-page min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(168,85,247,0.18),_transparent_35%),linear-gradient(160deg,_#040404_0%,_#0a0a0a_55%,_#111111_100%)] px-4 py-8 text-white sm:px-6 lg:px-8 print:bg-white print:px-0 print:py-0">
        <div class="mx-auto max-w-5xl">
            <header class="mb-6 flex flex-col gap-4 rounded-[2rem] border border-gray-800 bg-black/50 p-5 backdrop-blur print:hidden sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="text-xs uppercase tracking-[0.35em] text-gray-500">User ticket</div>
                    <h1 class="mt-2 text-2xl font-black sm:text-3xl">Tiket peminjaman</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-gray-400">
                        Tiket ini milik user yang meminjam buku, dan otomatis aktif setelah diverifikasi admin.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button
                        type="button"
                        @click="printTicket"
                        class="rounded-full bg-white px-5 py-3 text-sm font-bold text-black transition hover:bg-gray-200"
                    >
                        Print
                    </button>
                    <button
                        type="button"
                        @click="backToLoans"
                        class="rounded-full border border-gray-800 bg-white/5 px-5 py-3 text-sm font-bold text-white transition hover:bg-white/10"
                    >
                        Ke Peminjaman Saya
                    </button>
                </div>
            </header>

            <section class="ticket-only rounded-[2rem] border border-gray-800 bg-[radial-gradient(circle_at_top_left,_rgba(168,85,247,0.18),_transparent_35%),linear-gradient(160deg,_#040404_0%,_#0a0a0a_55%,_#111111_100%)] p-8 shadow-2xl shadow-black/30 sm:p-10 print:border-0 print:bg-white print:p-0 print:shadow-none">
                <div class="rounded-[2rem] border border-gray-800 bg-[#0b0b0b] p-6 shadow-2xl shadow-black/20 print:mt-0 print:break-inside-avoid print:border print:border-black print:bg-white print:p-0 print:shadow-none">
                    <div class="flex flex-col gap-5 border-b border-gray-800 pb-5 print:border-black">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                <div class="text-xs uppercase tracking-[0.35em] text-gray-500 print:text-gray-700">Ticket number</div>
                                <div class="mt-2 text-2xl font-black text-white print:text-black sm:text-3xl">{{ ticketNumber }}</div>
                                <div class="mt-2 text-sm text-gray-400 print:text-gray-700">Issued {{ formatDateTime(issuedAt) }}</div>
                            </div>
                            <div class="rounded-[1.5rem] border border-violet-900/40 bg-violet-950/50 px-5 py-4 text-violet-300 print:border-black print:bg-white print:text-black">
                                <div class="text-xs uppercase tracking-[0.35em] opacity-70">Status</div>
                                <div class="mt-1 text-lg font-black uppercase tracking-[0.18em] sm:text-xl">{{ loan.status }}</div>
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                            <div class="rounded-2xl border border-gray-800 bg-white/5 p-3 print:border-black print:bg-white sm:p-4">
                                <div class="text-xs uppercase tracking-[0.25em] text-gray-500 print:text-gray-700">Borrower</div>
                                <div class="mt-1 text-sm font-semibold text-white print:text-black">{{ loan.user?.name || '-' }}</div>
                                <div class="mt-1 text-xs text-gray-400 print:text-gray-700">{{ loan.user?.email || '-' }}</div>
                            </div>
                            <div class="rounded-2xl border border-gray-800 bg-white/5 p-3 print:border-black print:bg-white sm:p-4">
                                <div class="text-xs uppercase tracking-[0.25em] text-gray-500 print:text-gray-700">Loan date</div>
                                <div class="mt-1 text-sm font-semibold text-white print:text-black">{{ formatDate(loan.loan_date) }}</div>
                            </div>
                            <div class="rounded-2xl border border-gray-800 bg-white/5 p-3 print:border-black print:bg-white sm:p-4">
                                <div class="text-xs uppercase tracking-[0.25em] text-gray-500 print:text-gray-700">Due date</div>
                                <div class="mt-1 text-sm font-semibold text-white print:text-black">{{ formatDate(loan.due_date) }}</div>
                            </div>
                            <div class="rounded-2xl border border-gray-800 bg-white/5 p-3 print:border-black print:bg-white sm:p-4">
                                <div class="text-xs uppercase tracking-[0.25em] text-gray-500 print:text-gray-700">Books</div>
                                <div class="mt-1 text-sm font-semibold text-white print:text-black">{{ loan.items?.length || 0 }} items</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-5 lg:grid-cols-[1.2fr_0.8fr]">
                        <div>
                            <div class="text-xs uppercase tracking-[0.35em] text-gray-500 print:text-gray-700">Book list</div>
                            <div class="mt-3 grid gap-2">
                                <article
                                    v-for="item in loan.items"
                                    :key="item.id"
                                    class="rounded-2xl border border-gray-800 bg-black/60 p-3 print:border-black print:bg-white sm:p-4"
                                >
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <div class="text-sm font-bold text-white print:text-black">{{ item.book?.title || '-' }}</div>
                                            <div class="mt-1 text-xs text-gray-400 print:text-gray-700">{{ item.book?.author || '-' }}</div>
                                        </div>
                                        <div class="text-xs uppercase tracking-[0.25em] text-gray-500 print:text-gray-700">#{{ item.book_id }}</div>
                                    </div>
                                </article>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="rounded-[1.75rem] border border-gray-800 bg-white/5 p-4 print:border-black print:bg-white">
                                <div class="text-xs uppercase tracking-[0.35em] text-gray-500 print:text-gray-700">Notes</div>
                                <p class="mt-2 text-sm leading-6 text-gray-300 print:text-black">
                                    Tiket ini berlaku sebagai bukti bahwa peminjaman sudah diverifikasi oleh admin dan siap diproses sesuai tenggat pengembalian.
                                </p>
                            </div>

                            <div class="rounded-[1.75rem] border border-gray-800 bg-white/5 p-4 print:border-black print:bg-white">
                                <div class="text-xs uppercase tracking-[0.35em] text-gray-500 print:text-gray-700">Signature</div>
                                <div class="mt-6 border-t border-dashed border-gray-700 pt-3 text-center text-sm text-gray-400 print:border-black print:text-black">
                                    Admin verification stamp
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<style scoped>
@media print {
    @page {
        size: A4 portrait;
        margin: 10mm;
    }

    :global(body) {
        background: white !important;
        margin: 0 !important;
    }

    :global(body *) {
        visibility: hidden !important;
    }

    :global(.ticket-only),
    :global(.ticket-only *) {
        visibility: visible !important;
    }

    :global(.ticket-only) {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        margin: 0 !important;
    }

    :global(.print\:hidden) {
        display: none !important;
    }

    :global(.print\:border) {
        border-color: #000 !important;
    }

    :global(.print\:border-black) {
        border-color: #000 !important;
    }

    :global(.print\:bg-white) {
        background: #fff !important;
    }

    :global(.print\:text-black) {
        color: #000 !important;
    }

    :global(.print\:text-gray-700) {
        color: #4b5563 !important;
    }

    :global(.print\:shadow-none) {
        box-shadow: none !important;
    }
}
</style>
