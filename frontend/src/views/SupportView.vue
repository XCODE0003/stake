<script setup lang="ts">
import { onMounted, ref } from 'vue';
import BaseButton from '@/components/BaseButton.vue';
import BaseSpinner from '@/components/BaseSpinner.vue';
import AppShell from '@/layouts/AppShell.vue';
import { firstError, validationErrors } from '@/lib/api';
import { useAuthStore } from '@/stores/auth';
import type { Ticket, ValidationErrors } from '@/types';

const auth = useAuthStore();

const tickets = ref<Ticket[]>([]);
const loading = ref(true);
const message = ref('');
const sending = ref(false);
const errors = ref<ValidationErrors>({});

const statusStyles: Record<Ticket['status'], string> = {
    open: 'bg-amber-400/10 text-amber-400',
    answered: 'bg-stake-green/10 text-stake-green',
    closed: 'bg-muted/10 text-muted',
};

const statusLabels: Record<Ticket['status'], string> = {
    open: 'Open',
    answered: 'Answered',
    closed: 'Closed',
};

function formatDate(value: string | null): string {
    return value ? new Date(value).toLocaleString() : '';
}

async function submit(): Promise<void> {
    sending.value = true;
    errors.value = {};
    try {
        const ticket = await auth.createTicket(message.value);
        tickets.value.unshift(ticket);
        message.value = '';
    } catch (error) {
        errors.value = validationErrors(error);
    } finally {
        sending.value = false;
    }
}

onMounted(async () => {
    try {
        tickets.value = await auth.fetchTickets();
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <AppShell>
        <div class="mx-auto flex w-full max-w-3xl flex-col gap-6 p-4 sm:p-8">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">
                    Online support
                </h1>
                <p class="text-sm text-muted">
                    Open a ticket and our team will get back to you.
                </p>
            </div>

            <!-- New ticket -->
            <div class="rounded-lg border border-border bg-card p-6">
                <h2 class="text-base font-semibold">New ticket</h2>
                <form class="mt-4 flex flex-col gap-3" @submit.prevent="submit">
                    <textarea
                        v-model="message"
                        rows="4"
                        placeholder="Describe your question or issue…"
                        class="w-full rounded-md border border-input-border bg-sunken px-3 py-2 text-base text-foreground outline-none transition placeholder:text-muted/60 focus:border-primary focus:ring-2 focus:ring-primary/30"
                    ></textarea>
                    <p
                        v-if="firstError(errors, 'message')"
                        class="text-sm text-destructive"
                    >
                        {{ firstError(errors, 'message') }}
                    </p>
                    <div class="flex justify-end">
                        <BaseButton
                            type="submit"
                            :disabled="sending || message.trim().length === 0"
                        >
                            <BaseSpinner v-if="sending" />
                            Send
                        </BaseButton>
                    </div>
                </form>
            </div>

            <!-- Ticket list -->
            <div class="flex flex-col gap-4">
                <p v-if="loading" class="text-sm text-muted">Loading…</p>

                <div
                    v-else-if="tickets.length === 0"
                    class="rounded-lg border border-border bg-card p-8 text-center text-sm text-muted"
                >
                    You have no tickets yet.
                </div>

                <div
                    v-for="ticket in tickets"
                    :key="ticket.id"
                    class="rounded-lg border border-border bg-card p-5"
                >
                    <div class="flex items-center justify-between gap-3">
                        <span
                            class="rounded-full px-2.5 py-0.5 text-xs font-medium"
                            :class="statusStyles[ticket.status]"
                        >
                            {{ statusLabels[ticket.status] }}
                        </span>
                        <span class="text-xs text-muted">
                            {{ formatDate(ticket.created_at) }}
                        </span>
                    </div>

                    <p class="mt-3 text-sm whitespace-pre-line">
                        {{ ticket.message }}
                    </p>

                    <div
                        v-if="ticket.reply"
                        class="mt-4 rounded-md border-l-2 border-primary bg-surface-high/50 p-3"
                    >
                        <p class="text-xs font-medium text-primary">
                            Support reply
                        </p>
                        <p class="mt-1 text-sm whitespace-pre-line">
                            {{ ticket.reply }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AppShell>
</template>
