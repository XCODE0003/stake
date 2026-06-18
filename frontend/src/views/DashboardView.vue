<script setup lang="ts">
import { computed, onMounted, reactive, ref } from 'vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseInput from '@/components/BaseInput.vue';
import BaseSpinner from '@/components/BaseSpinner.vue';
import AppShell from '@/layouts/AppShell.vue';
import { firstError, validationErrors } from '@/lib/api';
import { useAuthStore } from '@/stores/auth';
import type { NetworkOption, ValidationErrors } from '@/types';

const auth = useAuthStore();

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

const dialogOpen = ref(false);
const errors = ref<ValidationErrors>({});
const saving = ref(false);
const walletForm = reactive({
    wallet_network: user.value.wallet_network ?? '',
    wallet_address: user.value.wallet_address ?? '',
});

function openDialog(): void {
    walletForm.wallet_network = user.value.wallet_network ?? '';
    walletForm.wallet_address = user.value.wallet_address ?? '';
    errors.value = {};
    dialogOpen.value = true;
}

async function saveWallet(): Promise<void> {
    saving.value = true;
    errors.value = {};
    try {
        await auth.updateWallet({ ...walletForm });
        dialogOpen.value = false;
    } catch (error) {
        errors.value = validationErrors(error);
    } finally {
        saving.value = false;
    }
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
                    Welcome back, {{ user.name }}
                </h1>
                <p class="text-sm text-muted">
                    Here are your current affiliate terms.
                </p>
            </div>

            <!-- Stats -->
            <div class="grid gap-4 md:grid-cols-3">
                <div
                    class="flex items-start justify-between gap-4 rounded-lg border border-border bg-card p-6 transition-colors hover:border-primary/40"
                >
                    <div class="space-y-2">
                        <p class="text-sm text-muted">Fixed payment</p>
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
                        <p class="text-sm text-muted">Streams</p>
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
                        <p class="text-sm text-muted">Referrals</p>
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
                        <h2 class="text-base font-semibold">Payout wallet</h2>
                        <p class="text-sm text-muted">
                            Link a wallet address to receive payouts.
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
                            No wallet linked yet.
                        </p>

                        <BaseButton @click="openDialog">
                            {{
                                user.wallet_address
                                    ? 'Edit wallet'
                                    : 'Link payout wallet'
                            }}
                        </BaseButton>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wallet modal -->
        <div
            v-if="dialogOpen"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
            @click.self="dialogOpen = false"
        >
            <div class="w-full max-w-md rounded-lg bg-card p-6 shadow-xl">
                <h3 class="text-lg font-semibold">Link payout wallet</h3>
                <p class="mt-1 text-sm text-muted">
                    Select a network and enter your wallet address.
                </p>

                <form class="mt-5 flex flex-col gap-4" @submit.prevent="saveWallet">
                    <div class="grid gap-2">
                        <label for="network" class="text-sm font-medium">
                            Network
                        </label>
                        <select
                            id="network"
                            v-model="walletForm.wallet_network"
                            class="h-11 w-full rounded-md border border-input-border bg-sunken px-3 text-base text-foreground outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/30"
                        >
                            <option value="" disabled>Select a network</option>
                            <option
                                v-for="option in networks"
                                :key="option.value"
                                :value="option.value"
                            >
                                {{ option.label }}
                            </option>
                        </select>
                        <p
                            v-if="firstError(errors, 'wallet_network')"
                            class="text-sm text-destructive"
                        >
                            {{ firstError(errors, 'wallet_network') }}
                        </p>
                    </div>

                    <div class="grid gap-2">
                        <label for="address" class="text-sm font-medium">
                            Wallet address
                        </label>
                        <BaseInput
                            id="address"
                            v-model="walletForm.wallet_address"
                            placeholder="e.g. TXXXXXXXXXXXXXXXXXXX"
                            autocomplete="off"
                        />
                        <p
                            v-if="firstError(errors, 'wallet_address')"
                            class="text-sm text-destructive"
                        >
                            {{ firstError(errors, 'wallet_address') }}
                        </p>
                    </div>

                    <div class="mt-2 flex justify-end gap-3">
                        <button
                            type="button"
                            class="rounded-md px-4 py-2 text-sm text-muted transition hover:bg-surface-high hover:text-foreground"
                            @click="dialogOpen = false"
                        >
                            Cancel
                        </button>
                        <BaseButton type="submit" :disabled="saving">
                            <BaseSpinner v-if="saving" />
                            Save
                        </BaseButton>
                    </div>
                </form>
            </div>
        </div>
    </AppShell>
</template>
