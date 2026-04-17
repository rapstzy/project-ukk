<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const page = usePage();
const user = computed(() => page.props.auth.user || {});
const userHandle = computed(() => user.value?.email?.split('@')[0] || 'reader');
</script>

<template>
    <Head title="Profil" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <div class="inline-flex rounded-full border border-gray-800 bg-black/60 px-4 py-2 text-xs font-semibold uppercase tracking-[0.35em] text-gray-400">
                        Profil Akun
                    </div>
                    <h1 class="mt-5 text-4xl font-black leading-[0.95] tracking-tight sm:text-5xl">
                        Kelola akun Anda.
                    </h1>
                    <p class="mt-4 max-w-2xl text-sm leading-6 text-gray-400 sm:text-base">
                        Atur identitas dan pilihan penghapusan akun dalam satu ruang yang tetap tenang dan fokus.
                    </p>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 lg:min-w-[280px]">
                    <div class="rounded-[1.5rem] border border-gray-800 bg-white/5 p-4">
                        <div class="text-xs uppercase tracking-[0.3em] text-gray-500">Handle</div>
                        <div class="mt-2 truncate text-sm font-semibold text-white">@{{ userHandle }}</div>
                    </div>
                    <div class="rounded-[1.5rem] border border-gray-800 bg-white/5 p-4">
                        <div class="text-xs uppercase tracking-[0.3em] text-gray-500">Peran</div>
                        <div class="mt-2 text-sm font-semibold text-white uppercase">{{ user.role || 'user' }}</div>
                    </div>
                </div>
            </div>
        </template>

        <div class="grid gap-6 xl:grid-cols-[0.85fr_1.15fr]">
            <aside class="space-y-6 xl:sticky xl:top-10 xl:self-start">
                <div class="rounded-[2rem] border border-gray-800 bg-[#0c0c0c] p-6 shadow-2xl shadow-black/20">
                    <div class="flex items-center gap-4">
                        <div class="flex h-16 w-16 items-center justify-center rounded-full border border-gray-700 bg-white text-lg font-black uppercase text-black">
                            {{ user.name?.charAt(0) || 'U' }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="truncate text-xl font-black text-white">{{ user.name }}</div>
                            <div class="truncate text-sm text-gray-500">{{ user.email }}</div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <div class="rounded-[1.5rem] border border-gray-800 bg-white/5 p-4">
                            <div class="text-xs uppercase tracking-[0.3em] text-gray-500">Tipe Akun</div>
                            <div class="mt-2 text-sm font-semibold text-white">{{ user.role === 'admin' ? 'Administrator' : 'Pembaca' }}</div>
                        </div>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-gray-800 bg-[radial-gradient(circle_at_top,_rgba(29,155,240,0.16),_transparent_42%),linear-gradient(160deg,_#050505_0%,_#0d0d0d_100%)] p-6 shadow-2xl shadow-black/20">
                    <div class="text-xs uppercase tracking-[0.35em] text-gray-500">Catatan Ruang Kerja</div>
                    <h2 class="mt-3 text-2xl font-black text-white">Bersih, Cepat, dan Fokus.</h2>
                    <p class="mt-3 text-sm leading-6 text-gray-400">
                        Profil ini mengikuti bahasa visual yang sama dengan dashboard dan katalog buku, supaya seluruh aplikasi terasa menyatu.
                    </p>
                </div>
            </aside>

            <div class="space-y-6">
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                />

                <DeleteUserForm />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
