<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    title: '',
    author: '',
    isbn: '',
    publisher: '',
    year: new Date().getFullYear(),
    category: 'Umum',
    stock: 1,
    description: '',
    cover: null,
    cover_path: '',
});

const coverPreview = ref(null);

const normalizeCoverPath = (path) => {
    const cleanPath = (path || '').trim().replace(/\\/g, '/');

    if (cleanPath.startsWith('public/storage/')) {
        return `/${cleanPath.replace(/^public\//, '')}`;
    }

    if (cleanPath.startsWith('storage/') || cleanPath.startsWith('images/')) {
        return `/${cleanPath}`;
    }

    if (cleanPath.startsWith('/storage/') || cleanPath.startsWith('/images/')) {
        return cleanPath;
    }

    return cleanPath ? `/${cleanPath.replace(/^\/+/, '')}` : null;
};

const handleCoverChange = (e) => {
    const file = e.target.files[0];
    if (!file) return;

    form.cover = file;
    form.cover_path = '';
    const reader = new FileReader();
    reader.onload = (event) => {
        coverPreview.value = event.target.result;
    };
    reader.readAsDataURL(file);
};

const handleCoverPathInput = () => {
    if (form.cover_path) {
        form.cover = null;
        coverPreview.value = normalizeCoverPath(form.cover_path);
    } else if (!form.cover) {
        coverPreview.value = null;
    }
};

const submitForm = () => {
    form.post(route('books.store'), {
        forceFormData: true,
        onSuccess: () => {
            form.reset();
            coverPreview.value = null;
        },
    });
};

const categories = ['Umum', 'Fiksi', 'Non-Fiksi', 'Biografi', 'Sejarah', 'Seni', 'Teknologi', 'Pendidikan'];
</script>

