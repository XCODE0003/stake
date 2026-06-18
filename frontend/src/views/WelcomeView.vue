<script setup lang="ts">
import { computed } from 'vue';
import { RouterLink } from 'vue-router';
import StakeLogo from '@/components/StakeLogo.vue';
import { useAuthStore } from '@/stores/auth';

const auth = useAuthStore();
const loggedIn = computed(() => auth.isAuthenticated && auth.user !== null);
</script>

<template>
    <div
        class="relative flex min-h-svh flex-col items-center justify-center overflow-hidden bg-background px-6 text-center"
    >
        <div
            class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_50%_-20%,rgba(20,117,225,0.28)_0%,transparent_60%)]"
        />

        <div class="relative flex w-full max-w-md flex-col items-center gap-10">
            <StakeLogo class="h-16 w-auto sm:h-20" />

            <p class="max-w-sm text-lg leading-relaxed font-medium text-muted">
                Working with us is now easier, faster and more comfortable!
            </p>

            <div v-if="loggedIn" class="w-full">
                <RouterLink
                    :to="{ name: 'dashboard' }"
                    class="inline-flex h-12 w-full items-center justify-center rounded-md bg-primary px-6 text-base font-semibold text-white shadow-lg transition hover:bg-primary-hover"
                >
                    Dashboard
                </RouterLink>
            </div>

            <div v-else class="flex w-full flex-col gap-3 sm:flex-row">
                <RouterLink
                    :to="{ name: 'login' }"
                    class="inline-flex h-12 flex-1 items-center justify-center rounded-md bg-primary px-6 text-base font-semibold text-white shadow-lg transition hover:bg-primary-hover"
                >
                    Login
                </RouterLink>
                <RouterLink
                    :to="{ name: 'register' }"
                    class="inline-flex h-12 flex-1 items-center justify-center rounded-md border border-border bg-surface-high px-6 text-base font-semibold text-foreground transition hover:border-primary/40"
                >
                    Register
                </RouterLink>
            </div>
        </div>

        <p class="relative mt-16 text-xs text-muted/70">
            © Stake. Affiliate program.
        </p>
    </div>
</template>
