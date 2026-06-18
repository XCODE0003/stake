<script setup lang="ts">
import AppShell from '@/layouts/AppShell.vue';
</script>

<template>
    <AppShell>
        <div class="mx-auto flex w-full max-w-6xl flex-col gap-6 p-4 sm:p-8">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">Transactions</h1>
                <p class="text-sm text-muted">Your payout history.</p>
            </div>

            <div
                class="flex flex-col items-center rounded-lg border border-border bg-card p-12 text-center"
            >
                <div
                    class="mb-4 flex size-12 items-center justify-center rounded-full bg-surface-high text-muted"
                >
                    <svg
                        class="size-6"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <polyline points="17 1 21 5 17 9" />
                        <path d="M3 11V9a4 4 0 0 1 4-4h14" />
                        <polyline points="7 23 3 19 7 15" />
                        <path d="M21 13v2a4 4 0 0 1-4 4H3" />
                    </svg>
                </div>
                <p class="text-base font-medium">No transactions yet</p>
                <p class="mt-1 text-sm text-muted">
                    You don't have any transactions yet.
                </p>
            </div>
        </div>
    </AppShell>
</template>
