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
        class="inline-flex h-11 items-center justify-center gap-2 rounded-md bg-primary px-5 text-base font-semibold text-white transition hover:bg-primary-hover disabled:pointer-events-none disabled:opacity-50"
    >
        <slot />
    </button>
</template>
