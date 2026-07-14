<script setup lang="ts">
import { computed, ref } from 'vue';
import { useRouter } from 'vue-router';
import BaseButton from '@/components/BaseButton.vue';
import BaseSpinner from '@/components/BaseSpinner.vue';
import BrandLogo from '@/components/BrandLogo.vue';
import { useI18n } from '@/i18n';
import { useAuthStore } from '@/stores/auth';

const auth = useAuthStore();
const router = useRouter();
const { t } = useI18n();

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
                <BrandLogo class="h-9 w-auto" />
            </div>

            <div
                class="rounded-lg border border-[var(--card-border)] bg-card p-8 text-center shadow-xl"
            >
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
                    {{ t('pending.title') }}
                </h1>
                <p class="mt-3 text-sm text-muted">
                    {{ t('pending.body') }}
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
                        {{ t('pending.check') }}
                    </BaseButton>
                    <button
                        type="button"
                        class="text-sm text-muted hover:underline"
                        @click="logout"
                    >
                        {{ t('pending.logout') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
