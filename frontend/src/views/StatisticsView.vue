<script setup lang="ts">
import { computed } from 'vue';
import AppShell from '@/layouts/AppShell.vue';
import { useAuthStore } from '@/stores/auth';

const auth = useAuthStore();
const user = computed(() => auth.user!);

function amount(value: string): string {
    const number = Number(value);
    return Number.isNaN(number)
        ? value
        : new Intl.NumberFormat('en-US', {
              minimumFractionDigits: 0,
              maximumFractionDigits: 2,
          }).format(number);
}

const casinoProfit = computed(() => amount(user.value.casino_profit));
const yourProfit = computed(() => amount(user.value.your_profit));
const currency = computed(() => user.value.fixed_payment_currency);
</script>

<template>
    <AppShell>
        <div class="mx-auto flex w-full max-w-6xl flex-col gap-6 p-4 sm:p-8">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">Statistics</h1>
                <p class="text-sm text-muted">Your affiliate performance.</p>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <!-- Referrals -->
                <div
                    class="flex items-start justify-between gap-4 rounded-lg border border-border bg-card p-6"
                >
                    <div class="space-y-2">
                        <p class="text-sm text-muted">Referrals</p>
                        <p class="text-3xl font-bold">
                            {{ user.referrals_count }}
                        </p>
                    </div>
                    <span
                        class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-[#40c8d0]/10 text-[#40c8d0]"
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

                <!-- Casino profit -->
                <div
                    class="flex items-start justify-between gap-4 rounded-lg border border-border bg-card p-6"
                >
                    <div class="space-y-2">
                        <p class="text-sm text-muted">Casino profit</p>
                        <p class="text-3xl font-bold">
                            {{ casinoProfit }}
                            <span class="text-lg text-muted">{{ currency }}</span>
                        </p>
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
                            <line x1="12" y1="1" x2="12" y2="23" />
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                        </svg>
                    </span>
                </div>

                <!-- Your profit -->
                <div
                    class="flex items-start justify-between gap-4 rounded-lg border border-border bg-card p-6"
                >
                    <div class="space-y-2">
                        <p class="text-sm text-muted">Your profit</p>
                        <p class="text-3xl font-bold text-stake-green">
                            {{ yourProfit }}
                            <span class="text-lg text-muted">{{ currency }}</span>
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
                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
                            <polyline points="17 6 23 6 23 12" />
                        </svg>
                    </span>
                </div>
            </div>
        </div>
    </AppShell>
</template>
