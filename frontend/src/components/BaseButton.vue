<script setup lang="ts">
withDefaults(
    defineProps<{
        type?: 'button' | 'submit';
        disabled?: boolean;
    }>(),
    {
        type: 'button',
        disabled: false,
    },
);
</script>

<template>
    <button
        :type="type"
        :disabled="disabled"
        class="btn-primary inline-flex h-[var(--control-h)] items-center justify-center gap-2 px-5 text-base font-semibold transition disabled:pointer-events-none disabled:opacity-50"
    >
        <slot />
    </button>
</template>
