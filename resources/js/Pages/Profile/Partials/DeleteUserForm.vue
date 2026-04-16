<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="rounded-[2rem] border border-red-900/30 bg-[#0c0c0c] p-6 shadow-2xl shadow-black/20 sm:p-8">
        <header class="border-b border-red-900/20 pb-6">
            <div class="text-xs uppercase tracking-[0.35em] text-red-400/70">Danger zone</div>
            <h2 class="mt-3 text-2xl font-black text-white sm:text-3xl">
                Delete account
            </h2>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-gray-400">
                Penghapusan akun akan menghapus semua data yang terkait secara permanen. Ini tidak bisa dibatalkan.
            </p>
        </header>

        <div class="mt-6">
            <DangerButton @click="confirmUserDeletion">Delete account</DangerButton>
        </div>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="border border-gray-800 bg-[#0b0b0b] p-6 sm:p-8">
                <div class="flex items-start justify-between gap-4 border-b border-gray-800 pb-5">
                    <div>
                        <div class="text-xs uppercase tracking-[0.35em] text-red-400/70">Confirm action</div>
                        <h2 class="mt-3 text-2xl font-black text-white">
                            Are you sure you want to delete your account?
                        </h2>
                    </div>
                </div>

                <p class="mt-5 text-sm leading-6 text-gray-400">
                    Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Password" class="sr-only" />
                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full"
                        placeholder="Password"
                        @keyup.enter="deleteUser"
                    />
                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <SecondaryButton @click="closeModal">
                        Cancel
                    </SecondaryButton>

                    <DangerButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Delete account
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
