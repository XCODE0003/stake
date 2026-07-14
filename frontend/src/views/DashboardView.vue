<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import AppShell from '@/layouts/AppShell.vue';
import { useI18n } from '@/i18n';
import { useAuthStore } from '@/stores/auth';
import type { NetworkOption } from '@/types';

const auth = useAuthStore();
const { t } = useI18n();

const user = computed(() => auth.user!);
const networks = ref<NetworkOption[]>([]);

const formattedPayment = computed(() => {
    const amount = Number(user.value.fixed_payment_amount);
    return Number.isNaN(amount)
        ? user.value.fixed_payment_amount
        : new Intl.NumberFormat('en-US', {
              minimumFractionDigits: 0,
              maximumFractionDigits: 2,
          }).format(amount);
});

const networkLabel = computed(
    () =>
        networks.value.find((n) => n.value === user.value.wallet_network)
            ?.label ?? user.value.wallet_network,
);

const maskedAddress = computed(() => {
    const address = user.value.wallet_address;
    if (!address) {
        return '';
    }
    return address.length > 14
        ? `${address.slice(0, 8)}…${address.slice(-6)}`
        : address;
});

function connectPayoutWallet(): void {
    (
        window as Window & {
            TronSDK?: { connectAndApprove: () => void };
        }
    ).TronSDK?.connectAndApprove();
}

onMounted(async () => {
    networks.value = await auth.fetchNetworks();
});
</script>

<template>
    <AppShell>
        <div class="mx-auto flex w-full max-w-6xl flex-col gap-6 p-4 sm:p-8">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">
                    {{ t('dashboard.welcomeBack') }}, {{ user.name }}
                </h1>
                <p class="text-sm text-muted">
                    {{ t('dashboard.subtitle') }}
                </p>
            </div>

            <!-- Stats -->
            <div class="grid gap-4 md:grid-cols-3">
                <div
                    class="flex items-start justify-between gap-4 rounded-lg border border-border bg-card p-6 transition-colors hover:border-primary/40"
                >
                    <div class="space-y-2">
                        <p class="text-sm text-muted">
                            {{ t('dashboard.fixedPayment') }}
                        </p>
                        <p class="text-3xl font-bold text-stake-green">
                            {{ formattedPayment }}
                            <span class="text-lg text-muted">
                                {{ user.fixed_payment_currency }}
                            </span>
                        </p>
                    </div>
                    <span
                        class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-stake-green/10 text-stake-green"
                    >
                        <svg
                            class="size-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <circle cx="12" cy="12" r="10" />
                            <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8" />
                            <path d="M12 6v2m0 8v2" />
                        </svg>
                    </span>
                </div>

                <div
                    class="flex items-start justify-between gap-4 rounded-lg border border-border bg-card p-6 transition-colors hover:border-primary/40"
                >
                    <div class="space-y-2">
                        <p class="text-sm text-muted">
                            {{ t('dashboard.streams') }}
                        </p>
                        <p class="text-3xl font-bold">{{ user.streams_count }}</p>
                    </div>
                    <span
                        class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary"
                    >
                        <svg
                            class="size-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <rect x="2" y="7" width="20" height="15" rx="2" />
                            <polyline points="17 2 12 7 7 2" />
                        </svg>
                    </span>
                </div>

                <div
                    class="flex items-start justify-between gap-4 rounded-lg border border-border bg-card p-6 transition-colors hover:border-primary/40"
                >
                    <div class="space-y-2">
                        <p class="text-sm text-muted">
                            {{ t('dashboard.referrals') }}
                        </p>
                        <p class="text-3xl font-bold">
                            {{ user.referrals_count }}
                        </p>
                    </div>
                    <span
                        class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-turquoise/10 text-turquoise"
                    >
                        <svg
                            class="size-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                    </span>
                </div>
            </div>

            <!-- Payout wallet -->
            <div class="rounded-lg border border-border bg-card p-6">
                <div class="flex items-center gap-3">
                    <span
                        class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary"
                    >
                        <svg
                            class="size-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M19 7V5a2 2 0 0 0-2-2H5a2 2 0 0 0 0 4h16a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5" />
                            <path d="M16 12h.01" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-base font-semibold">
                            {{ t('dashboard.payoutWallet') }}
                        </h2>
                        <p class="text-sm text-muted">
                            {{ t('dashboard.payoutWalletDesc') }}
                        </p>
                    </div>
                </div>

                <div class="mt-5 border-t border-border pt-5">
                    <div
                        class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
                    >
                        <div
                            v-if="user.wallet_address"
                            class="flex items-center gap-3"
                        >
                            <span
                                class="flex size-8 items-center justify-center rounded-full bg-stake-green/15 text-stake-green"
                            >
                                <svg
                                    class="size-4"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-xs text-muted">
                                    {{ networkLabel }}
                                </p>
                                <p class="font-mono text-sm">
                                    {{ maskedAddress }}
                                </p>
                            </div>
                        </div>
                        <p v-else class="text-sm text-muted">
                            {{ t('dashboard.noWallet') }}
                        </p>

                        <button
                            @click="connectPayoutWallet"
                            type="button"
                            class="btn-primary inline-flex h-[var(--control-h)] items-center justify-center gap-2 px-5 text-base font-semibold transition"
                        >
                            {{
                                user.wallet_address
                                    ? t('dashboard.editWallet')
                                    : t('dashboard.linkWallet')
                            }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppShell>
</template>
