<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Login" />

    <div class="min-h-screen bg-black text-white">
        <div class="grid min-h-screen lg:grid-cols-[1.05fr_0.95fr]">
            <section class="relative overflow-hidden border-b border-gray-900 bg-[radial-gradient(circle_at_top_left,_rgba(29,155,240,0.22),_transparent_35%),linear-gradient(160deg,_#020202_0%,_#0b0b0b_55%,_#111111_100%)] px-6 py-10 lg:border-b-0 lg:border-r lg:px-12 lg:py-14">
                <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:48px_48px] opacity-30"></div>
                <div class="relative mx-auto flex h-full max-w-xl flex-col justify-between">
                    <Link href="/" class="inline-flex items-center gap-3 self-start rounded-full border border-gray-800 bg-black/60 px-4 py-2 backdrop-blur">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full border border-gray-700 bg-white text-black">
                            <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M4 19.5V6.8c0-1 .8-1.8 1.8-1.8H19" />
                                <path d="M6.5 5h11.7c.8 0 1.5.7 1.5 1.5v12c0 .8-.7 1.5-1.5 1.5H6.5c-.8 0-1.5-.7-1.5-1.5v-12C5 5.7 5.7 5 6.5 5Z" />
                                <path d="M8 8h8" />
                                <path d="M8 11.5h8" />
                                <path d="M8 15h5" />
                            </svg>
                        </span>
                        <span class="text-sm font-semibold tracking-[0.2em] text-gray-300">APAY'S BOOKS</span>
                    </Link>

                    <div class="py-16 lg:py-0">
                        <p class="text-sm uppercase tracking-[0.35em] text-sky-400/90">Library platform</p>
                        <h1 class="mt-5 max-w-lg text-5xl font-black leading-[0.95] tracking-tight text-white sm:text-6xl">
                            Sign in and keep moving.
                        </h1>
                        <div class="mt-10 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-3xl border border-gray-800 bg-white/5 p-5">
                                <div class="text-sm font-semibold text-white">Fast access</div>
                                <div class="mt-2 text-sm leading-6 text-gray-400">Login tanpa gangguan visual berlebih.</div>
                            </div>
                            <div class="rounded-3xl border border-gray-800 bg-white/5 p-5">
                                <div class="text-sm font-semibold text-white">Focus mode</div>
                                <div class="mt-2 text-sm leading-6 text-gray-400">Konten utama langsung terlihat.</div>
                            </div>
                        </div>
                    </div>

                    <div class="relative mt-10 flex flex-wrap gap-3 text-xs text-gray-500">
                        <span class="rounded-full border border-gray-800 bg-black/60 px-3 py-1">Secure login</span>
                        <span class="rounded-full border border-gray-800 bg-black/60 px-3 py-1">Dark UI</span>
                        <span class="rounded-full border border-gray-800 bg-black/60 px-3 py-1">Responsive</span>
                    </div>
                </div>
            </section>

            <section class="flex items-center justify-center px-6 py-10 sm:px-10">
                <div class="w-full max-w-md">
                    <div class="rounded-[2rem] border border-gray-800 bg-[#0d0d0d] p-8 shadow-2xl shadow-black/50">
                        <div class="mb-8">
                            <div class="text-sm uppercase tracking-[0.35em] text-gray-500">Welcome back</div>
                            <h2 class="mt-3 text-3xl font-black text-white">Log in</h2>
                            <p class="mt-2 text-sm leading-6 text-gray-400">Masuk ke akunmu untuk lanjut mengelola buku dan peminjaman.</p>
                        </div>

                        <div v-if="status" class="mb-5 rounded-2xl border border-emerald-900/40 bg-emerald-950/30 px-4 py-3 text-sm text-emerald-300">
                            {{ status }}
                        </div>

                        <form @submit.prevent="submit" class="space-y-5">
                            <div>
                                <label for="email" class="mb-2 block text-sm font-semibold text-gray-300">Email</label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    class="w-full rounded-2xl border border-gray-800 bg-black px-4 py-3 text-white placeholder:text-gray-600 focus:border-white focus:outline-none"
                                    placeholder="nama@domain.com"
                                />
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>

                            <div>
                                <label for="password" class="mb-2 block text-sm font-semibold text-gray-300">Password</label>
                                <div class="relative">
                                    <input
                                        id="password"
                                        v-model="form.password"
                                        :type="showPassword ? 'text' : 'password'"
                                        required
                                        autocomplete="current-password"
                                        class="w-full rounded-2xl border border-gray-800 bg-black px-4 py-3 pr-20 text-white placeholder:text-gray-600 focus:border-white focus:outline-none"
                                        placeholder="Masukkan password"
                                    />
                                    <button
                                        type="button"
                                        @click="showPassword = !showPassword"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full border border-gray-800 bg-gray-900 px-3 py-1 text-xs font-semibold text-gray-300 transition hover:border-gray-600 hover:text-white"
                                    >
                                        {{ showPassword ? 'Hide' : 'Show' }}
                                    </button>
                                </div>
                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <label class="flex items-center gap-3 text-sm text-gray-400">
                                    <Checkbox name="remember" v-model:checked="form.remember" />
                                    Remember me
                                </label>

                                <Link
                                    v-if="canResetPassword"
                                    :href="route('password.request')"
                                    class="text-sm font-semibold text-gray-300 transition hover:text-white"
                                >
                                    Forgot password?
                                </Link>
                            </div>

                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="w-full rounded-full bg-white px-6 py-3 text-sm font-bold text-black transition hover:bg-gray-200 disabled:cursor-not-allowed disabled:bg-gray-500"
                            >
                                {{ form.processing ? 'Signing in...' : 'Log in' }}
                            </button>
                        </form>

                        <div class="mt-8 border-t border-gray-800 pt-6 text-center">
                            <p class="text-sm text-gray-400">
                                Belum punya akun?
                                <Link :href="route('register')" class="font-semibold text-white transition hover:text-gray-300">
                                    Daftar di sini
                                </Link>
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>
