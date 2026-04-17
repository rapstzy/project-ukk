<script setup>
import { computed, ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import NotificationBell from '@/Components/NotificationBell.vue';

const page = usePage();
const user = computed(() => page.props.auth.user || {});
const isAdmin = computed(() => user.value?.role === 'admin');
const userHandle = computed(() => user.value?.email?.split('@')[0] || 'reader');
const showMobileMenu = ref(false);

const menuItems = computed(() => {
    const loanRoute = isAdmin.value ? 'loans.admin' : 'loans.myLoans';

    return [
        { label: 'Beranda', href: 'dashboard', note: 'Ringkasan' },
        { label: 'Buku', href: 'books.index', note: 'Katalog' },
        { label: isAdmin.value ? 'Kelola Pinjaman' : 'Pinjaman Saya', href: loanRoute, note: isAdmin.value ? 'Panel Admin' : 'Peminjaman' },
        { label: 'Notifikasi', href: 'notifications.index', note: 'Kotak Masuk' },
        { label: 'Profil', href: 'profile.edit', note: 'Akun' },
    ];
});

const isActive = (routeName) => route().current(routeName);

const handleLogout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div class="flex min-h-screen bg-black text-white">
        <aside class="hidden w-[300px] shrink-0 flex-col border-r border-gray-900 bg-[#050505] lg:flex">
            <div class="border-b border-gray-900 p-6">
                <div class="flex items-center justify-between gap-3">
                    <Link :href="route('dashboard')" class="flex items-center gap-3">
                        <span class="flex h-11 w-11 items-center justify-center rounded-full border border-gray-800 bg-white text-black">
                            <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M4 19.5V6.8c0-1 .8-1.8 1.8-1.8H19" />
                                <path d="M6.5 5h11.7c.8 0 1.5.7 1.5 1.5v12c0 .8-.7 1.5-1.5 1.5H6.5c-.8 0-1.5-.7-1.5-1.5v-12C5 5.7 5.7 5 6.5 5Z" />
                                <path d="M8 8h8" />
                                <path d="M8 11.5h8" />
                                <path d="M8 15h5" />
                            </svg>
                        </span>
                        <div>
                            <div class="text-sm font-bold uppercase tracking-[0.28em] text-gray-300">Perpustakaan</div>
                            <div class="text-xs text-gray-500">Ruang kerja</div>
                        </div>
                    </Link>

                    <NotificationBell />
                </div>
            </div>

            <div class="border-b border-gray-900 p-4">
                <Link :href="route('profile.edit')" class="block">
                    <div class="rounded-[1.5rem] border border-gray-800 bg-white/5 p-4 transition hover:border-white/20 hover:bg-white/10">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full border border-gray-700 bg-black text-sm font-black uppercase text-white">
                                {{ user.name?.charAt(0) || 'U' }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="truncate text-sm font-semibold text-white">{{ user.name }}</div>
                                <div class="truncate text-xs text-gray-500">@{{ userHandle }}</div>
                            </div>
                            <span class="rounded-full border border-gray-800 bg-black px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.25em] text-gray-400">
                                {{ isAdmin ? 'Admin' : 'Pengguna' }}
                            </span>
                        </div>
                    </div>
                </Link>
            </div>

            <nav class="flex-1 px-4 py-5">
                <div class="space-y-2">
                    <template v-for="item in menuItems" :key="item.href">
                        <Link
                            :href="route(item.href)"
                            :class="[
                                'group flex items-center justify-between rounded-[1.5rem] border px-4 py-4 transition',
                                isActive(item.href)
                                    ? 'border-white/20 bg-white text-black'
                                    : 'border-gray-900 bg-transparent text-gray-400 hover:border-gray-800 hover:bg-white/5 hover:text-white'
                            ]"
                        >
                            <div class="min-w-0">
                                <div class="truncate text-sm font-semibold">{{ item.label }}</div>
                                <div class="mt-1 text-xs uppercase tracking-[0.25em] opacity-60">{{ item.note }}</div>
                            </div>
                            <span
                                :class="[
                                    'h-2.5 w-2.5 rounded-full transition',
                                    isActive(item.href) ? 'bg-black' : 'bg-gray-600 group-hover:bg-white'
                                ]"
                            />
                        </Link>
                    </template>
                </div>
            </nav>

            <div class="border-t border-gray-900 p-4">
                <button
                    @click="handleLogout"
                    class="flex w-full items-center justify-between rounded-[1.5rem] border border-gray-900 bg-white/5 px-4 py-4 text-left transition hover:border-red-900/40 hover:bg-red-950/30"
                >
                    <div>
                        <div class="text-sm font-semibold text-white">Keluar</div>
                        <div class="text-xs uppercase tracking-[0.25em] text-gray-500">Akhiri sesi</div>
                    </div>
                    <span class="rounded-full border border-gray-800 bg-black px-3 py-1 text-xs font-semibold text-gray-400">Keluar</span>
                </button>
            </div>
        </aside>

        <div class="lg:hidden fixed inset-x-0 top-0 z-50 border-b border-gray-900 bg-black/80 px-4 py-3 backdrop-blur">
            <div class="flex items-center justify-between">
                <Link :href="route('dashboard')" class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full border border-gray-800 bg-white text-black">
                        <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 19.5V6.8c0-1 .8-1.8 1.8-1.8H19" />
                            <path d="M6.5 5h11.7c.8 0 1.5.7 1.5 1.5v12c0 .8-.7 1.5-1.5 1.5H6.5c-.8 0-1.5-.7-1.5-1.5v-12C5 5.7 5.7 5 6.5 5Z" />
                            <path d="M8 8h8" />
                            <path d="M8 11.5h8" />
                            <path d="M8 15h5" />
                        </svg>
                    </span>
                    <div>
                        <div class="text-sm font-bold uppercase tracking-[0.24em] text-gray-200">Perpustakaan</div>
                        <div class="text-xs text-gray-500">Ruang kerja</div>
                    </div>
                </Link>

                <div class="flex items-center gap-2">
                    <NotificationBell />
                    <button @click="showMobileMenu = !showMobileMenu" class="rounded-full border border-gray-800 bg-white/5 p-3 text-white transition hover:bg-white/10">
                        <svg v-if="!showMobileMenu" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <Transition name="slide">
            <div v-if="showMobileMenu" class="fixed inset-0 z-40 bg-black/95 px-4 pt-20 backdrop-blur lg:hidden">
                <div class="space-y-4">
                    <Link
                        :href="route('profile.edit')"
                        class="block rounded-[1.5rem] border border-gray-800 bg-white/5 p-4"
                    >
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full border border-gray-700 bg-black text-sm font-black uppercase text-white">
                                {{ user.name?.charAt(0) || 'U' }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="truncate text-sm font-semibold text-white">{{ user.name }}</div>
                                <div class="truncate text-xs text-gray-500">@{{ userHandle }}</div>
                            </div>
                            <span class="rounded-full border border-gray-800 bg-black px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.25em] text-gray-400">
                                {{ isAdmin ? 'Admin' : 'Pengguna' }}
                            </span>
                        </div>
                    </Link>

                    <div class="space-y-2">
                        <template v-for="item in menuItems" :key="item.href">
                            <Link
                                :href="route(item.href)"
                                class="flex items-center justify-between rounded-[1.5rem] border border-gray-900 bg-white/5 px-4 py-4 text-white transition hover:bg-white/10"
                                @click="showMobileMenu = false"
                            >
                                <div>
                                    <div class="text-sm font-semibold">{{ item.label }}</div>
                                    <div class="mt-1 text-xs uppercase tracking-[0.25em] text-gray-500">{{ item.note }}</div>
                                </div>
                                <span class="h-2.5 w-2.5 rounded-full bg-gray-500" />
                            </Link>
                        </template>
                    </div>

                    <button
                        @click="handleLogout"
                        class="flex w-full items-center justify-between rounded-[1.5rem] border border-gray-900 bg-white/5 px-4 py-4 text-left text-white transition hover:border-red-900/40 hover:bg-red-950/30"
                    >
                        <div>
                            <div class="text-sm font-semibold">Keluar</div>
                            <div class="text-xs uppercase tracking-[0.25em] text-gray-500">Akhiri sesi</div>
                        </div>
                        <span class="rounded-full border border-gray-800 bg-black px-3 py-1 text-xs font-semibold text-gray-400">Keluar</span>
                    </button>
                </div>
            </div>
        </Transition>

        <main class="flex-1 overflow-y-auto pt-16 lg:pt-0">
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
                <div v-if="$slots.header" class="mb-6 rounded-[2rem] border border-gray-800 bg-[linear-gradient(160deg,_rgba(255,255,255,0.04),_rgba(255,255,255,0.02))] p-6 shadow-2xl shadow-black/20">
                    <slot name="header" />
                </div>

                <slot />
            </div>
        </main>
    </div>
</template>

<style scoped>
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: transparent;
}

::-webkit-scrollbar-thumb {
    background: #2f2f2f;
    border-radius: 999px;
}

::-webkit-scrollbar-thumb:hover {
    background: #444;
}

.slide-enter-active,
.slide-leave-active {
    transition: opacity 0.25s ease;
}

.slide-enter-from,
.slide-leave-to {
    opacity: 0;
}
</style>
