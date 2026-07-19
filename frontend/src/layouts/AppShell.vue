<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { RouterLink, useRoute, useRouter } from 'vue-router';
import BrandLogo from '@/components/BrandLogo.vue';
import { useI18n } from '@/i18n';
import { useAuthStore } from '@/stores/auth';

const auth = useAuthStore();
const router = useRouter();
const route = useRoute();
const { t } = useI18n();

const initial = computed(() =>
    (auth.user?.name ?? '?').charAt(0).toUpperCase(),
);

// Off-canvas drawer state — only relevant below the `lg` breakpoint, where the
// sidebar slides in over the content instead of sitting beside it.
const sidebarOpen = ref(false);

// Close the drawer whenever navigation happens, so tapping a link doesn't leave
// it hanging open over the new view.
watch(
    () => route.fullPath,
    () => {
        sidebarOpen.value = false;
    },
);

async function logout(): Promise<void> {
    await auth.logout();
    router.push({ name: 'login' });
}
</script>

<template>
    <div class="flex min-h-svh bg-background">
        <!-- Mobile drawer backdrop -->
        <div
            v-if="sidebarOpen"
            class="fixed inset-0 z-40 bg-black/60 lg:hidden"
            @click="sidebarOpen = false"
        />

        <!-- Sidebar: static column on desktop, slide-in drawer on mobile -->
        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-64 shrink-0 flex-col border-r border-border bg-sidebar transition-transform duration-200 ease-out lg:static lg:z-auto lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex items-center gap-2 px-5 py-5">
                <BrandLogo class="h-7 max-w-[150px]" />
                <span class="shrink-0 text-xs text-muted">
                    {{ t('nav.affiliate') }}
                </span>
                <button
                    class="ml-auto flex size-8 items-center justify-center rounded-md text-muted transition hover:bg-surface-high hover:text-foreground lg:hidden"
                    :aria-label="t('nav.closeMenu')"
                    @click="sidebarOpen = false"
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
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
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
                    {{ t('nav.dashboard') }}
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
                    {{ t('nav.support') }}
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
                    {{ t('nav.transactions') }}
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
                    {{ t('nav.statistics') }}
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
                    {{ t('nav.logout') }}
                </button>
            </div>
        </aside>

        <!-- Right column -->
        <div class="flex min-w-0 flex-1 flex-col">
            <!-- Mobile top bar with hamburger -->
            <header
                class="flex items-center gap-3 border-b border-border bg-sidebar px-4 py-3 lg:hidden"
            >
                <button
                    class="-ml-1 flex size-9 items-center justify-center rounded-md text-muted transition hover:bg-surface-high hover:text-foreground"
                    :aria-label="t('nav.openMenu')"
                    @click="sidebarOpen = true"
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
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <line x1="3" y1="12" x2="21" y2="12" />
                        <line x1="3" y1="18" x2="21" y2="18" />
                    </svg>
                </button>
                <BrandLogo class="h-6 max-w-[130px]" />
            </header>

            <!-- Main -->
            <main class="min-w-0 flex-1 overflow-x-hidden">
                <slot />
            </main>
        </div>
    </div>
</template>
