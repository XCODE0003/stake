<script setup lang="ts">
import { RouterLink } from 'vue-router';
import StakeLogo from '@/components/StakeLogo.vue';

defineProps<{
    title: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex min-h-svh items-center justify-center bg-background p-4 sm:p-6">
        <div class="w-full max-w-md rounded-lg bg-card p-6 shadow-xl sm:p-8">
            <RouterLink to="/" class="inline-flex">
                <StakeLogo class="mb-6 h-8 w-auto" />
            </RouterLink>

            <div class="mb-6 space-y-1.5">
                <h1 class="text-3xl font-bold tracking-tight">{{ title }}</h1>
                <p v-if="description" class="text-sm text-muted">
                    {{ description }}
                </p>
            </div>

            <slot />
        </div>
    </div>
</template>
