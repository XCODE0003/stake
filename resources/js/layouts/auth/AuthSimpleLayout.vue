<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import StakeLogo from '@/components/StakeLogo.vue';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="flex min-h-svh flex-col items-center justify-center bg-background p-4 sm:p-6"
    >
        <div class="w-full max-w-md rounded-lg bg-card p-6 shadow-xl sm:p-8">
            <Link :href="home()" class="inline-flex">
                <StakeLogo class="mb-6 h-8 w-auto" />
                <span class="sr-only">{{ title }}</span>
            </Link>

            <div class="mb-6 space-y-1.5">
                <h1 class="text-3xl font-bold tracking-tight">{{ title }}</h1>
                <p class="text-sm text-muted-foreground">{{ description }}</p>
            </div>

            <slot />
        </div>
    </div>
</template>
