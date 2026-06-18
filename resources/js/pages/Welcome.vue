<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import StakeLogo from '@/components/StakeLogo.vue';
import { dashboard, login } from '@/routes';
/* @chisel-registration */
import { register } from '@/routes';
/* @end-chisel-registration */
</script>

<template>
    <Head title="Stake — Affiliate" />

    <div
        class="relative flex min-h-svh flex-col items-center justify-center overflow-hidden bg-background px-6 text-center"
    >
        <!-- Subtle Stake-blue glow -->
        <div
            class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_50%_-20%,rgba(20,117,225,0.28)_0%,transparent_60%)]"
        />

        <div class="relative flex w-full max-w-md flex-col items-center gap-10">
            <StakeLogo class="h-16 w-auto sm:h-20" />

            <p
                class="max-w-sm text-lg leading-relaxed font-medium text-muted-foreground"
            >
                Working with us is now easier, faster and more comfortable!
            </p>

            <div v-if="$page.props.auth.user" class="w-full">
                <Link
                    :href="dashboard()"
                    class="inline-flex h-12 w-full items-center justify-center rounded-md bg-primary px-6 text-base font-semibold text-primary-foreground shadow-lg transition hover:bg-stake-hover"
                >
                    Dashboard
                </Link>
            </div>

            <div v-else class="flex w-full flex-col gap-3 sm:flex-row">
                <Link
                    :href="login()"
                    class="inline-flex h-12 flex-1 items-center justify-center rounded-md bg-primary px-6 text-base font-semibold text-primary-foreground shadow-lg transition hover:bg-stake-hover"
                >
                    Login
                </Link>
                <!-- @chisel-registration -->
                <Link
                    :href="register()"
                    class="inline-flex h-12 flex-1 items-center justify-center rounded-md border border-border bg-secondary px-6 text-base font-semibold text-secondary-foreground transition hover:bg-stake-surface-high"
                >
                    Register
                </Link>
                <!-- @end-chisel-registration -->
            </div>
        </div>

        <p class="relative mt-16 text-xs text-muted-foreground/70">
            © Stake. Affiliate program.
        </p>
    </div>
</template>