<template>
    <Head title="Tambah Buku" />

    <AppLayout>
        <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
            <section class="rounded-[2rem] border border-gray-800 bg-[radial-gradient(circle_at_top_left,_rgba(29,155,240,0.18),_transparent_35%),linear-gradient(160deg,_#040404_0%,_#0a0a0a_55%,_#111111_100%)] p-8 shadow-2xl shadow-black/30 sm:p-10">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <div class="inline-flex rounded-full border border-gray-800 bg-black/60 px-4 py-2 text-xs font-semibold uppercase tracking-[0.35em] text-gray-400">
                            Create book
                        </div>
                        <h1 class="mt-5 text-4xl font-black leading-[0.95] tracking-tight sm:text-6xl">
                            Add a new book
                        </h1>
                        <p class="mt-4 max-w-2xl text-sm leading-6 text-gray-400 sm:text-base">
                            Tambahkan buku baru dengan tampilan yang seragam dengan halaman lain: gelap, ringkas, dan fokus.
                        </p>
                    </div>

                    <a
                        :href="route('books.index')"
                        class="rounded-full bg-white px-5 py-3 text-sm font-bold text-black transition hover:bg-gray-200"
                    >
                        Kembali
                    </a>
                </div>
            </section>

            <div class="mt-8 grid gap-6 lg:grid-cols-[0.8fr_1.2fr]">
                <aside class="rounded-[2rem] border border-gray-800 bg-[#0b0b0b] p-6 shadow-2xl shadow-black/20">
                    <div class="text-xs uppercase tracking-[0.35em] text-gray-500">Guidelines</div>
                    <div class="mt-4 space-y-3 text-sm text-gray-300">
                        <div class="rounded-2xl border border-gray-800 bg-white/5 px-4 py-3">Cover opsional, maksimal 2MB.</div>
                        <div class="rounded-2xl border border-gray-800 bg-white/5 px-4 py-3">Stok akan dipakai untuk borrowing flow.</div>
                        <div class="rounded-2xl border border-gray-800 bg-white/5 px-4 py-3">Gunakan judul dan ISBN yang unik.</div>
                    </div>

                    <div class="mt-6 rounded-[1.75rem] border border-gray-800 bg-black p-5">
                        <div class="text-xs uppercase tracking-[0.35em] text-gray-500">Preview</div>
                        <div class="mt-4 aspect-[4/5] overflow-hidden rounded-3xl border border-gray-800 bg-gray-950">
                            <img v-if="coverPreview" :src="coverPreview" class="h-full w-full object-cover" />
                            <div v-else class="flex h-full items-center justify-center text-gray-700">
                                <svg class="h-20 w-20" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </aside>

                <form @submit.prevent="submitForm" class="rounded-[2rem] border border-gray-800 bg-[#0b0b0b] p-6 shadow-2xl shadow-black/20 sm:p-8">
                    <div class="space-y-5">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-300">Cover buku</label>
                            <input
                                type="file"
                                accept="image/*"
                                @change="handleCoverChange"
                                class="w-full rounded-2xl border border-gray-800 bg-black px-4 py-3 text-sm text-gray-300 file:mr-4 file:rounded-full file:border-0 file:bg-white file:px-4 file:py-2 file:text-sm file:font-semibold file:text-black hover:border-gray-700"
                            />
                            <div class="mt-3">
                                <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.25em] text-gray-500">Atau pakai path</label>
                                <input
                                    v-model="form.cover_path"
                                    type="text"
                                    @input="handleCoverPathInput"
                                    placeholder="/storage/covers/foto.jpg atau /images/foto.jpg"
                                    class="w-full rounded-2xl border border-gray-800 bg-black px-4 py-3 text-sm text-white placeholder:text-gray-600 focus:border-white focus:outline-none"
                                />
                                <p class="mt-2 text-xs text-gray-500">Kalau file sudah ada di folder server, isi path-nya di sini. Jangan pakai `public/storage/...`, pakai `/storage/...`.</p>
                            </div>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-300">Judul buku</label>
                                <input v-model="form.title" type="text" class="w-full rounded-2xl border border-gray-800 bg-black px-4 py-3 text-white placeholder:text-gray-600 focus:border-white focus:outline-none" placeholder="Masukkan judul buku" />
                                <p v-if="form.errors.title" class="mt-2 text-sm text-red-400">{{ form.errors.title }}</p>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-300">Penulis</label>
                                <input v-model="form.author" type="text" class="w-full rounded-2xl border border-gray-800 bg-black px-4 py-3 text-white placeholder:text-gray-600 focus:border-white focus:outline-none" placeholder="Masukkan nama penulis" />
                                <p v-if="form.errors.author" class="mt-2 text-sm text-red-400">{{ form.errors.author }}</p>
                            </div>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-300">ISBN</label>
                                <input v-model="form.isbn" type="text" class="w-full rounded-2xl border border-gray-800 bg-black px-4 py-3 text-white placeholder:text-gray-600 focus:border-white focus:outline-none" placeholder="978-..." />
                                <p v-if="form.errors.isbn" class="mt-2 text-sm text-red-400">{{ form.errors.isbn }}</p>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-300">Penerbit</label>
                                <input v-model="form.publisher" type="text" class="w-full rounded-2xl border border-gray-800 bg-black px-4 py-3 text-white placeholder:text-gray-600 focus:border-white focus:outline-none" placeholder="Nama penerbit" />
                                <p v-if="form.errors.publisher" class="mt-2 text-sm text-red-400">{{ form.errors.publisher }}</p>
                            </div>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-300">Tahun terbit</label>
                                <input v-model.number="form.year" type="number" :min="1000" :max="new Date().getFullYear()" class="w-full rounded-2xl border border-gray-800 bg-black px-4 py-3 text-white placeholder:text-gray-600 focus:border-white focus:outline-none" />
                                <p v-if="form.errors.year" class="mt-2 text-sm text-red-400">{{ form.errors.year }}</p>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-300">Kategori</label>
                                <select v-model="form.category" class="w-full rounded-2xl border border-gray-800 bg-black px-4 py-3 text-white focus:border-white focus:outline-none">
                                    <option v-for="cat in categories" :key="cat" :value="cat">
                                        {{ cat }}
                                    </option>
                                </select>
                                <p v-if="form.errors.category" class="mt-2 text-sm text-red-400">{{ form.errors.category }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-300">Stok</label>
                            <input v-model.number="form.stock" type="number" min="0" class="w-full rounded-2xl border border-gray-800 bg-black px-4 py-3 text-white placeholder:text-gray-600 focus:border-white focus:outline-none" />
                            <p v-if="form.errors.stock" class="mt-2 text-sm text-red-400">{{ form.errors.stock }}</p>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-300">Deskripsi</label>
                            <textarea v-model="form.description" rows="5" class="w-full rounded-2xl border border-gray-800 bg-black px-4 py-3 text-white placeholder:text-gray-600 focus:border-white focus:outline-none" placeholder="Tulis deskripsi buku..."></textarea>
                            <p v-if="form.errors.description" class="mt-2 text-sm text-red-400">{{ form.errors.description }}</p>
                        </div>

                        <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                            <button type="submit" :disabled="form.processing" class="flex-1 rounded-full bg-white px-6 py-3 text-sm font-bold text-black transition hover:bg-gray-200 disabled:cursor-not-allowed disabled:bg-gray-500">
                                {{ form.processing ? 'Menyimpan...' : 'Simpan buku' }}
                            </button>
                            <a :href="route('books.index')" class="rounded-full border border-gray-800 px-6 py-3 text-center text-sm font-bold text-gray-300 transition hover:bg-gray-900 hover:text-white">
                                Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
