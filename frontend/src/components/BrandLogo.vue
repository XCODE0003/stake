<script setup lang="ts">
// Active-brand wordmark. Size via a height utility, e.g. <BrandLogo class="h-8" />.
import { useTheme } from '@/themes/useTheme';

const theme = useTheme();
</script>

<template>
    <img
        :src="theme.logo"
        :alt="theme.brand"
        draggable="false"
        class="block w-auto select-none object-contain"
    />
</template>
