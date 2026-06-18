<script setup lang="ts">
defineProps<{ modelValue?: string }>();
const emit = defineEmits<{ 'update:modelValue': [value: string] }>();

function onInput(event: Event): void {
    emit('update:modelValue', (event.target as HTMLInputElement).value);
}
</script>

<template>
    <input
        :value="modelValue"
        class="h-11 w-full rounded-md border border-input-border bg-sunken px-3 text-base text-foreground outline-none transition placeholder:text-muted/60 focus:border-primary focus:ring-2 focus:ring-primary/30 disabled:opacity-50"
        @input="onInput"
    />
</template>
