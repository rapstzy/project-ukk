<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section class="rounded-[2rem] border border-gray-800 bg-[#0c0c0c] p-6 shadow-2xl shadow-black/20 sm:p-8">
        <header class="border-b border-gray-800 pb-6">
            <div>
                <div class="text-xs uppercase tracking-[0.35em] text-gray-500">Profil</div>
                <h2 class="mt-3 text-2xl font-black text-white sm:text-3xl">
                    Informasi Profil
                </h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-gray-400">
                    Perbarui nama dan alamat email Anda. Perubahan di sini langsung dipakai di seluruh workspace.
                </p>
            </div>
        </header>

        <form
            @submit.prevent="form.patch(route('profile.update'))"
            class="mt-6 space-y-6"
        >
            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <InputLabel for="name" value="Nama" />
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-2 block w-full"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel for="email" value="Email" />
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-2 block w-full"
                        v-model="form.email"
                        required
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
            </div>

            <div
                v-show="status === 'verification-link-sent'"
                class="rounded-[1.5rem] border border-emerald-900/40 bg-emerald-950/30 px-4 py-3 text-sm text-emerald-300"
            >
                Link verifikasi baru sudah dikirim ke email Anda.
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <PrimaryButton :loading="form.processing">
                    {{ form.recentlySuccessful ? 'Tersimpan' : 'Simpan Perubahan' }}
                </PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-400"
                    >
                        Perubahan disimpan.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
