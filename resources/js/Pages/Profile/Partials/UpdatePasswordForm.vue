<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);
const showCurrentPassword = ref(false);
const showPassword = ref(false);
const showConfirmation = ref(false);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section class="rounded-[2rem] border border-gray-800 bg-[#0c0c0c] p-6 shadow-2xl shadow-black/20 sm:p-8">
        <header class="border-b border-gray-800 pb-6">
            <div class="text-xs uppercase tracking-[0.35em] text-gray-500">Security</div>
            <h2 class="mt-3 text-2xl font-black text-white sm:text-3xl">
                Update password
            </h2>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-gray-400">
                Gunakan password yang panjang dan unik supaya akun tetap aman.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-5">
            <div>
                <InputLabel for="current_password" value="Current Password" />
                <div class="relative mt-2">
                    <TextInput
                        id="current_password"
                        ref="currentPasswordInput"
                        v-model="form.current_password"
                        :type="showCurrentPassword ? 'text' : 'password'"
                        class="pr-20"
                        autocomplete="current-password"
                    />
                    <button
                        type="button"
                        @click="showCurrentPassword = !showCurrentPassword"
                        class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full border border-gray-800 bg-black px-3 py-1 text-xs font-semibold text-gray-300 transition hover:border-gray-700 hover:text-white"
                    >
                        {{ showCurrentPassword ? 'Hide' : 'Show' }}
                    </button>
                </div>
                <InputError :message="form.errors.current_password" class="mt-2" />
            </div>

            <div>
                <InputLabel for="password" value="New Password" />
                <div class="relative mt-2">
                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        :type="showPassword ? 'text' : 'password'"
                        class="pr-20"
                        autocomplete="new-password"
                    />
                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full border border-gray-800 bg-black px-3 py-1 text-xs font-semibold text-gray-300 transition hover:border-gray-700 hover:text-white"
                    >
                        {{ showPassword ? 'Hide' : 'Show' }}
                    </button>
                </div>
                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Confirm Password" />
                <div class="relative mt-2">
                    <TextInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        :type="showConfirmation ? 'text' : 'password'"
                        class="pr-20"
                        autocomplete="new-password"
                    />
                    <button
                        type="button"
                        @click="showConfirmation = !showConfirmation"
                        class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full border border-gray-800 bg-black px-3 py-1 text-xs font-semibold text-gray-300 transition hover:border-gray-700 hover:text-white"
                    >
                        {{ showConfirmation ? 'Hide' : 'Show' }}
                    </button>
                </div>
                <InputError :message="form.errors.password_confirmation" class="mt-2" />
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <PrimaryButton :disabled="form.processing">
                    {{ form.recentlySuccessful ? 'Saved' : 'Save changes' }}
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
                        Password updated.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
