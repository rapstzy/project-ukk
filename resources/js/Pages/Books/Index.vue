<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

const props = defineProps({
    books: {
        type: Array,
        required: true,
    },
    categories: {
        type: Array,
        required: true,
    },
    isAdmin: {
        type: Boolean,
        default: false,
    },
    search: {
        type: String,
        default: '',
    },
    selectedCategory: {
        type: String,
        default: '',
    },
});

const searchInput = ref(props.search);
const categoryFilter = ref(props.selectedCategory);
const selectedBooks = ref([]);
const showDeletedOnly = ref(false);
const expandedBookId = ref(null);

const borrowForm = useForm({
    book_ids: [],
});

const visibleBooks = computed(() => {
    if (!props.isAdmin) {
        return props.books.filter((book) => !book.deleted_at);
    }

    if (showDeletedOnly.value) {
        return props.books.filter((book) => book.deleted_at);
    }

    return props.books;
});

const toggleSelect = (bookId) => {
    if (props.books.find((book) => book.id === bookId)?.deleted_at) return;

    const index = selectedBooks.value.indexOf(bookId);
    if (index === -1) {
        if (selectedBooks.value.length < 3) {
            selectedBooks.value.push(bookId);
        }
    } else {
        selectedBooks.value.splice(index, 1);
    }

    borrowForm.book_ids = selectedBooks.value;
};

const submitBorrow = () => {
    if (selectedBooks.value.length === 0) return;

    borrowForm.post(route('loans.store'), {
        onSuccess: () => {
            selectedBooks.value = [];
            borrowForm.book_ids = [];
        },
    });
};

const toggleDetails = (bookId) => {
    expandedBookId.value = expandedBookId.value === bookId ? null : bookId;
};

const deleteBook = (bookId) => {
    if (confirm('Apakah Anda yakin ingin menghapus buku ini? Anda dapat memulihkannya kemudian.')) {
        router.delete(route('books.destroy', bookId));
    }
};

const restoreBook = (bookId) => {
    if (confirm('Apakah Anda yakin ingin memulihkan buku ini?')) {
        router.post(route('books.restore', bookId));
    }
};

const forceDeleteBook = (bookId) => {
    if (confirm('Peringatan: menghapus permanen tidak dapat diurungkan. Lanjutkan?')) {
        router.delete(route('books.forceDelete', bookId));
    }
};

