<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { watch } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    InputOTP,
    InputOTPGroup,
    InputOTPSlot,
} from '@/components/ui/input-otp';
import { Spinner } from '@/components/ui/spinner';
import { logout } from '@/routes';

defineOptions({
    layout: {
        title: 'Verify your email',
        description: 'Enter the 6-digit code we sent to your email',
    },
});

defineProps<{
    status?: string;
    email?: string;
}>();

const form = useForm({ code: '' });
const resend = useForm({});

function submit(): void {
    if (form.processing || form.code.length !== 6) {
        return;
    }

    form.post('/email/verify-code', {
        preserveScroll: true,
        onError: () => form.reset('code'),
    });
}

// Submit automatically once all six digits are entered.
watch(
    () => form.code,
    (value) => {
        if (value.length === 6) {
            submit();
        }
    },
);

function resendCode(): void {
    resend.post('/email/verify-code/resend', { preserveScroll: true });
}
</script>

<template>
    <Head title="Verify your email" />

    <div class="flex flex-col gap-6">
        <p v-if="email" class="text-center text-sm text-muted-foreground">
            We sent a code to
            <span class="font-medium text-foreground">{{ email }}</span>
        </p>

        <div
            v-if="status === 'verification-code-sent'"
            class="rounded-md bg-stake-green/10 px-3 py-2 text-center text-sm font-medium text-stake-green"
        >
            A new code has been sent to your email.
        </div>

        <form class="flex flex-col items-center gap-4" @submit.prevent="submit">
            <InputOTP
                v-model="form.code"
                :maxlength="6"
                :disabled="form.processing"
            >
                <InputOTPGroup>
                    <InputOTPSlot :index="0" class="h-12 w-12 text-lg" />
                    <InputOTPSlot :index="1" class="h-12 w-12 text-lg" />
                    <InputOTPSlot :index="2" class="h-12 w-12 text-lg" />
                    <InputOTPSlot :index="3" class="h-12 w-12 text-lg" />
                    <InputOTPSlot :index="4" class="h-12 w-12 text-lg" />
                    <InputOTPSlot :index="5" class="h-12 w-12 text-lg" />
                </InputOTPGroup>
            </InputOTP>

            <InputError :message="form.errors.code" />

            <Button
                type="submit"
                class="w-full"
                :disabled="form.processing || form.code.length !== 6"
            >
                <Spinner v-if="form.processing" />
                Verify
            </Button>
        </form>

        <div class="flex flex-col items-center gap-3 text-sm">
            <button
                type="button"
                class="text-primary hover:underline disabled:opacity-50"
                :disabled="resend.processing"
                @click="resendCode"
            >
                Resend code
            </button>

            <Link
                :href="logout()"
                as="button"
                class="text-muted-foreground hover:underline"
            >
                Log out
            </Link>
        </div>
    </div>
</template>
