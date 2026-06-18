<script setup lang="ts">
import { computed, ref } from 'vue';
import { useRouter } from 'vue-router';
import BaseButton from '@/components/BaseButton.vue';
import BaseSpinner from '@/components/BaseSpinner.vue';
import StakeLogo from '@/components/StakeLogo.vue';
import { useAuthStore } from '@/stores/auth';

const auth = useAuthStore();
const router = useRouter();

const checking = ref(false);
const email = computed(() => auth.user?.email ?? '');

async function checkStatus(): Promise<void> {
    checking.value = true;
    try {
        await auth.fetchMe();
        if (auth.user?.approved) {
            router.push({ name: 'dashboard' });
        }
    } finally {
        checking.value = false;
    }
}

async function logout(): Promise<void> {
    await auth.logout();
    router.push({ name: 'login' });
}
</script>

<template>
    <div class="flex min-h-svh items-center justify-center bg-background p-4 sm:p-6">
        <div class="w-full max-w-md">
            <div class="mb-8 flex justify-center">
                <StakeLogo class="h-9 w-auto" />
            </div>

            <div class="rounded-lg bg-card p-8 text-center shadow-xl">
                <div
                    class="mx-auto mb-5 flex size-14 items-center justify-center rounded-full bg-primary/10 text-primary"
                >
                    <svg
                        class="size-7"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                </div>

                <h1 class="text-xl font-bold tracking-tight">
                    Please wait for your account to be approved
                </h1>
                <p class="mt-3 text-sm text-muted">
                    Your email is verified. A manager will review your
                    application and activate the account. Once approved, you'll
                    see your terms and be able to link a payout wallet.
                </p>

                <p v-if="email" class="mt-4 rounded-md bg-surface-high px-3 py-2 text-sm">
                    {{ email }}
                </p>

                <div class="mt-6 flex flex-col gap-3">
                    <BaseButton
                        class="w-full"
                        :disabled="checking"
                        @click="checkStatus"
                    >
                        <BaseSpinner v-if="checking" />
                        Check status
                    </BaseButton>
                    <button
                        type="button"
                        class="text-sm text-muted hover:underline"
                        @click="logout"
                    >
                        Log out
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
