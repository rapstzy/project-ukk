<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, router, usePage, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import Spinner from '@/Components/Spinner.vue';

const props = defineProps({
    users: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const flashSuccess = computed(() => page.props.flash?.success || '');
const authUser = computed(() => page.props.auth.user);
const deletingId = ref(null);

const deleteUser = (user) => {
    if (confirm(`Apakah Anda yakin ingin menghapus pengguna ${user.name}? Semua data peminjaman terkait juga akan terhapus.`)) {
        deletingId.value = user.id;
        router.delete(route('admin.users.destroy', user.id), {
            preserveScroll: true,
            onFinish: () => deletingId.value = null,
        });
    }
};

const roleLabel = (role) => role === 'admin' ? 'Admin' : 'User';
</script>

<template>
    <Head title="Manajemen User" />

    <AppLayout>
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <section class="rounded-[2rem] border border-gray-800 bg-[radial-gradient(circle_at_top_left,_rgba(29,155,240,0.16),_transparent_35%),linear-gradient(160deg,_#040404_0%,_#0a0a0a_55%,_#111111_100%)] p-8 shadow-2xl shadow-black/30 sm:p-10">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <div class="inline-flex rounded-full border border-gray-800 bg-black/60 px-4 py-2 text-xs font-semibold uppercase tracking-[0.35em] text-gray-400">
                            Akses Admin
                        </div>
                        <h1 class="mt-5 text-4xl font-black leading-[0.95] tracking-tight sm:text-6xl">
                            Kelola akun user
                        </h1>
                        <p class="mt-4 max-w-2xl text-sm leading-6 text-gray-400 sm:text-base">
                            Lihat dan kelola seluruh akun pengguna yang terdaftar di sistem perpustakaan.
                        </p>
                    </div>
                </div>
            </section>

            <div v-if="flashSuccess" class="mt-6 rounded-3xl border border-emerald-900/40 bg-emerald-950/30 px-5 py-4 text-sm text-emerald-300">
                {{ flashSuccess }}
            </div>

            <div class="mt-8">
                <section class="rounded-[2rem] border border-gray-800 bg-[#0b0b0b] p-6 shadow-2xl shadow-black/20 sm:p-8">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <div class="text-xs uppercase tracking-[0.35em] text-gray-500">Pengguna</div>
                            <h2 class="mt-2 text-2xl font-black text-white">Daftar akun</h2>
                        </div>
                        <span class="rounded-full border border-gray-800 bg-white/5 px-4 py-2 text-xs font-semibold text-gray-300">
                            {{ users.data?.length || 0 }} item
                        </span>
                    </div>

                    <div class="mt-6 space-y-4">
                        <article
                            v-for="user in users.data"
                            :key="user.id"
                            class="rounded-3xl border border-gray-800 bg-black/60 p-5 transition hover:border-white/20 hover:bg-white/[0.04]"
                        >
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                <div class="min-w-0">
                                    <div class="text-xs uppercase tracking-[0.3em] text-gray-500">Pengguna #{{ user.id }}</div>
                                    <div class="mt-1 truncate text-xl font-bold text-white">{{ user.name }}</div>
                                    <div class="mt-1 text-sm text-gray-400">{{ user.email }}</div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span :class="['inline-flex rounded-full border px-3 py-1 text-xs font-bold uppercase tracking-[0.25em]', user.role === 'admin' ? 'border-violet-900/40 bg-violet-950/50 text-violet-300' : 'border-gray-800 bg-gray-900 text-gray-300']">
                                        {{ roleLabel(user.role) }}
                                    </span>

                                    <button
                                        v-if="user.role !== 'admin' && user.id !== authUser.id"
                                        @click="deleteUser(user)"
                                        :disabled="deletingId === user.id"
                                        class="inline-flex items-center gap-2 rounded-full border border-red-900/40 bg-red-950/20 px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] text-red-400 transition hover:bg-red-900/40 disabled:opacity-50"
                                    >
                                        <Spinner v-if="deletingId === user.id" size="sm" />
                                        {{ deletingId === user.id ? 'Menghapus...' : 'Hapus' }}
                                    </button>
                                </div>
                            </div>
                        </article>
                    </div>

                    <div v-if="users.links" class="mt-6 flex flex-wrap justify-center gap-2 border-t border-gray-800 pt-6">
                        <button
                            v-for="link in users.links"
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
