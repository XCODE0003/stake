<script setup lang="ts">
import { computed } from 'vue';
import { RouterLink, useRouter } from 'vue-router';
import StakeLogo from '@/components/StakeLogo.vue';
import { useAuthStore } from '@/stores/auth';

const auth = useAuthStore();
const router = useRouter();

const initial = computed(() =>
    (auth.user?.name ?? '?').charAt(0).toUpperCase(),
);

async function logout(): Promise<void> {
    await auth.logout();
    router.push({ name: 'login' });
}
</script>

<template>
    <div class="flex min-h-svh bg-background">
        <!-- Sidebar -->
        <aside
            class="flex w-64 shrink-0 flex-col border-r border-border bg-sidebar"
        >
            <div class="flex items-center gap-2 px-5 py-5">
                <StakeLogo class="h-7 w-auto" />
                <span class="text-xs text-muted">Affiliate</span>
            </div>

            <nav class="flex flex-1 flex-col gap-1 px-3">
                <RouterLink
                    :to="{ name: 'dashboard' }"
                    class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium text-muted transition hover:bg-surface-high hover:text-foreground"
                    active-class="!bg-surface-high !text-foreground"
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
                        <rect x="3" y="3" width="7" height="7" rx="1" />
                        <rect x="14" y="3" width="7" height="7" rx="1" />
                        <rect x="14" y="14" width="7" height="7" rx="1" />
                        <rect x="3" y="14" width="7" height="7" rx="1" />
                    </svg>
                    Dashboard
                </RouterLink>

                <RouterLink
                    :to="{ name: 'support' }"
                    class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium text-muted transition hover:bg-surface-high hover:text-foreground"
                    active-class="!bg-surface-high !text-foreground"
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
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                    </svg>
                    Online support
                </RouterLink>

                <RouterLink
                    :to="{ name: 'transactions' }"
                    class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium text-muted transition hover:bg-surface-high hover:text-foreground"
                    active-class="!bg-surface-high !text-foreground"
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
                        <polyline points="17 1 21 5 17 9" />
                        <path d="M3 11V9a4 4 0 0 1 4-4h14" />
                        <polyline points="7 23 3 19 7 15" />
                        <path d="M21 13v2a4 4 0 0 1-4 4H3" />
                    </svg>
                    Transactions
                </RouterLink>

                <RouterLink
                    :to="{ name: 'statistics' }"
                    class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium text-muted transition hover:bg-surface-high hover:text-foreground"
                    active-class="!bg-surface-high !text-foreground"
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
                        <line x1="18" y1="20" x2="18" y2="10" />
                        <line x1="12" y1="20" x2="12" y2="4" />
                        <line x1="6" y1="20" x2="6" y2="14" />
                    </svg>
                    Statistics
                </RouterLink>
            </nav>

            <div class="border-t border-border p-3">
                <div class="flex items-center gap-3 rounded-md px-2 py-2">
                    <span
                        class="flex size-8 items-center justify-center rounded-lg bg-surface-high text-sm font-semibold"
                    >
                        {{ initial }}
                    </span>
                    <span class="flex-1 truncate text-sm">
                        {{ auth.user?.name }}
                    </span>
                </div>
                <button
                    class="mt-1 flex w-full items-center gap-3 rounded-md px-3 py-2 text-sm text-muted transition hover:bg-surface-high hover:text-foreground"
                    @click="logout"
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
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" y1="12" x2="9" y2="12" />
                    </svg>
                    Log out
                </button>
            </div>
        </aside>

        <!-- Main -->
        <main class="flex-1 overflow-x-hidden">
            <slot />
        </main>
    </div>
</template>