const handleSearch = () => {
    const params = {};

    if (searchInput.value) {
        params.search = searchInput.value;
    }

    if (categoryFilter.value) {
        params.category = categoryFilter.value;
    }

    router.get(
        route('books.index'),
        params,
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
};

const clearFilters = () => {
    searchInput.value = '';
    categoryFilter.value = '';
    router.get(route('books.index'), {}, { preserveState: true, preserveScroll: true, replace: true });
};

const toggleDeletedFilter = () => {
    showDeletedOnly.value = !showDeletedOnly.value;
};

const isExpanded = (bookId) => expandedBookId.value === bookId;

const coverAccent = (index) => [
    'from-sky-500/20 via-cyan-500/10 to-transparent',
    'from-amber-500/20 via-orange-500/10 to-transparent',
    'from-emerald-500/20 via-lime-500/10 to-transparent',
][index % 3];

const refreshBooks = () => {
    router.reload({
        preserveScroll: true,
        preserveState: true,
    });
};

const handlePageShow = (event) => {
    if (event.persisted) {
        refreshBooks();
    }
};

onMounted(() => {
    window.addEventListener('focus', refreshBooks);
    window.addEventListener('pageshow', handlePageShow);
});

onBeforeUnmount(() => {
    window.removeEventListener('focus', refreshBooks);
    window.removeEventListener('pageshow', handlePageShow);
});
</script>

<template>
    <Head title="Buku" />

    <AppLayout>
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <section class="relative overflow-hidden rounded-[2rem] border border-gray-800 bg-[radial-gradient(circle_at_top_left,_rgba(29,155,240,0.2),_transparent_34%),linear-gradient(160deg,_#040404_0%,_#0a0a0a_55%,_#111111_100%)] p-7 shadow-2xl shadow-black/30 sm:p-8">
                <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:48px_48px] opacity-20"></div>
                <div class="relative flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <div class="inline-flex rounded-full border border-gray-800 bg-black/60 px-4 py-2 text-xs font-semibold uppercase tracking-[0.35em] text-gray-400">
                            {{ isAdmin ? 'Admin catalog' : 'Reader catalog' }}
                        </div>
                        <h1 class="mt-5 text-4xl font-black leading-[0.95] tracking-tight sm:text-6xl">
                            Books that move with the scroll.
                        </h1>
                        <p class="mt-4 max-w-2xl text-sm leading-6 text-gray-400 sm:text-base">
                            <template v-if="isAdmin">
                                Koleksi buku disusun seperti panel parallax bertumpuk. Admin tetap punya akses cepat untuk edit, stok, arsip, dan restore.
                            </template>
                            <template v-else>
                                Jelajahi buku lewat kartu yang saling overlap saat scroll, lalu pilih maksimal tiga judul untuk masuk ke daftar pinjaman.
                            </template>
                        </p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2 lg:min-w-[360px]">
                        <a
                            v-if="isAdmin"
                            :href="route('books.create')"
                            class="rounded-[1.5rem] border border-gray-800 bg-white px-5 py-4 text-black transition hover:bg-gray-200"
                        >
                            <div class="text-xs uppercase tracking-[0.3em] text-gray-600">Create</div>
                            <div class="mt-2 text-lg font-semibold">Tambah buku</div>
                            <div class="mt-2 text-sm leading-6 text-gray-600">Tambah koleksi baru.</div>
                        </a>

                        <a
                            :href="route('dashboard')"
                            class="rounded-[1.5rem] border border-gray-800 bg-white/5 p-5 transition hover:border-white/20 hover:bg-white/10"
                        >
                            <div class="text-xs uppercase tracking-[0.3em] text-gray-500">Back</div>
                            <div class="mt-2 text-lg font-semibold text-white">Dashboard</div>
                            <div class="mt-2 text-sm leading-6 text-gray-400">Kembali ke ringkasan.</div>
                        </a>
                    </div>
                </div>
            </section>

            <section class="mt-8 rounded-[2rem] border border-gray-800 bg-[#0b0b0b] p-5 shadow-2xl shadow-black/20 sm:p-6">
                <div class="grid gap-4 lg:grid-cols-[1.25fr_0.75fr] lg:items-end">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <div class="text-xs uppercase tracking-[0.35em] text-gray-500">Search</div>
                                <h2 class="mt-2 text-2xl font-black text-white">Find a book</h2>
                            </div>
                            <div class="rounded-full border border-gray-800 bg-white/5 px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-gray-300">
                                {{ visibleBooks.length }} books
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-[1fr_auto_auto]">
                            <input
                                v-model="searchInput"
                                type="text"
                                placeholder="Cari judul, pengarang, atau ISBN..."
                                @keyup.enter="handleSearch"
                                class="w-full rounded-2xl border border-gray-800 bg-black px-4 py-3 text-white placeholder:text-gray-600 focus:border-white focus:outline-none"
                            />
                            <button @click="handleSearch" class="rounded-2xl bg-white px-5 py-3 font-bold text-black transition hover:bg-gray-200">
                                Cari
                            </button>
                            <button @click="clearFilters" class="rounded-2xl border border-gray-800 bg-white/5 px-5 py-3 font-bold text-gray-300 transition hover:bg-white/10 hover:text-white">
                                Reset
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="text-xs uppercase tracking-[0.35em] text-gray-500">Filter</div>
                        <div class="grid gap-3">
                            <select
                                v-model="categoryFilter"
                                @change="handleSearch"
                                class="w-full rounded-2xl border border-gray-800 bg-black px-4 py-3 text-white focus:border-white focus:outline-none"
                            >
                                <option value="">Semua Kategori</option>
                                <option v-for="cat in categories" :key="cat" :value="cat">
                                    {{ cat }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            <div v-if="visibleBooks.length === 0" class="mt-8 rounded-[2rem] border border-dashed border-gray-800 bg-black/40 py-16 text-center text-gray-500">
                {{ showDeletedOnly ? 'Tidak ada buku yang dihapus' : 'Belum ada buku yang tersedia' }}
            </div>

            <div v-else class="mt-10 space-y-8 pb-8">
                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                    <article
                        v-for="(book, index) in visibleBooks"
                        :key="book.id"
                        :class="[
                            'relative w-full',
                            selectedBooks.includes(book.id) ? 'ring-1 ring-sky-500/40' : ''
                        ]"
                    >
                        <div
                            :class="[
                            'group relative overflow-hidden rounded-[1.5rem] border bg-[#0a0a0a] shadow-2xl shadow-black/25 transition duration-300',
                                book.deleted_at ? 'border-red-900/20 opacity-75' : 'border-gray-800 hover:border-white/20'
                            ]"
                            @click="!isAdmin && !book.deleted_at && toggleSelect(book.id)"
                        >
                            <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(255,255,255,0.03),transparent_40%)]"></div>

                            <div class="relative">
                                <div class="relative h-52 overflow-hidden border-b border-gray-800">
                                    <img
                                        v-if="book.cover_url || book.cover"
                                        :src="book.cover_url || book.cover"
                                        :alt="book.title"
                                        class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                    />
                                    <div v-else class="absolute inset-0 bg-gradient-to-br from-gray-950 via-gray-900 to-black"></div>
                                    <div :class="['absolute inset-0 bg-gradient-to-t from-black via-black/35 to-transparent', coverAccent(index)]"></div>
                                    <div v-if="book.deleted_at && isAdmin" class="absolute inset-0 z-10 flex items-center justify-center bg-black/70 backdrop-blur-sm">
                                        <div class="rounded-[1.5rem] border border-red-900/40 bg-red-950/50 px-4 py-3 text-center">
                                            <div class="text-xs uppercase tracking-[0.35em] text-red-300">Archived</div>
                                            <div class="mt-1 text-lg font-bold text-white">Book removed</div>
                                        </div>
                                    </div>

                                    <div class="absolute left-0 right-0 top-0 flex items-center justify-between p-4">
                                        <span class="rounded-full border border-white/10 bg-black/60 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.25em] text-gray-200">
                                            {{ isAdmin ? 'Admin' : 'Reader' }}
                                        </span>

                                        <span
                                            v-if="!book.deleted_at"
                                            :class="[
                                                'rounded-full border px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.25em]',
                                                book.stock > 0
                                                    ? 'border-emerald-900/40 bg-emerald-950/60 text-emerald-300'
                                                    : 'border-rose-900/40 bg-rose-950/60 text-rose-300'
                                            ]"
                                        >
                                            {{ book.stock > 0 ? `${book.stock} in stock` : 'Out' }}
                                        </span>
                                    </div>

                                    <div v-if="selectedBooks.includes(book.id)" class="absolute bottom-4 left-4">
                                        <span class="rounded-full border border-sky-500/30 bg-sky-500/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.25em] text-sky-300">
                                            Selected
                                        </span>
                                    </div>
                                </div>

                                <div class="p-4">
                                    <div class="space-y-4">
                                        <div class="space-y-2">
                                            <div class="text-xs uppercase tracking-[0.35em] text-gray-500">{{ book.category }}</div>
                                            <h3 class="text-lg font-black leading-tight text-white">
                                                {{ book.title }}
                                            </h3>
                                            <p class="text-sm text-gray-400">{{ book.author }}</p>
                                        </div>

                                        <div class="flex flex-wrap gap-2 text-xs text-gray-400">
                                            <span class="rounded-full border border-gray-800 bg-black/60 px-3 py-1">ISBN {{ book.isbn }}</span>
                                            <span class="rounded-full border border-gray-800 bg-black/60 px-3 py-1">Stock {{ book.stock }}</span>
                                        </div>

                                        <p class="text-sm leading-6 text-gray-400">
                                            {{ book.description || 'Deskripsi buku belum tersedia.' }}
                                        </p>

                                        <Transition name="card-reveal">
                                            <div v-show="isExpanded(book.id)" class="space-y-3 border-t border-gray-800 pt-4">
                                                <div class="grid gap-3 sm:grid-cols-3">
                                                    <div class="rounded-[1.25rem] border border-gray-800 bg-white/5 p-3">
                                                        <div class="text-xs uppercase tracking-[0.28em] text-gray-500">Stock</div>
                                                        <div class="mt-2 text-sm font-black text-white">{{ book.stock }}</div>
                                                    </div>
                                                    <div class="rounded-[1.25rem] border border-gray-800 bg-white/5 p-3">
                                                        <div class="text-xs uppercase tracking-[0.28em] text-gray-500">State</div>
                                                        <div class="mt-2 text-sm font-black text-white">{{ book.deleted_at ? 'Archived' : 'Active' }}</div>
                                                    </div>
                                                    <div class="rounded-[1.25rem] border border-gray-800 bg-white/5 p-3">
                                                        <div class="text-xs uppercase tracking-[0.28em] text-gray-500">Mode</div>
                                                        <div class="mt-2 text-sm font-black text-white">{{ isAdmin ? 'Admin' : 'Reader' }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </Transition>

                                        <div class="flex items-center justify-between gap-3 border-t border-gray-800 pt-4">
                                            <button
                                                type="button"
                                                @click.stop="toggleDetails(book.id)"
                                                class="rounded-full border border-white/10 bg-white px-4 py-2 text-xs font-bold uppercase tracking-[0.22em] text-black transition hover:bg-gray-200"
                                            >
                                                {{ isExpanded(book.id) ? 'Hide' : 'View' }}
                                            </button>

                                            <div v-if="isAdmin && !book.deleted_at" class="flex gap-2">
                                                <a :href="route('books.edit', book.id)" class="rounded-full border border-gray-800 bg-white/5 px-4 py-2 text-xs font-semibold uppercase tracking-[0.22em] text-gray-300 transition hover:bg-white/10 hover:text-white">
                                                    Edit
                                                </a>
                                                <a :href="route('books.stock.edit', book.id)" class="rounded-full border border-gray-800 bg-white/5 px-4 py-2 text-xs font-semibold uppercase tracking-[0.22em] text-gray-300 transition hover:bg-white/10 hover:text-white">
                                                    Stock
                                                </a>
                                            </div>

                                            <div v-else-if="isAdmin && book.deleted_at" class="flex gap-2">
                                                <button @click.stop="restoreBook(book.id)" class="rounded-full border border-emerald-900/40 bg-emerald-950/40 px-4 py-2 text-xs font-semibold uppercase tracking-[0.22em] text-emerald-300 transition hover:bg-emerald-900/60">
                                                    Restore
                                                </button>
                                                <button @click.stop="forceDeleteBook(book.id)" class="rounded-full border border-red-900/40 bg-red-950/40 px-4 py-2 text-xs font-semibold uppercase tracking-[0.22em] text-red-300 transition hover:bg-red-900/60">
                                                    Delete
                                                </button>
                                            </div>

                                            <div v-else class="flex-1">
                                                <button
                                                    v-if="!book.deleted_at"
                                                    :disabled="book.stock === 0 || borrowForm.processing"
                                                    @click.stop="toggleSelect(book.id)"
                                                    :class="[
                                                        'w-full rounded-full px-4 py-2 text-xs font-semibold uppercase tracking-[0.22em] transition',
                                                        selectedBooks.includes(book.id)
                                                            ? 'border border-sky-500/40 bg-sky-500 text-black'
                                                            : 'border border-white/10 bg-white/5 text-white hover:bg-white/10 disabled:cursor-not-allowed disabled:bg-gray-800 disabled:text-gray-500'
                                                    ]"
                                                >
                                                    {{ selectedBooks.includes(book.id) ? 'Selected' : book.stock > 0 ? 'Add' : 'Out of stock' }}
                                                </button>
                                                <div v-else class="rounded-full border border-gray-800 bg-black/60 px-4 py-2 text-xs text-gray-500">
                                                    Archived
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            </div>

            <Transition v-if="!isAdmin" name="slide-up">
                <div v-if="selectedBooks.length > 0" class="fixed bottom-0 left-0 right-0 z-30 border-t border-gray-800 bg-black/95 px-4 py-4 backdrop-blur">
                    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4">
                        <div>
                            <div class="text-xs uppercase tracking-[0.35em] text-gray-500">Borrow queue</div>
                            <div class="mt-1 text-white">
                                <span class="text-2xl font-black">{{ selectedBooks.length }}</span>
                                <span class="ml-2 text-sm text-gray-400">of 3 books selected</span>
                            </div>
                        </div>
                        <button
                            @click="submitBorrow"
                            :disabled="borrowForm.processing"
                            class="rounded-full bg-white px-6 py-3 text-sm font-bold text-black transition hover:bg-gray-200 disabled:cursor-not-allowed disabled:bg-gray-500"
                        >
                            {{ borrowForm.processing ? 'Processing...' : 'Pinjam buku' }}
                        </button>
                    </div>
                </div>
            </Transition>

            <div v-if="!isAdmin && selectedBooks.length > 0" class="h-28"></div>

        </div>
    </AppLayout>
</template>

<style scoped>
.card-reveal-enter-active,
.card-reveal-leave-active {
    transition: all 0.25s ease;
}

.card-reveal-enter-from,
.card-reveal-leave-to {
    opacity: 0;
    transform: translateY(-6px);
}

.slide-up-enter-active,
.slide-up-leave-active {
    transition: all 0.3s ease;
}

.slide-up-enter-from {
    transform: translateY(100%);
    opacity: 0;
}

.slide-up-leave-to {
    transform: translateY(100%);
    opacity: 0;
}
</style>
