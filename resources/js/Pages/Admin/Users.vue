<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    users: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const flashSuccess = computed(() => page.props.flash?.success || '');
const showPassword = ref(false);
const showConfirmation = ref(false);

const form = useForm({
    user_id: '',
    password: '',
    password_confirmation: '',
});

const recentPasswordRequests = computed(() => {
    return (page.props.notificationSummary?.recent || []).filter((item) => item.data?.kind === 'password_reset_requested');
});

const selectedUser = computed(() => {
    return props.users.data?.find((user) => String(user.id) === String(form.user_id));
});

const submit = () => {
    if (!form.user_id) {
        return;
    }

    form.put(route('admin.users.password.update', form.user_id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('password', 'password_confirmation');
        },
    });
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
                            Admin access
                        </div>
                        <h1 class="mt-5 text-4xl font-black leading-[0.95] tracking-tight sm:text-6xl">
                            Kelola akun user
                        </h1>
                        <p class="mt-4 max-w-2xl text-sm leading-6 text-gray-400 sm:text-base">
                            Admin bisa mengatur password semua user langsung dari panel ini, tanpa perlu email reset.
                        </p>
                    </div>
                </div>
            </section>

            <div v-if="flashSuccess" class="mt-6 rounded-3xl border border-emerald-900/40 bg-emerald-950/30 px-5 py-4 text-sm text-emerald-300">
                {{ flashSuccess }}
            </div>

            <div class="mt-8 grid gap-6 xl:grid-cols-[1.25fr_0.75fr]">
                <section class="rounded-[2rem] border border-gray-800 bg-[#0b0b0b] p-6 shadow-2xl shadow-black/20 sm:p-8">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <div class="text-xs uppercase tracking-[0.35em] text-gray-500">Users</div>
                            <h2 class="mt-2 text-2xl font-black text-white">Daftar akun</h2>
                        </div>
                        <span class="rounded-full border border-gray-800 bg-white/5 px-4 py-2 text-xs font-semibold text-gray-300">
                            {{ users.data?.length || 0 }} items
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
                                    <div class="text-xs uppercase tracking-[0.3em] text-gray-500">User #{{ user.id }}</div>
                                    <div class="mt-1 truncate text-xl font-bold text-white">{{ user.name }}</div>
                                    <div class="mt-1 text-sm text-gray-400">{{ user.email }}</div>
                                </div>
                                <span :class="['inline-flex rounded-full border px-3 py-1 text-xs font-bold uppercase tracking-[0.25em]', user.role === 'admin' ? 'border-violet-900/40 bg-violet-950/50 text-violet-300' : 'border-gray-800 bg-gray-900 text-gray-300']">
                                    {{ roleLabel(user.role) }}
                                </span>
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

                <aside class="space-y-6">
                    <section class="rounded-[2rem] border border-gray-800 bg-[#0b0b0b] p-6">
                        <div class="text-xs uppercase tracking-[0.35em] text-gray-500">Reset password</div>
                        <form class="mt-4 space-y-4" @submit.prevent="submit">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-300">Pilih user</label>
                                <select
                                    v-model="form.user_id"
                                    class="w-full rounded-2xl border border-gray-800 bg-black px-4 py-3 text-white focus:border-white focus:outline-none"
                                >
                                    <option value="">Pilih akun</option>
                                    <option v-for="user in users.data" :key="user.id" :value="user.id">
                                        {{ user.name }} - {{ user.email }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-300">Password baru</label>
                                <div class="relative">
                                    <input
                                        v-model="form.password"
                                        :type="showPassword ? 'text' : 'password'"
                                        class="w-full rounded-2xl border border-gray-800 bg-black px-4 py-3 pr-20 text-white focus:border-white focus:outline-none"
                                        placeholder="Minimal 8 karakter"
                                    />
                                    <button
                                        type="button"
                                        @click="showPassword = !showPassword"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full border border-gray-800 bg-gray-900 px-3 py-1 text-xs font-semibold text-gray-300 transition hover:border-gray-600 hover:text-white"
                                    >
                                        {{ showPassword ? 'Hide' : 'Show' }}
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-300">Konfirmasi password</label>
                                <div class="relative">
                                    <input
                                        v-model="form.password_confirmation"
                                        :type="showConfirmation ? 'text' : 'password'"
                                        class="w-full rounded-2xl border border-gray-800 bg-black px-4 py-3 pr-20 text-white focus:border-white focus:outline-none"
                                        placeholder="Ulangi password"
                                    />
                                    <button
                                        type="button"
                                        @click="showConfirmation = !showConfirmation"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full border border-gray-800 bg-gray-900 px-3 py-1 text-xs font-semibold text-gray-300 transition hover:border-gray-600 hover:text-white"
                                    >
                                        {{ showConfirmation ? 'Hide' : 'Show' }}
                                    </button>
                                </div>
                            </div>

                            <button
                                type="submit"
                                :disabled="form.processing || !form.user_id"
                                class="w-full rounded-full bg-white px-6 py-3 text-sm font-bold text-black transition hover:bg-gray-200 disabled:cursor-not-allowed disabled:bg-gray-500"
                            >
                                {{ form.processing ? 'Menyimpan...' : 'Reset password' }}
                            </button>
                        </form>
                    </section>

                    <section class="rounded-[2rem] border border-gray-800 bg-[#0b0b0b] p-6">
                        <div class="text-xs uppercase tracking-[0.35em] text-gray-500">Permintaan terbaru</div>
                        <div class="mt-4 space-y-3">
                            <div v-if="recentPasswordRequests.length === 0" class="rounded-2xl border border-dashed border-gray-800 bg-white/5 p-4 text-sm text-gray-500">
                                Belum ada permintaan reset password.
                            </div>
                            <article
                                v-for="item in recentPasswordRequests"
                                :key="item.id"
                                class="rounded-2xl border border-gray-800 bg-white/5 p-4"
                            >
                                <div class="text-sm font-semibold text-white">{{ item.data?.title }}</div>
                                <div class="mt-1 text-sm text-gray-400">{{ item.data?.message }}</div>
                                <div class="mt-2 text-xs uppercase tracking-[0.24em] text-gray-500">
                                    {{ item.data?.user_email }}
                                </div>
                            </article>
                        </div>

                        <div v-if="selectedUser" class="mt-5 rounded-2xl border border-violet-900/30 bg-violet-950/20 p-4 text-sm text-violet-200">
                            Password baru akan diterapkan untuk <span class="font-bold">{{ selectedUser.name }}</span>.
                        </div>
                    </section>
                </aside>
            </div>
        </div>
    </AppLayout>
</template>
