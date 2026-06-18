<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { useRouter } from 'vue-router';
import BaseButton from '@/components/BaseButton.vue';
import BaseSpinner from '@/components/BaseSpinner.vue';
import AuthCard from '@/layouts/AuthCard.vue';
import { firstError, validationErrors } from '@/lib/api';
import { useAuthStore } from '@/stores/auth';
import type { ValidationErrors } from '@/types';

const auth = useAuthStore();
const router = useRouter();

const code = ref('');
const errors = ref<ValidationErrors>({});
const processing = ref(false);
const resending = ref(false);
const resent = ref(false);

const email = computed(() => auth.user?.email ?? '');

function onInput(event: Event): void {
    code.value = (event.target as HTMLInputElement).value
        .replace(/\D/g, '')
        .slice(0, 6);
}

async function submit(): Promise<void> {
    if (processing.value || code.value.length !== 6) {
        return;
    }
    processing.value = true;
    errors.value = {};

    try {
        await auth.verifyEmail(code.value);
        router.push(
            auth.user?.needs_approval
                ? { name: 'pending' }
                : { name: 'dashboard' },
        );
    } catch (error) {
        errors.value = validationErrors(error);
        code.value = '';
    } finally {
        processing.value = false;
    }
}

watch(code, (value) => {
    if (value.length === 6) {
        submit();
    }
});

async function resend(): Promise<void> {
    resending.value = true;
    resent.value = false;
    try {
        await auth.resendCode();
        resent.value = true;
    } finally {
        resending.value = false;
    }
}

async function logout(): Promise<void> {
    await auth.logout();
    router.push({ name: 'login' });
}
</script>

<template>
    <AuthCard
        title="Verify your email"
        description="Enter the 6-digit code we sent to your email"
    >
        <div class="flex flex-col gap-5">
            <p v-if="email" class="text-sm text-muted">
                We sent a code to
                <span class="font-medium text-foreground">{{ email }}</span>
            </p>

            <div
                v-if="resent"
                class="rounded-md bg-stake-green/10 px-3 py-2 text-center text-sm font-medium text-stake-green"
            >
                A new code has been sent to your email.
            </div>

            <form class="flex flex-col gap-4" @submit.prevent="submit">
                <input
                    :value="code"
                    inputmode="numeric"
                    autocomplete="one-time-code"
                    maxlength="6"
                    placeholder="------"
                    class="h-14 w-full rounded-md border border-input-border bg-sunken text-center font-mono text-2xl tracking-[0.5em] text-foreground outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/30"
                    @input="onInput"
                />
                <p
                    v-if="firstError(errors, 'code')"
                    class="text-center text-sm text-destructive"
                >
                    {{ firstError(errors, 'code') }}
                </p>

                <BaseButton
                    type="submit"
                    class="w-full"
                    :disabled="processing || code.length !== 6"
                >
                    <BaseSpinner v-if="processing" />
                    Verify
                </BaseButton>
            </form>

            <div class="flex flex-col items-center gap-3 text-sm">
                <button
                    type="button"
                    class="text-primary hover:underline disabled:opacity-50"
                    :disabled="resending"
                    @click="resend"
                >
                    Resend code
                </button>
                <button
                    type="button"
                    class="text-muted hover:underline"
                    @click="logout"
                >
                    Log out
                </button>
            </div>
        </div>
    </AuthCard>
</template>
