<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    book: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    stock: props.book.stock,
});

const submitForm = () => {
    form.put(route('books.stock.update', props.book.id));
};
</script>

<template>
    <Head :title="`Edit Stok - ${book.title}`" />

    <AppLayout>
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="mb-12">
                <h1 class="text-4xl font-bold mb-2">Edit Stok Buku</h1>
                <p class="text-gray-400">Ubah hanya jumlah stok tanpa menyentuh data buku lainnya.</p>
            </div>

            <div class="bg-gray-950 rounded-2xl border border-gray-700 p-8">
                <div class="mb-6 rounded-xl border border-gray-700 bg-gray-900/60 p-5">
                    <div class="text-sm text-gray-400">Buku</div>
                    <div class="mt-1 text-xl font-semibold text-white">{{ book.title }}</div>
                    <div class="text-sm text-gray-500">{{ book.author }}</div>
                </div>

                <form @submit.prevent="submitForm" class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-300 mb-2">Stok Saat Ini</label>
                        <input
                            v-model.number="form.stock"
                            type="number"
                            min="0"
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:border-blue-500 focus:outline-none transition"
                        />
                        <p v-if="form.errors.stock" class="text-red-400 text-sm mt-1">{{ form.errors.stock }}</p>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="flex-1 px-6 py-3 bg-amber-600 hover:bg-amber-700 disabled:bg-gray-600 text-white font-bold rounded-lg transition transform hover:scale-105 active:scale-95"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Stok' }}
                        </button>
                        <a
                            :href="route('books.index')"
                            class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-bold rounded-lg transition text-center"
                        >
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
